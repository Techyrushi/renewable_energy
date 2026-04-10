<?php
require_once __DIR__ . '/includes/cms.php';
$sr_form_success = false;
$sr_form_error = '';
$sr_form_notice = '';
$sr_recaptcha_site_key = getenv('SR_RECAPTCHA_SITE_KEY') ?: '';
$sr_recaptcha_secret = getenv('SR_RECAPTCHA_SECRET') ?: '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sr_contact_form'])) {
	$full_name = trim((string) ($_POST['full_name'] ?? ''));
	$phone = trim((string) ($_POST['phone'] ?? ''));
	$email = trim((string) ($_POST['email'] ?? ''));
	$city = trim((string) ($_POST['city'] ?? ''));
	$customer_type = trim((string) ($_POST['customer_type'] ?? ''));
	$system_size = trim((string) ($_POST['system_size'] ?? ''));
	$source = trim((string) ($_POST['source'] ?? ''));
	$message = trim((string) ($_POST['message'] ?? ''));
	$honeypot = trim((string) ($_POST['company'] ?? ''));
	$phoneDigits = preg_replace('/\D+/', '', $phone);

	if ($honeypot !== '') {
		$sr_form_error = 'Unable to submit. Please try again.';
	} elseif ($full_name === '' || $phone === '' || $email === '' || $city === '' || $customer_type === '' || $system_size === '' || $source === '') {
		$sr_form_error = 'Please fill in all required fields.';
	} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$sr_form_error = 'Please enter a valid email address.';
	} elseif ($phoneDigits === '' || strlen($phoneDigits) < 8 || strlen($phoneDigits) > 15) {
		$sr_form_error = 'Please enter a valid phone number.';
	} else {
		if ($sr_recaptcha_secret !== '') {
			$token = trim((string) ($_POST['g-recaptcha-response'] ?? ''));
			if ($token === '') {
				$sr_form_error = 'Please complete the reCAPTCHA.';
			} else {
				$verify_response = '';
				$verify_payload = http_build_query([
					'secret' => $sr_recaptcha_secret,
					'response' => $token,
					'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
				]);

				if (function_exists('curl_init')) {
					$ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $verify_payload);
					curl_setopt($ch, CURLOPT_TIMEOUT, 8);
					$verify_response = (string) curl_exec($ch);
					curl_close($ch);
				} else {
					$context = stream_context_create([
						'http' => [
							'method' => 'POST',
							'header' => "Content-type: application/x-www-form-urlencoded\r\n",
							'content' => $verify_payload,
							'timeout' => 8,
						]
					]);
					$verify_response = (string) @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
				}

				$verify_json = json_decode($verify_response, true);
				if (!is_array($verify_json) || empty($verify_json['success'])) {
					$sr_form_error = 'reCAPTCHA verification failed. Please try again.';
				}
			}
		}

		if ($sr_form_error === '') {
			$sr_db = sr_cms_db_try();
			$enquiryId = 0;
			if ($sr_db instanceof mysqli) {
				$stmt = $sr_db->prepare('INSERT INTO cms_enquiries (full_name, phone, email, city, customer_type, system_size, source, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
				if ($stmt) {
					$stmt->bind_param('ssssssss', $full_name, $phone, $email, $city, $customer_type, $system_size, $source, $message);
					$stmt->execute();
					$enquiryId = (int) $stmt->insert_id;
					$stmt->close();
				}
			}
			if ($enquiryId <= 0) {
				$sr_form_error = 'Unable to save your enquiry right now. Please try again.';
			}
		}

		if ($sr_form_error === '') {
			$to = sr_cms_setting_get('company_email', 'info@shivanjalirenewables.com');
			$companyName = sr_cms_setting_get('company_name', 'Shivanjali Renewables');
			$siteLogo = trim(sr_cms_setting_get('site_logo', ''));
			$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
			$host = (string) ($_SERVER['HTTP_HOST'] ?? '');
			$base = $host !== '' ? ($scheme . '://' . $host) : '';
			$logoUrl = '';
			if ($base !== '' && $siteLogo !== '') {
				$logoUrl = preg_match('#^https?://#i', $siteLogo) ? $siteLogo : ($base . '/' . ltrim($siteLogo, '/'));
			}
			$submittedAt = date('Y-m-d H:i:s');

			$subjectAdmin = ($enquiryId > 0 ? ('Enquiry #' . $enquiryId . ' - ') : '') . 'Free Solar Quote Request - ' . $full_name;
			$bodyTextAdmin = "New enquiry received:\n\n"
				. ($enquiryId > 0 ? ("Enquiry ID: {$enquiryId}\n") : '')
				. "Submitted at: {$submittedAt}\n"
				. "Full Name: {$full_name}\n"
				. "Phone: {$phone}\n"
				. "Email: {$email}\n"
				. "City / Location: {$city}\n"
				. "Customer Type: {$customer_type}\n"
				. "Approx System Size: {$system_size}\n"
				. "Heard About Us: {$source}\n"
				. "Message: " . ($message !== '' ? $message : '(none)') . "\n";

			$brandHeader = '<div style="max-width:720px;margin:0 auto 16px auto;padding:18px 18px;border-radius:16px;background:#0b3a54;color:#fff;">'
				. '<div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">'
				. ($logoUrl !== '' ? '<img src="' . htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') . '" style="height:44px;max-width:220px;object-fit:contain;background:#fff;border-radius:12px;padding:6px;">' : '')
				. '<div style="font-size:16px;font-weight:700;letter-spacing:.2px;">' . htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') . '</div>'
				. '</div>'
				. '<div style="margin-top:10px;opacity:.9;">New enquiry received</div>'
				. '</div>';

			$cardWrapStart = '<div style="max-width:720px;margin:0 auto;padding:18px;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">';
			$cardWrapEnd = '</div>';

			$bodyHtmlAdmin = $brandHeader
				. $cardWrapStart
				. ($enquiryId > 0 ? '<div style="margin-bottom:10px;"><strong>Enquiry ID:</strong> ' . (int) $enquiryId . '</div>' : '')
				. '<div style="margin-bottom:14px;color:#64748b;">Submitted at: ' . htmlspecialchars($submittedAt, ENT_QUOTES, 'UTF-8') . '</div>'
				. '<table cellspacing="0" cellpadding="10" border="0" style="width:100%;border-collapse:collapse;">'
				. '<tr><th align="left">Full Name</th><td>' . htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8') . '</td></tr>'
				. '<tr><th align="left">Phone</th><td>' . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . '</td></tr>'
				. '<tr><th align="left">Email</th><td>' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '</td></tr>'
				. '<tr><th align="left">City / Location</th><td>' . htmlspecialchars($city, ENT_QUOTES, 'UTF-8') . '</td></tr>'
				. '<tr><th align="left">Customer Type</th><td>' . htmlspecialchars($customer_type, ENT_QUOTES, 'UTF-8') . '</td></tr>'
				. '<tr><th align="left">Approx System Size</th><td>' . htmlspecialchars($system_size, ENT_QUOTES, 'UTF-8') . '</td></tr>'
				. '<tr><th align="left">Heard About Us</th><td>' . htmlspecialchars($source, ENT_QUOTES, 'UTF-8') . '</td></tr>'
				. '<tr><th align="left">Message</th><td>' . nl2br(htmlspecialchars($message !== '' ? $message : '(none)', ENT_QUOTES, 'UTF-8')) . '</td></tr>'
				. '</table>'
				. $cardWrapEnd;

			$sentAdmin = sr_cms_send_mail($to, $companyName, $subjectAdmin, $bodyTextAdmin, $bodyHtmlAdmin, $email, $full_name);
			$errAdmin = sr_cms_mail_last_error();

			$subjectUser = ($enquiryId > 0 ? ('Enquiry #' . $enquiryId . ' - ') : '') . 'We received your enquiry - ' . $companyName;
			$bodyTextUser = "Hi {$full_name},\n\n"
				. "Thanks for reaching out to {$companyName}. We have received your enquiry and our team will contact you shortly.\n\n"
				. ($enquiryId > 0 ? ("Enquiry ID: {$enquiryId}\n") : '')
				. "Summary:\n"
				. "Phone: {$phone}\n"
				. "City / Location: {$city}\n"
				. "Customer Type: {$customer_type}\n"
				. "Approx System Size: {$system_size}\n\n"
				. "Regards,\n{$companyName}\n";
			$bodyHtmlUser = $brandHeader
				. $cardWrapStart
				. '<p style="margin:0 0 10px 0;">Hi ' . htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8') . ',</p>'
				. '<p style="margin:0 0 12px 0;">Thanks for reaching out to <strong>' . htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') . '</strong>. We have received your enquiry and our team will contact you shortly.</p>'
				. ($enquiryId > 0 ? '<div style="margin:10px 0 14px 0;padding:10px 12px;border-radius:12px;background:#f1f5f9;"><strong>Enquiry ID:</strong> ' . (int) $enquiryId . '</div>' : '')
				. '<div style="margin-top:16px;padding:12px;border:1px solid #e5e7eb;border-radius:12px;background:#fafafa;">'
				. '<div><strong>Phone:</strong> ' . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . '</div>'
				. '<div><strong>City / Location:</strong> ' . htmlspecialchars($city, ENT_QUOTES, 'UTF-8') . '</div>'
				. '<div><strong>Customer Type:</strong> ' . htmlspecialchars($customer_type, ENT_QUOTES, 'UTF-8') . '</div>'
				. '<div><strong>Approx System Size:</strong> ' . htmlspecialchars($system_size, ENT_QUOTES, 'UTF-8') . '</div>'
				. '</div>'
				. '<p style="margin-top:16px;">Regards,<br>' . htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') . '</p>'
				. $cardWrapEnd;

			$sentUser = sr_cms_send_mail($email, $full_name, $subjectUser, $bodyTextUser, $bodyHtmlUser, $to, $companyName);
			$errUser = sr_cms_mail_last_error();

			$sr_form_success = true;
			$_POST = [];
			if (!$sentAdmin || !$sentUser) {
				$detail = $errAdmin !== '' ? $errAdmin : $errUser;
				$sr_form_notice = $detail !== ''
					? ('Your enquiry is saved successfully. Email could not be sent: ' . $detail)
					: 'Your enquiry is saved successfully. Email delivery is pending; please try again later if you do not receive a confirmation.';
			}
		}
	}
}
?>
<?php include 'includes/header.php'; ?>
<?php
$sr_page = sr_cms_page_get('contact');
$sr_hero_title = $sr_page && trim((string) $sr_page['hero_title']) !== '' ? (string) $sr_page['hero_title'] : 'Let&#8217;s Build Your Solar Future Together';
$sr_hero_subtitle = $sr_page && trim((string) $sr_page['hero_subtitle']) !== '' ? (string) $sr_page['hero_subtitle'] : 'Get in touch with our team for a free consultation, site survey, or project proposal. We respond within 24 hours.';
$sr_banner_image = $sr_page && trim((string) ($sr_page['banner_image'] ?? '')) !== '' ? (string) $sr_page['banner_image'] : '';
$sr_page_override = $sr_page && trim((string) ($sr_page['content'] ?? '')) !== '' ? (string) $sr_page['content'] : '';

$sr_contact_reach_title = sr_cms_setting_get('contact_reach_title', 'Reach Us Directly');
$sr_contact_form_title = sr_cms_setting_get('contact_form_title', 'Get a Free Solar Quote');
$sr_contact_form_desc = sr_cms_setting_get('contact_form_desc', 'Get in touch with our team for a free consultation, site survey, or project proposal.');
$sr_contact_brands_title = sr_cms_setting_get('contact_brands_title', 'Top Brands We Trust for Your Solar System Needs');
$sr_contact_map_title = sr_cms_setting_get('contact_map_title', 'Map');
$sr_contact_directions_label = sr_cms_setting_get('contact_directions_label', 'Get Directions');
$sr_contact_map_embed_url = sr_cms_setting_get('contact_map_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3748.7218552306003!2d73.7840677759519!3d20.02018522165262!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bddeb01bf6b4547%3A0xb7f9cea04acc974e!2sABH%20Samruddhi%20Apartment!5e0!3m2!1sen!2sin!4v1774267316950!5m2!1sen!2sin');
$sr_contact_map_aria_label = sr_cms_setting_get('contact_map_aria_label', 'Shivanjali Renewables, Nashik');

$sr_company_email = sr_cms_setting_get('company_email', 'info@shivanjalirenewables.com');
$sr_company_address = sr_cms_setting_get('company_address', 'Office No. 505, ABH Samruddhi, Near Dream Castle Signal, Makhamalabad Road, Nashik – 422003, Maharashtra, India');
$sr_company_map_url = sr_cms_setting_get('company_map_url', 'https://maps.app.goo.gl/4r1P4qqp36AEcAce8');
$sr_company_phone1 = sr_cms_setting_get('company_phone1', '+91 8686 313 133');
$sr_company_phone1_tel = sr_cms_setting_get('company_phone1_tel', '+918686313133');
$sr_company_phone2 = sr_cms_setting_get('company_phone2', '+91 7447 777 070');
$sr_company_phone2_tel = sr_cms_setting_get('company_phone2_tel', '+917447777070');
$sr_company_phone3 = sr_cms_setting_get('company_phone3', '+91 8889 303 303');
$sr_company_phone3_tel = sr_cms_setting_get('company_phone3_tel', '+918889303303');
$sr_company_hours = sr_cms_setting_get('company_hours', 'Monday – Saturday: 9:00 AM – 6:00 PM');
$sr_web_root = rtrim(str_replace('\\', '/', (string) dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
if ($sr_web_root === '') {
	$sr_web_root = '';
}

$sr_social_facebook = trim(sr_cms_setting_get('social_facebook', ''));
$sr_social_instagram = trim(sr_cms_setting_get('social_instagram', ''));
$sr_social_linkedin = trim(sr_cms_setting_get('social_linkedin', ''));
$sr_social_youtube = trim(sr_cms_setting_get('social_youtube', ''));
$sr_social_whatsapp_url = trim(sr_cms_setting_get('social_whatsapp_url', ''));
$sr_social_whatsapp_tel = preg_replace('/\D+/', '', sr_cms_setting_get('company_whatsapp_tel', ''));
if ($sr_social_whatsapp_url === '' && $sr_social_whatsapp_tel !== '') {
	$sr_social_whatsapp_url = 'https://wa.me/' . $sr_social_whatsapp_tel;
}

$sr_socials = [
	[
		'key' => 'facebook',
		'enabled' => sr_cms_setting_get('social_facebook_enabled', '1') === '1' && $sr_social_facebook !== '',
		'url' => $sr_social_facebook,
		'icon' => 'fa-facebook-f',
		'label' => 'Facebook',
	],
	[
		'key' => 'instagram',
		'enabled' => sr_cms_setting_get('social_instagram_enabled', '1') === '1' && $sr_social_instagram !== '',
		'url' => $sr_social_instagram,
		'icon' => 'fa-instagram',
		'label' => 'Instagram',
	],
	[
		'key' => 'linkedin',
		'enabled' => sr_cms_setting_get('social_linkedin_enabled', '1') === '1' && $sr_social_linkedin !== '',
		'url' => $sr_social_linkedin,
		'icon' => 'fa-linkedin',
		'label' => 'LinkedIn',
	],
	[
		'key' => 'youtube',
		'enabled' => sr_cms_setting_get('social_youtube_enabled', '1') === '1' && $sr_social_youtube !== '',
		'url' => $sr_social_youtube,
		'icon' => 'fa-youtube-play',
		'label' => 'YouTube',
	],
	[
		'key' => 'whatsapp',
		'enabled' => sr_cms_setting_get('social_whatsapp_enabled', '1') === '1' && $sr_social_whatsapp_url !== '',
		'url' => $sr_social_whatsapp_url,
		'icon' => 'fa-whatsapp',
		'label' => 'WhatsApp Business',
	],
];

$sr_client_logos = [];
$sr_db = sr_cms_db_try();
if ($sr_db instanceof mysqli) {
	$res = $sr_db->query('SELECT id, image, label, url FROM cms_client_logos WHERE is_active=1 ORDER BY sort_order ASC, updated_at DESC LIMIT 100');
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$sr_client_logos[] = $row;
		}
		$res->free();
	}
	if (!$sr_client_logos) {
		$defaults = [];
		for ($i = 1; $i <= 12; $i++) {
			$no = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
			$defaults[] = 'images/client/client-dark-' . $no . '.png';
		}
		$ins = $sr_db->prepare('INSERT INTO cms_client_logos (image, label, url, sort_order, is_active) VALUES (?, ?, "", ?, 1)');
		if ($ins) {
			$sort = 0;
			foreach ($defaults as $img) {
				$label = 'Client';
				$sort++;
				$ins->bind_param('ssi', $img, $label, $sort);
				$ins->execute();
			}
			$ins->close();
		}
		$res2 = $sr_db->query('SELECT id, image, label, url FROM cms_client_logos WHERE is_active=1 ORDER BY sort_order ASC, updated_at DESC LIMIT 100');
		if ($res2) {
			while ($row = $res2->fetch_assoc()) {
				$sr_client_logos[] = $row;
			}
			$res2->free();
		}
	}
}
?>
<!-- Title Bar -->
<div class="pbmit-title-bar-wrapper sr-why-hero" <?php echo $sr_banner_image !== '' ? (' style="background-image:url(' . htmlspecialchars($sr_banner_image, ENT_QUOTES, 'UTF-8') . ');"') : ''; ?>>
	<div class="container">
		<div class="pbmit-title-bar-content">
			<div class="pbmit-title-bar-content-inner">
				<div class="pbmit-tbar">
					<div class="pbmit-tbar-inner container">
						<h1 class="pbmit-tbar-title">
							<?php echo htmlspecialchars($sr_hero_title, ENT_QUOTES, 'UTF-8'); ?></h1>
						<?php if (trim($sr_hero_subtitle) !== '') { ?>
							<p class="pbmit-tbar-subtitle mb-0"><?php echo $sr_hero_subtitle; ?></p>
						<?php } ?>
					</div>
				</div>
				<div class="pbmit-breadcrumb">
					<div class="pbmit-breadcrumb-inner">
						<span>
							<a title="" href="./" class="home"><span>Home</span></a>
						</span>
						<i class="pbmit-base-icon-arrow-right-2"></i>
						<span><span class="post-root post post-post current-item"> Contact Us</span></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Title Bar End-->
</header>

<!-- Contact Us Content -->
<div class="page-content contact-us">
	<?php if ($sr_page_override !== '') { ?>
		<?php echo $sr_page_override; ?>
	<?php } else { ?>

		<!-- Ihbox -->
		<section class="ihbox-section">
			<div class="container">
				<div class="pbmit-heading-subheading text-center mb-5">
					<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_contact_reach_title, ENT_QUOTES, 'UTF-8'); ?>
					</h2>
				</div>
				<div class="row">
					<div class="col-md-12 col-xl-4 pbmit-column mb-xl-0 mb-4">
						<div class="pbmit-ihbox-style-3">
							<div class="pbmit-ihbox-box">
								<div class="pbmit-ihbox-icon">
									<div class="pbmit-ihbox-icon-wrapper pbmit-icon-type-icon">
										<svg height="512" viewBox="0 0 60 60" width="512"
											xmlns="http://www.w3.org/2000/svg">
											<g fill="none" fill-rule="evenodd">
												<g fill="rgb(0,0,0)" fill-rule="nonzero" transform="translate(0 -1)">
													<path
														d="m31.2383 34.99c-2.2415644-.0103981-4.4685165-.3615784-6.6045-1.0415-3.4128423-1.1417755-5.9477395-4.0310188-6.6358-7.5634-.7529-3.6265.6192-7.461 3.7637-10.52.3374-.3286.688-.6433667 1.0518-.9443 3.2196288-2.6954608 7.5602996-3.6321929 11.605-2.5044 3.9157536 1.2498134 6.6214551 4.8283567 6.7568 8.9365.1466146 2.3647451-.6241352 4.6949748-2.1519 6.5059-1.1783023 1.4842706-3.0874488 2.185355-4.9463 1.8164-.7602176-.1577458-1.4234762-.6181385-1.8371-1.2752-.3711287-.6382278-.4663065-1.4000836-.2636-2.11.8745-3.3056 1.7636-8.3291 1.7724-8.38.0621106-.3519066.3072431-.6439461.6430585-.76611s.7112953-.0558927.985.17385c.2737048.2297427.4040521.5880534.3419415.93996-.0371.209-.9126 5.1572-1.8086 8.5435-.063952.1876506-.0478957.3933459.0444.5688.1382799.191343.3469169.3199065.58.3574 1.1379287.1899234 2.2873612-.2665919 2.9849-1.1855 1.1864016-1.4193487 1.780845-3.2409596 1.66-5.0869-.0977844-3.2700176-2.242135-6.124934-5.3554-7.13-3.3983056-.9347035-7.0389503-.1361935-9.7341 2.135-.3213.2667-.6338.5459-.9336.8379-1.5546 1.5122-4.0371 4.65-3.2006 8.68.5620337 2.8154834 2.5728764 5.1228675 5.2851 6.0645 4.69 1.4961 11.4292 1.6762 14.98-2.7388.3488575-.4201425.9702584-.4827231 1.3958849-.1405779s.4980618.9624746.1627151 1.3934779c-2.6257 3.2653-6.6213 4.4335-10.5412 4.4335z">
													</path>
													<path
														d="m27.8149 29.8052c-1.0570282.0181646-2.0880311-.3284577-2.9194-.9815-1.9194-1.5361-1.9722-4.2046-1.4341-6.0219.1812165-.6052113.4245444-1.1900564.7261-1.7452.7503224-1.5138869 1.9500705-2.7589691 3.4351-3.5649 1.8493462-.9596056 4.1088442-.5957076 5.5634.896.7216241.7908253 1.2651883 1.727263 1.594 2.7461.1741403.5170195-.0982507 1.0782018-.6121886 1.2612366-.513938.1830348-1.0797365-.0796331-1.2716114-.5903366-.2370831-.7527039-.6311586-1.4465454-1.1562-2.0357-.8480317-.8672392-2.1694059-1.0645586-3.2338-.4829-1.1136951.6280705-2.0087091 1.5816703-2.565 2.7329-.2337738.431336-.4225991.8855555-.5635 1.3555-.3886 1.3145-.3032 3.03.7691 3.8887 1.1723.9414 3.15.5434 4.2627-.4165.829232-.746533 1.5417334-1.6132856 2.1137-2.5713.1852213-.3103564.5234962-.4966162.8847981-.4871844.3613019.0094317.6893982.213087.8581746.5326844.1687764.3195975.1519648.7053954-.0439727 1.0091-.6774817 1.1288507-1.5209182 2.1493684-2.502 3.0273-1.0934765.9226171-2.4746572 1.4346934-3.9053 1.4479z">
													</path>
													<path
														d="m57 61h-54c-1.65610033-.0018187-2.99818129-1.3438997-3-3v-37c.00001572-.3877788.22421679-.7405895.57528314-.9052857s.76569921-.1115825 1.06391686.1362857l24.5371 20.3925c2.2201734 1.8335597 5.4293979 1.8329723 7.6489-.0014l24.5348-20.3911c.2981965-.248376.7131706-.3017925 1.0645472-.1370311.3513766.1647613.5756955.5179438.5754528.9060311v37c-.0018187 1.6561003-1.3438997 2.9981813-3 3zm-55-37.8687v34.8687c.00071619.5519878.44801218.9992838 1 1h54c.5520791-.0004962.9995038-.4479209 1-1v-34.8687l-22.8979 19.03c-2.9606327 2.446724-7.241348 2.4473534-10.2027.0015z">
													</path>
													<path
														d="m1.001 22c-.4355701.0003953-.82129491-.2811908-.95363398-.6961701-.13233908-.4149794.01915201-.8678864.37453398-1.1197299l9-6.38c.45059523-.3122969 1.0686272-.2035665 1.3855809.2437653s.2146394 1.0664583-.2293809 1.3880347l-9 6.38c-.16852428.1199208-.37026338.1842773-.5771.1841z">
													</path>
													<path
														d="m58.999 22c-.2068366.0001773-.4085757-.0641792-.5771-.1841l-9-6.38c-.4440203-.3215764-.5463346-.9407029-.2293809-1.3880347s.9349857-.5560622 1.3855809-.2437653l9 6.38c.355382.2518435.5068731.7047505.374534 1.1197299-.1323391.4149793-.5180639.6965654-.953634.6961701z">
													</path>
													<path
														d="m39.2393 8c-.2070115.00010935-.4089216-.06423486-.5777-.1841l-4.78-3.39c-2.2188862-1.87501472-5.4612857-1.89454674-7.7026-.0464l-4.84 3.4365c-.291493.20671388-.6711242.24221572-.9958901.09313231-.324766-.1490834-.5453271-.46010273-.5786-.81590001-.033273-.35579728.1257971-.70231843.4172901-.9090323l4.78-3.39c2.9536144-2.41503467 7.2051289-2.39582552 10.1368.0458l4.72 3.3438c.3556854.25178052.5073677.70490665.3749428 1.12008029s-.5184618.69678741-.9542428.69611971z">
													</path>
													<path
														d="m1.65 60.46c-.42143186.0003675-.79786247-.263514-.94122905-.6598105-.14336657-.3962966-.02289448-.8399405.30122905-1.1092895l22.82-18.96c.2742111-.2331685.6525759-.3009793.9906961-.1775532.3381203.1234261.5838207.4190432.643333.7740327.0595122.3549896-.0763472.7145733-.3557291.9415205l-22.82 18.96c-.17916597.149357-.40504487.2311377-.6383.2311z">
													</path>
													<path
														d="m58.3486 60.46c-.2330738.0001375-.4587858-.0816243-.6377-.231l-22.82-18.96c-.2793819-.2269472-.4152413-.5865309-.3557291-.9415205.0595123-.3549895.3052127-.6506066.643333-.7740327.3381202-.1234261.716485-.0556153.9906961.1775532l22.8194 18.96c.3241235.269349.4445956.7129929.301229 1.1092895-.1433665.3962965-.5197971.660178-.941229.6598105z">
													</path>
													<path
														d="m50 29.48c-.5522847 0-1-.4477153-1-1v-20.4722c-.0029.021-.043-.0073-.11-.0078h-37.78c-.0442022-.00271479-.0875817.01282953-.12.043l.01 20.437c0 .5522847-.4477153 1-1 1-.55228475 0-1-.4477153-1-1v-20.48c.03226085-1.13410356.97579439-2.02844815 2.11-2h37.78c1.134163-.02833724 2.0776307.86594492 2.11 2v20.48c0 .5522847-.4477153 1-1 1z">
													</path>
												</g>
											</g>
										</svg>
									</div>
								</div>
								<div class="pbmit-ihbox-contents">
									<h2 class="pbmit-element-title">Email</h2>
									<div class="pbmit-heading-desc">
										<a
											href="mailto:<?php echo htmlspecialchars($sr_company_email, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($sr_company_email, ENT_QUOTES, 'UTF-8'); ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-xl-4 pbmit-column mb-xl-0 mb-4">
						<div class="pbmit-ihbox-style-3">
							<div class="pbmit-ihbox-box">
								<div class="pbmit-ihbox-icon">
									<div class="pbmit-ihbox-icon-wrapper pbmit-icon-type-icon">
										<svg clip-rule="evenodd" fill-rule="evenodd" height="512"
											image-rendering="optimizeQuality" shape-rendering="geometricPrecision"
											text-rendering="geometricPrecision" viewBox="0 0 512 512" width="512">
											<g>
												<g>
													<g>
														<g>
															<path
																d="m256 392.69c-2.04 0-3.94-1.04-5.04-2.75-5.81-9.03-14.14-21.06-23.78-34.98-44.13-63.74-110.81-160.06-110.81-215.23 0-77 62.64-139.64 139.63-139.64 77 0 139.64 62.64 139.64 139.64 0 55.17-66.69 151.49-110.82 215.22-9.64 13.93-17.97 25.96-23.77 34.99-1.11 1.71-3.01 2.75-5.05 2.75zm0-380.6c-70.38 0-127.64 57.26-127.64 127.64 0 51.42 68.04 149.69 108.68 208.4 7.21 10.4 13.68 19.76 18.96 27.65 5.28-7.9 11.76-17.25 18.96-27.65 40.65-58.71 108.68-156.98 108.68-208.4 0-70.38-57.26-127.64-127.64-127.64z">
															</path>
														</g>
														<g>
															<path
																d="m256 222.51c-42.69 0-77.43-34.73-77.43-77.43 0-42.69 34.74-77.43 77.43-77.43 42.7 0 77.43 34.74 77.43 77.43 0 42.7-34.73 77.43-77.43 77.43zm0-142.86c-36.08 0-65.43 29.35-65.43 65.43s29.35 65.43 65.43 65.43 65.43-29.35 65.43-65.43-29.35-65.43-65.43-65.43z">
															</path>
														</g>
														<g>
															<path
																d="m256.14 464.34c-2.54 0-5.08-.04-7.64-.13-29.33-1.07-56.23-8.21-75.74-20.11-19.63-11.96-29.92-27.68-28.98-44.25 1.04-18.22 15.2-34.54 39.88-45.97 3-1.39 6.57-.08 7.96 2.93 1.39 3 .08 6.57-2.92 7.96-20.17 9.34-32.18 22.37-32.94 35.76-.68 11.94 7.58 23.78 23.24 33.33 37 22.55 101.73 24.86 144.31 5.15 20.16-9.34 32.17-22.38 32.93-35.77.68-11.94-7.57-23.77-23.24-33.32-2.83-1.73-3.73-5.42-2-8.25 1.72-2.83 5.41-3.72 8.24-2 19.63 11.97 29.92 27.68 28.98 44.25-1.04 18.22-15.2 34.55-39.88 45.97-20.29 9.4-45.69 14.45-72.2 14.45z">
															</path>
														</g>
														<g>
															<path
																d="m256.23 511.91c-4.02 0-8.05-.08-12.09-.22-46.33-1.69-88.75-12.93-119.46-31.65-30.22-18.42-46.07-42.4-44.64-67.52 1.58-27.77 23.5-52.8 61.72-70.5 3-1.39 6.57-.08 7.96 2.93 1.39 3 .08 6.57-2.92 7.96-33.52 15.51-53.49 37.49-54.78 60.29-1.17 20.49 12.65 40.59 38.91 56.6 60.09 36.63 165.19 40.41 234.28 8.43 33.51-15.52 53.48-37.49 54.78-60.29 1.17-20.49-12.65-40.59-38.91-56.6-2.83-1.73-3.73-5.42-2-8.24 1.72-2.83 5.41-3.73 8.24-2 30.22 18.42 46.08 42.4 44.64 67.52-1.58 27.76-23.5 52.8-61.71 70.49-32.02 14.83-72.11 22.8-114.02 22.8z">
															</path>
														</g>
													</g>
												</g>
											</g>
										</svg>
									</div>
								</div>
								<div class="pbmit-ihbox-contents">
									<h2 class="pbmit-element-title">Office Address</h2>
									<div class="pbmit-heading-desc">
										<?php echo nl2br(htmlspecialchars($sr_company_address, ENT_QUOTES, 'UTF-8')); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-xl-4 pbmit-column">
						<div class="pbmit-ihbox-style-3">
							<div class="pbmit-ihbox-box">
								<div class="pbmit-ihbox-icon">
									<div class="pbmit-ihbox-icon-wrapper pbmit-icon-type-icon">
										<svg version="1.1" xmlns="http://www.w3.org/2000/svg"
											xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
											viewBox="0 0 512.076 512.076" style="enable-background:new 0 0 512.076 512.076;"
											xml:space="preserve">
											<g transform="translate(-1 -1)">
												<g>
													<g>
														<path
															d="M499.639,396.039l-103.646-69.12c-13.153-8.701-30.784-5.838-40.508,6.579l-30.191,38.818 c-3.88,5.116-10.933,6.6-16.546,3.482l-5.743-3.166c-19.038-10.377-42.726-23.296-90.453-71.04s-60.672-71.45-71.049-90.453 l-3.149-5.743c-3.161-5.612-1.705-12.695,3.413-16.606l38.792-30.182c12.412-9.725,15.279-27.351,6.588-40.508l-69.12-103.646 C109.12,1.056,91.25-2.966,77.461,5.323L34.12,31.358C20.502,39.364,10.511,52.33,6.242,67.539 c-15.607,56.866-3.866,155.008,140.706,299.597c115.004,114.995,200.619,145.92,259.465,145.92 c13.543,0.058,27.033-1.704,40.107-5.239c15.212-4.264,28.18-14.256,36.181-27.878l26.061-43.315 C517.063,422.832,513.043,404.951,499.639,396.039z M494.058,427.868l-26.001,43.341c-5.745,9.832-15.072,17.061-26.027,20.173 c-52.497,14.413-144.213,2.475-283.008-136.32S8.29,124.559,22.703,72.054c3.116-10.968,10.354-20.307,20.198-26.061 l43.341-26.001c5.983-3.6,13.739-1.855,17.604,3.959l37.547,56.371l31.514,47.266c3.774,5.707,2.534,13.356-2.85,17.579 l-38.801,30.182c-11.808,9.029-15.18,25.366-7.91,38.332l3.081,5.598c10.906,20.002,24.465,44.885,73.967,94.379 c49.502,49.493,74.377,63.053,94.37,73.958l5.606,3.089c12.965,7.269,29.303,3.898,38.332-7.91l30.182-38.801 c4.224-5.381,11.87-6.62,17.579-2.85l103.637,69.12C495.918,414.126,497.663,421.886,494.058,427.868z">
														</path>
														<path
															d="M291.161,86.39c80.081,0.089,144.977,64.986,145.067,145.067c0,4.713,3.82,8.533,8.533,8.533s8.533-3.82,8.533-8.533 c-0.099-89.503-72.63-162.035-162.133-162.133c-4.713,0-8.533,3.82-8.533,8.533S286.448,86.39,291.161,86.39z">
														</path>
														<path
															d="M291.161,137.59c51.816,0.061,93.806,42.051,93.867,93.867c0,4.713,3.821,8.533,8.533,8.533 c4.713,0,8.533-3.82,8.533-8.533c-0.071-61.238-49.696-110.863-110.933-110.933c-4.713,0-8.533,3.82-8.533,8.533 S286.448,137.59,291.161,137.59z">
														</path>
														<path
															d="M291.161,188.79c23.552,0.028,42.638,19.114,42.667,42.667c0,4.713,3.821,8.533,8.533,8.533s8.533-3.82,8.533-8.533 c-0.038-32.974-26.759-59.696-59.733-59.733c-4.713,0-8.533,3.82-8.533,8.533S286.448,188.79,291.161,188.79z">
														</path>
													</g>
												</g>
											</g>
										</svg>
									</div>
								</div>
								<div class="pbmit-ihbox-contents">
									<h2 class="pbmit-element-title">Phone &amp; Working Hours</h2>
									<div class="pbmit-heading-desc">
										<?php if (trim($sr_company_phone1_tel) !== '' && trim($sr_company_phone1) !== '') { ?>
											<div><a
													href="tel:<?php echo htmlspecialchars($sr_company_phone1_tel, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($sr_company_phone1, ENT_QUOTES, 'UTF-8'); ?></a>
											</div>
										<?php } ?>
										<?php if (trim($sr_company_phone2_tel) !== '' && trim($sr_company_phone2) !== '') { ?>
											<div><a
													href="tel:<?php echo htmlspecialchars($sr_company_phone2_tel, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($sr_company_phone2, ENT_QUOTES, 'UTF-8'); ?></a>
											</div>
										<?php } ?>
										<?php if (trim($sr_company_phone3_tel) !== '' && trim($sr_company_phone3) !== '') { ?>
											<div><a
													href="tel:<?php echo htmlspecialchars($sr_company_phone3_tel, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($sr_company_phone3, ENT_QUOTES, 'UTF-8'); ?></a>
											</div>
										<?php } ?>
										<?php if (trim($sr_company_hours) !== '') { ?>
											<div class="mt-2">
												<?php echo htmlspecialchars($sr_company_hours, ENT_QUOTES, 'UTF-8'); ?></div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Ihbox End -->

		<!-- Contact Form -->
		<section>
			<div class="container">
				<div class="pbmit-heading-subheading text-center">
					<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_contact_form_title, ENT_QUOTES, 'UTF-8'); ?>
					</h2>
				</div>
				<div class="row g-0">
					<div class="col-md-12 col-xl-4 contact-form-left-col">
						<div class="contact-form-left-box">
							<div class="inner-box pbmit-bg-color-global">
								<div class="pbmit-icon-wrap">
									<div class="pbmit-icon">
										<i class="pbmit-base-icon-comment-3"></i>
									</div>
								</div>
								<div class="pbmit-heading-title">
									<h2>Our Commitment</h2>
								</div>
								<div class="sr-commitment-text">
									<p class="mb-3">We value your time. Our team will acknowledge your enquiry within 2
										business hours and schedule a site visit or consultation within 48 hours. No
										pressure. No obligation. Just clear, honest advice.</p>
								</div>
								<div class="sr-social-block">
									<h3 class="sr-social-title">Follow Us</h3>
									<style>
										.sr-social-links {
											display: flex;
											flex-wrap: wrap;
											gap: 10px
										}

										.sr-social-links li {
											margin: 0
										}

										.sr-social-link {
											display: inline-flex;
											align-items: center;
											gap: 10px;
											padding: 10px 14px;
											border-radius: 14px;
											background: rgba(255, 255, 255, .14);
											border: 1px solid rgba(255, 255, 255, .18);
											color: #fff;
											text-decoration: none;
											transition: transform .18s ease, background .18s ease, box-shadow .18s ease
										}

										.sr-social-link i {
											width: 34px;
											height: 34px;
											display: inline-flex;
											align-items: center;
											justify-content: center;
											border-radius: 12px;
											background: rgba(255, 255, 255, .18);
											transition: transform .18s ease, background .18s ease
										}

										.sr-social-link span {
											font-weight: 600;
											letter-spacing: .2px
										}

										.sr-social-link:hover {
											transform: translateY(-2px);
											background: rgba(255, 255, 255, .22);
											box-shadow: 0 10px 18px rgba(0, 0, 0, .18)
										}

										.sr-social-link:hover i {
											transform: scale(1.06)
										}

										.sr-social-link--facebook i {
											background: rgba(24, 119, 242, .38)
										}

										.sr-social-link--instagram i {
											background: rgba(225, 48, 108, .38)
										}

										.sr-social-link--linkedin i {
											background: rgba(10, 102, 194, .38)
										}

										.sr-social-link--youtube i {
											background: rgba(255, 0, 0, .34)
										}

										.sr-social-link--whatsapp i {
											background: rgba(37, 211, 102, .34)
										}
									</style>
									<ul class="sr-social-links list-unstyled mb-0">
										<?php foreach ($sr_socials as $s) { ?>
											<?php if (!empty($s['enabled'])) { ?>
												<li>
													<a class="sr-social-link sr-social-link--<?php echo htmlspecialchars((string) $s['key'], ENT_QUOTES, 'UTF-8'); ?>"
														href="<?php echo htmlspecialchars((string) $s['url'], ENT_QUOTES, 'UTF-8'); ?>"
														target="_blank" rel="noopener">
														<i
															class="fa <?php echo htmlspecialchars((string) $s['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
														<span><?php echo htmlspecialchars((string) $s['label'], ENT_QUOTES, 'UTF-8'); ?></span>
													</a>
												</li>
											<?php } ?>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-xl-8 contact-form-right-col">
						<div class="contact-form-right-box pbmit-bg-color-white">
							<div class="pbmit-custom-heading">
								<h2 class="pbmit-title">
									<?php echo htmlspecialchars($sr_contact_form_title, ENT_QUOTES, 'UTF-8'); ?></h2>
							</div>
							<p class="pb-2"><?php echo htmlspecialchars($sr_contact_form_desc, ENT_QUOTES, 'UTF-8'); ?></p>
							<?php if ($sr_form_success) { ?>
								<div class="alert alert-success">Thanks! Your request has been sent. We will contact you
									shortly.</div>
							<?php } elseif ($sr_form_error !== '') { ?>
								<div class="alert alert-danger">
									<?php echo htmlspecialchars($sr_form_error, ENT_QUOTES, 'UTF-8'); ?></div>
							<?php } ?>
							<form class="contact-form" method="post" id="sr-contact-form" action="">
								<input type="hidden" name="sr_contact_form" value="1">
								<div class="row">
									<div class="col-md-6">
										<input type="text" class="form-control" placeholder="Full Name *" name="full_name"
											required
											value="<?php echo htmlspecialchars((string) ($_POST['full_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-md-6">
										<input type="tel" class="form-control" placeholder="Phone Number *" name="phone"
											required
											value="<?php echo htmlspecialchars((string) ($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-md-6">
										<input type="email" class="form-control" placeholder="Email Address *" name="email"
											required
											value="<?php echo htmlspecialchars((string) ($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-md-6">
										<input type="text" class="form-control" placeholder="City / Location *" name="city"
											required
											value="<?php echo htmlspecialchars((string) ($_POST['city'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-md-6">
										<select class="form-select" name="customer_type" required>
											<option value="" disabled <?php echo (($_POST['customer_type'] ?? '') === '') ? 'selected' : ''; ?>>Type of Customer *</option>
											<option value="Residential" <?php echo (($_POST['customer_type'] ?? '') === 'Residential') ? 'selected' : ''; ?>>Residential</option>
											<option value="Commercial" <?php echo (($_POST['customer_type'] ?? '') === 'Commercial') ? 'selected' : ''; ?>>Commercial</option>
											<option value="Industrial" <?php echo (($_POST['customer_type'] ?? '') === 'Industrial') ? 'selected' : ''; ?>>Industrial</option>
											<option value="Open Access Developer" <?php echo (($_POST['customer_type'] ?? '') === 'Open Access Developer') ? 'selected' : ''; ?>>Open Access Developer
											</option>
											<option value="Other" <?php echo (($_POST['customer_type'] ?? '') === 'Other') ? 'selected' : ''; ?>>Other</option>
										</select>
									</div>
									<div class="col-md-6">
										<select class="form-select" name="system_size" required>
											<option value="" disabled <?php echo (($_POST['system_size'] ?? '') === '') ? 'selected' : ''; ?>>Approximate System Size Needed *</option>
											<option value="Below 20 kW" <?php echo (($_POST['system_size'] ?? '') === 'Below 20 kW') ? 'selected' : ''; ?>>Below 20 kW</option>
											<option value="20–200 kW" <?php echo (($_POST['system_size'] ?? '') === '20–200 kW') ? 'selected' : ''; ?>>20–200 kW</option>
											<option value="200–990 kW" <?php echo (($_POST['system_size'] ?? '') === '200–990 kW') ? 'selected' : ''; ?>>200–990 kW</option>
											<option value="1 MW and above" <?php echo (($_POST['system_size'] ?? '') === '1 MW and above') ? 'selected' : ''; ?>>1 MW and above</option>
											<option value="Not Sure" <?php echo (($_POST['system_size'] ?? '') === 'Not Sure') ? 'selected' : ''; ?>>Not Sure</option>
										</select>
									</div>
									<div class="col-md-12">
										<select class="form-select" name="source" required>
											<option value="" disabled <?php echo (($_POST['source'] ?? '') === '') ? 'selected' : ''; ?>>How did you hear about us? *</option>
											<option value="Google" <?php echo (($_POST['source'] ?? '') === 'Google') ? 'selected' : ''; ?>>Google</option>
											<option value="Referral" <?php echo (($_POST['source'] ?? '') === 'Referral') ? 'selected' : ''; ?>>Referral</option>
											<option value="Social Media" <?php echo (($_POST['source'] ?? '') === 'Social Media') ? 'selected' : ''; ?>>Social Media</option>
											<option value="Site Board" <?php echo (($_POST['source'] ?? '') === 'Site Board') ? 'selected' : ''; ?>>Site Board</option>
											<option value="Other" <?php echo (($_POST['source'] ?? '') === 'Other') ? 'selected' : ''; ?>>Other</option>
										</select>
									</div>
									<div class="col-md-12">
										<textarea name="message" cols="40" rows="6" class="form-control"
											placeholder="Message / Requirements (optional)"><?php echo htmlspecialchars((string) ($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
									</div>
									<div class="col-md-12 d-none">
										<input type="text" class="form-control" name="company" tabindex="-1"
											autocomplete="off">
									</div>
									<?php if ($sr_recaptcha_site_key !== '') { ?>
										<div class="col-md-12">
											<div class="sr-recaptcha">
												<div class="g-recaptcha"
													data-sitekey="<?php echo htmlspecialchars($sr_recaptcha_site_key, ENT_QUOTES, 'UTF-8'); ?>">
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="pbmit-button-wrapper">
									<button class="pbmit-btn submit" type="submit">
										<span class="pbmit-button-text">Request Free Consultation</span>
										<span class="form-btn-loader d-none">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 100">
												<circle fill="#fff" stroke="#fff" stroke-width="15" r="15" cx="40" cy="50">
													<animate attributeName="opacity" calcMode="spline" dur="2"
														values="1;0;1;" keySplines=".5 0 .5 1;.5 0 .5 1"
														repeatCount="indefinite" begin="-.4"></animate>
												</circle>
												<circle fill="#fff" stroke="#fff" stroke-width="15" r="15" cx="100" cy="50">
													<animate attributeName="opacity" calcMode="spline" dur="2"
														values="1;0;1;" keySplines=".5 0 .5 1;.5 0 .5 1"
														repeatCount="indefinite" begin="-.2"></animate>
												</circle>
												<circle fill="#fff" stroke="#fff" stroke-width="15" r="15" cx="160" cy="50">
													<animate attributeName="opacity" calcMode="spline" dur="2"
														values="1;0;1;" keySplines=".5 0 .5 1;.5 0 .5 1"
														repeatCount="indefinite" begin="0"></animate>
												</circle>
											</svg>
										</span>
									</button>
								</div>
								<div class="col-md-12 col-lg-12 message-status mt-3"></div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Contact Form End -->

		<!-- Client Start -->
		<section>
			<div class="container">
				<div class="client-area">
					<div class="row">
						<div class="col-md-9">
							<div class="swiper-slider" data-autoplay="true" data-loop="true" data-dots="false"
								data-arrows="false" data-columns="5" data-margin="30" data-effect="slide">
								<div class="swiper-wrapper">
									<?php foreach ($sr_client_logos as $idx => $l) { ?>
										<?php
										$img = trim((string) ($l['image'] ?? ''));
										$label = trim((string) ($l['label'] ?? ''));
										$url = trim((string) ($l['url'] ?? ''));
										?>
										<article class="pbmit-client-style-1 swiper-slide">
											<div class="pbmit-border-wrapper">
												<div class="pbmit-client-wrapper">
													<h4 class="pbmit-hide">
														<?php echo htmlspecialchars($label !== '' ? $label : ('Client-' . ((int) $idx + 1)), ENT_QUOTES, 'UTF-8'); ?>
													</h4>
													<div class="pbmit-featured-img-wrapper">
														<div class="pbmit-featured-wrapper">
															<?php if ($url !== '') { ?>
																<a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>"
																	target="_blank" rel="noopener">
																	<img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>"
																		class="img-fluid"
																		alt="<?php echo htmlspecialchars($label !== '' ? $label : 'Client logo', ENT_QUOTES, 'UTF-8'); ?>">
																</a>
															<?php } else { ?>
																<img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>"
																	class="img-fluid"
																	alt="<?php echo htmlspecialchars($label !== '' ? $label : 'Client logo', ENT_QUOTES, 'UTF-8'); ?>">
															<?php } ?>
														</div>
													</div>
												</div>
											</div>
										</article>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="client-custom-heading">
								<h2 class="pbmit-title">
									<?php echo htmlspecialchars($sr_contact_brands_title, ENT_QUOTES, 'UTF-8'); ?></h2>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Client End -->
		<section class="iframe-section">
			<div class="container-fluid p-0">
				<div class="container">
					<div class="sr-map-head">
						<div class="pbmit-custom-heading">
							<h2 class="pbmit-title">
								<?php echo htmlspecialchars($sr_contact_map_title, ENT_QUOTES, 'UTF-8'); ?></h2>
						</div>
						<a class="pbmit-btn outline sr-directions"
							href="<?php echo htmlspecialchars($sr_company_map_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank"
							rel="noopener"><span
								class="pbmit-button-text"><?php echo htmlspecialchars($sr_contact_directions_label, ENT_QUOTES, 'UTF-8'); ?></span></a>
					</div>
				</div>
				<div class="iframe-area">
					<iframe src="<?php echo htmlspecialchars($sr_contact_map_embed_url, ENT_QUOTES, 'UTF-8'); ?>"
						title="<?php echo htmlspecialchars($sr_contact_map_aria_label, ENT_QUOTES, 'UTF-8'); ?>"
						aria-label="<?php echo htmlspecialchars($sr_contact_map_aria_label, ENT_QUOTES, 'UTF-8'); ?>"></iframe>
				</div>
			</div>
		</section>
		<!-- Iframe Start -->

		<!-- Iframe End -->

		<!-- Contact Us Content End -->
	<?php } ?>
	<script
		src="<?php echo htmlspecialchars($sr_web_root . '/admin/assets/js/sweetalert/sweetalert2.min.js', ENT_QUOTES, 'UTF-8'); ?>"></script>
	<script>
		(function () {
			var form = document.getElementById('sr-contact-form');
			if (form) {
				form.addEventListener('submit', function () {
					var btn = form.querySelector('button.pbmit-btn.submit');
					if (!btn) {
						return;
					}
					btn.setAttribute('disabled', 'disabled');
					btn.setAttribute('aria-busy', 'true');
					var loader = btn.querySelector('.form-btn-loader');
					var text = btn.querySelector('.pbmit-button-text');
					if (loader) {
						loader.classList.remove('d-none');
					}
					if (text) {
						text.textContent = 'Submitting...';
					}
				}, { once: true });
			}

			var ok = <?php echo $sr_form_success ? 'true' : 'false'; ?>;
			var err = <?php echo json_encode((string) $sr_form_error, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
			var note = <?php echo json_encode((string) $sr_form_notice, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
			if (typeof Swal !== 'undefined') {
				if (ok) {
					Swal.fire({
						icon: 'success',
						title: 'Successful',
						text: note ? note : 'Thanks! Your request has been sent. We will contact you shortly.',
						confirmButtonText: 'OK'
					});
				} else if (err) {
					Swal.fire({
						icon: 'error',
						title: 'Please check',
						text: err,
						confirmButtonText: 'OK'
					});
				}
			}
		})();
	</script>
	<?php if ($sr_recaptcha_site_key !== '') { ?>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<?php } ?>
	<?php include 'includes/footer.php'; ?>
