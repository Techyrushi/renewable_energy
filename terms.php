<?php include 'includes/header.php'; ?>
</header>
<?php
$sr_page = sr_cms_page_get('terms-of-use');
$sr_legal_title = $sr_page && trim((string)$sr_page['hero_title']) !== '' ? (string)$sr_page['hero_title'] : 'Terms of Use';
$sr_legal_lead = $sr_page && trim((string)$sr_page['hero_subtitle']) !== '' ? (string)$sr_page['hero_subtitle'] : 'These Terms of Use govern access to and use of the Shivanjali Renewables website. By using this website, you agree to these terms.';
$sr_legal_icon_svg = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg"><path d="M6 2h9l3 3v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm8 1.5V6h2.5L14 3.5zM7 9h10v1.8H7V9zm0 4h10v1.8H7V13zm0 4h7v1.8H7V17z"/></svg>';
$sr_banner_image = $sr_page && trim((string)($sr_page['banner_image'] ?? '')) !== '' ? (string)$sr_page['banner_image'] : '';
$sr_page_override = $sr_page && trim((string)($sr_page['content'] ?? '')) !== '' ? (string)$sr_page['content'] : '';

$tou_defaults = [
	'tou_updated_text' => 'Last updated: 03 April 2026',
	'tou_toc_title' => 'On this page',
	'tou_cta_label' => 'Read Privacy Policy',
	'tou_cta_url' => 'privacy-policy',
	'tou_acceptance_h' => 'Acceptance',
	'tou_acceptance_html' => '<p class="mb-0">By accessing or using this website, you agree to be bound by these Terms of Use and our <a href="privacy-policy">Privacy Policy</a>. If you do not agree, please do not use the website.</p>',
	'tou_eligibility_h' => 'Eligibility',
	'tou_eligibility_html' => '<p class="mb-0">You must be able to form a legally binding contract under applicable law to use this website and submit enquiries.</p>',
	'tou_services_h' => 'Services &amp; Enquiries',
	'tou_services_html' => '<ul class="sr-legal-list mb-0"><li>The website provides information about our solar and renewable energy solutions and a way to request a consultation or quote.</li><li>You agree to provide accurate and complete information when submitting forms or contacting us.</li><li>We may contact you by phone, email, or messaging apps to respond to your enquiry.</li></ul>',
	'tou_quotes_h' => 'Quotes &amp; Proposals',
	'tou_quotes_html' => '<ul class="sr-legal-list mb-0"><li>Any estimates, savings calculations, or timelines shown on the website are indicative and may change based on site conditions, scope, permits, approvals, and equipment availability.</li><li>A final quotation or proposal may require a site visit, technical survey, and confirmation of requirements.</li><li>Unless explicitly stated in writing, a website enquiry does not create a binding contract.</li></ul>',
	'tou_ip_h' => 'Intellectual Property',
	'tou_ip_html' => '<p class="mb-2">All content on this website (including text, graphics, logos, images, and layout) is owned by Shivanjali Renewables or licensed to us and is protected by applicable intellectual property laws.</p><ul class="sr-legal-list mb-0"><li>You may view and print pages for personal, non-commercial use.</li><li>You may not copy, reproduce, distribute, or create derivative works without prior written permission.</li></ul>',
	'tou_acceptable_h' => 'Acceptable Use',
	'tou_acceptable_html' => '<ul class="sr-legal-list mb-0"><li>Do not attempt to disrupt or compromise the website’s security or availability.</li><li>Do not submit false, misleading, or unlawful information through forms.</li><li>Do not use the website to transmit malware, spam, or unauthorized promotional content.</li><li>Do not scrape, harvest, or collect data from the website without permission.</li></ul>',
	'tou_links_h' => 'Third-Party Links',
	'tou_links_html' => '<p class="mb-0">The website may include links to third-party services (such as maps or social platforms). We do not control these sites and are not responsible for their content, policies, or practices.</p>',
	'tou_disclaimer_h' => 'Disclaimers',
	'tou_disclaimer_html' => '<ul class="sr-legal-list mb-0"><li>The website and its content are provided on an “as is” and “as available” basis without warranties of any kind, to the maximum extent permitted by law.</li><li>We do not warrant that the website will be uninterrupted, error-free, or free from harmful components.</li><li>Information on the website is for general guidance and does not constitute professional, legal, or financial advice.</li></ul>',
	'tou_liability_h' => 'Limitation of Liability',
	'tou_liability_html' => '<p class="mb-0">To the maximum extent permitted by law, Shivanjali Renewables shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or related to your use of the website.</p>',
	'tou_indemnity_h' => 'Indemnity',
	'tou_indemnity_html' => '<p class="mb-0">You agree to indemnify and hold harmless Shivanjali Renewables from any claims, losses, liabilities, and expenses arising from your use of the website or violation of these Terms of Use.</p>',
	'tou_law_h' => 'Governing Law',
	'tou_law_html' => '<p class="mb-0">These Terms of Use are governed by the laws of India. Courts in Nashik, Maharashtra shall have jurisdiction, subject to applicable law.</p>',
	'tou_changes_h' => 'Changes to These Terms',
	'tou_changes_html' => '<p class="mb-0">We may update these Terms of Use from time to time. Continued use of the website after updates means you accept the revised terms.</p>',
	'tou_contact_h' => 'Contact',
	'tou_contact_html' => '<p class="mb-2">If you have questions about these Terms of Use, contact:</p><ul class="sr-legal-contact-list mb-0"><li><strong>Shivanjali Renewables</strong></li><li>Office No. 505, ABH Samruddhi, Near Dream Castle Signal, Makhamalabad Road, Nashik – 422003, Maharashtra, India</li><li><a href="mailto:info@shivanjalirenewables.com">info@shivanjalirenewables.com</a></li><li><a href="tel:+918686313133">+91 8686 313 133</a></li></ul>',
	'tou_highlight1_html' => '<strong>Use responsibly:</strong> don’t misuse the website, content, or forms.',
	'tou_highlight2_html' => '<strong>Quotes vary:</strong> pricing and timelines depend on site conditions and scope.',
	'tou_highlight3_html' => '<strong>Content protection:</strong> branding and materials are protected by IP laws.',
	'tou_highlight4_html' => '<strong>Need help:</strong> reach us via phone/email for clarifications.',
];

$tou_updated = sr_cms_setting_get('tou_updated_text', $tou_defaults['tou_updated_text']);
$tou_toc_title = sr_cms_setting_get('tou_toc_title', $tou_defaults['tou_toc_title']);
$tou_cta_label = sr_cms_setting_get('tou_cta_label', $tou_defaults['tou_cta_label']);
$tou_cta_url = sr_cms_setting_get('tou_cta_url', $tou_defaults['tou_cta_url']);
$tou_high1 = sr_cms_setting_get('tou_highlight1_html', $tou_defaults['tou_highlight1_html']);
$tou_high2 = sr_cms_setting_get('tou_highlight2_html', $tou_defaults['tou_highlight2_html']);
$tou_high3 = sr_cms_setting_get('tou_highlight3_html', $tou_defaults['tou_highlight3_html']);
$tou_high4 = sr_cms_setting_get('tou_highlight4_html', $tou_defaults['tou_highlight4_html']);

$tou_h1 = sr_cms_setting_get('tou_acceptance_h', $tou_defaults['tou_acceptance_h']);
$tou_h2 = sr_cms_setting_get('tou_eligibility_h', $tou_defaults['tou_eligibility_h']);
$tou_h3 = sr_cms_setting_get('tou_services_h', $tou_defaults['tou_services_h']);
$tou_h4 = sr_cms_setting_get('tou_quotes_h', $tou_defaults['tou_quotes_h']);
$tou_h5 = sr_cms_setting_get('tou_ip_h', $tou_defaults['tou_ip_h']);
$tou_h6 = sr_cms_setting_get('tou_acceptable_h', $tou_defaults['tou_acceptable_h']);
$tou_h7 = sr_cms_setting_get('tou_links_h', $tou_defaults['tou_links_h']);
$tou_h8 = sr_cms_setting_get('tou_disclaimer_h', $tou_defaults['tou_disclaimer_h']);
$tou_h9 = sr_cms_setting_get('tou_liability_h', $tou_defaults['tou_liability_h']);
$tou_h10 = sr_cms_setting_get('tou_indemnity_h', $tou_defaults['tou_indemnity_h']);
$tou_h11 = sr_cms_setting_get('tou_law_h', $tou_defaults['tou_law_h']);
$tou_h12 = sr_cms_setting_get('tou_changes_h', $tou_defaults['tou_changes_h']);
$tou_h13 = sr_cms_setting_get('tou_contact_h', $tou_defaults['tou_contact_h']);

$tou_s1 = sr_cms_setting_get('tou_acceptance_html', $tou_defaults['tou_acceptance_html']);
$tou_s2 = sr_cms_setting_get('tou_eligibility_html', $tou_defaults['tou_eligibility_html']);
$tou_s3 = sr_cms_setting_get('tou_services_html', $tou_defaults['tou_services_html']);
$tou_s4 = sr_cms_setting_get('tou_quotes_html', $tou_defaults['tou_quotes_html']);
$tou_s5 = sr_cms_setting_get('tou_ip_html', $tou_defaults['tou_ip_html']);
$tou_s6 = sr_cms_setting_get('tou_acceptable_html', $tou_defaults['tou_acceptable_html']);
$tou_s7 = sr_cms_setting_get('tou_links_html', $tou_defaults['tou_links_html']);
$tou_s8 = sr_cms_setting_get('tou_disclaimer_html', $tou_defaults['tou_disclaimer_html']);
$tou_s9 = sr_cms_setting_get('tou_liability_html', $tou_defaults['tou_liability_html']);
$tou_s10 = sr_cms_setting_get('tou_indemnity_html', $tou_defaults['tou_indemnity_html']);
$tou_s11 = sr_cms_setting_get('tou_law_html', $tou_defaults['tou_law_html']);
$tou_s12 = sr_cms_setting_get('tou_changes_html', $tou_defaults['tou_changes_html']);
$tou_s13 = sr_cms_setting_get('tou_contact_html', $tou_defaults['tou_contact_html']);
?>
<div class="pbmit-title-bar-wrapper"<?php echo $sr_banner_image !== '' ? (' style="background-image:url(' . htmlspecialchars($sr_banner_image, ENT_QUOTES, 'UTF-8') . ');"') : ''; ?>>
	<div class="container">
		<div class="pbmit-title-bar-content">
			<div class="pbmit-title-bar-content-inner">
				<div class="pbmit-tbar">
					<div class="pbmit-tbar-inner container">
						<h1 class="pbmit-tbar-title"><?php echo htmlspecialchars($sr_legal_title, ENT_QUOTES, 'UTF-8'); ?></h1>
					</div>
				</div>
				<div class="pbmit-breadcrumb">
					<div class="pbmit-breadcrumb-inner">
						<span>
							<a title="" href="./" class="home"><span>Home</span></a>
						</span>
						<i class="pbmit-base-icon-arrow-right-2"></i>
						<span><span class="post-root post post-post current-item"> Terms of Use</span></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="page-content">
	<?php if ($sr_page_override !== '') { ?>
		<?php echo $sr_page_override; ?>
	<?php } else { ?>
	<section class="section-xl sr-legal-page">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-11">
					<div class="sr-legal-intro">
						<div class="sr-legal-intro-top">
							<span class="sr-legal-badge">Legal</span>
							<span class="sr-legal-updated"><?php echo htmlspecialchars($tou_updated, ENT_QUOTES, 'UTF-8'); ?></span>
						</div>
						<h2 class="pbmit-title mb-2"><?php echo htmlspecialchars($sr_legal_title, ENT_QUOTES, 'UTF-8'); ?></h2>
						<p class="sr-legal-lead mb-0"><?php echo $sr_legal_lead; ?></p>
						<div class="sr-legal-highlights">
							<div class="sr-legal-highlight"><?php echo $tou_high1; ?></div>
							<div class="sr-legal-highlight"><?php echo $tou_high2; ?></div>
							<div class="sr-legal-highlight"><?php echo $tou_high3; ?></div>
							<div class="sr-legal-highlight"><?php echo $tou_high4; ?></div>
						</div>
					</div>

					<div class="row g-4 mt-2">
						<div class="col-lg-4">
							<aside class="sr-legal-toc">
								<div class="sr-legal-toc-title"><?php echo htmlspecialchars($tou_toc_title, ENT_QUOTES, 'UTF-8'); ?></div>
								<ul class="sr-legal-toc-list">
									<li><a href="#tou-acceptance"><?php echo htmlspecialchars($tou_h1, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#tou-eligibility"><?php echo htmlspecialchars($tou_h2, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#tou-services"><?php echo htmlspecialchars($tou_h3, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#tou-quotes"><?php echo htmlspecialchars($tou_h4, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#tou-ip"><?php echo htmlspecialchars($tou_h5, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#tou-acceptable"><?php echo htmlspecialchars($tou_h6, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#tou-links"><?php echo htmlspecialchars($tou_h7, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#tou-disclaimer"><?php echo htmlspecialchars($tou_h8, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#tou-liability"><?php echo htmlspecialchars($tou_h9, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#tou-indemnity"><?php echo htmlspecialchars($tou_h10, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#tou-law"><?php echo htmlspecialchars($tou_h11, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#tou-changes"><?php echo htmlspecialchars($tou_h12, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#tou-contact"><?php echo htmlspecialchars($tou_h13, ENT_QUOTES, 'UTF-8'); ?></a></li>
								</ul>
								<div class="sr-legal-toc-cta">
									<a href="<?php echo htmlspecialchars($tou_cta_url, ENT_QUOTES, 'UTF-8'); ?>" class="pbmit-btn outline"><span class="pbmit-button-text"><?php echo htmlspecialchars($tou_cta_label, ENT_QUOTES, 'UTF-8'); ?></span></a>
								</div>
							</aside>
						</div>

						<div class="col-lg-8">
							<div class="sr-legal-card" id="tou-acceptance">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>1) <?php echo htmlspecialchars($tou_h1, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $tou_s1; ?>
							</div>

							<div class="sr-legal-card" id="tou-eligibility">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>2) <?php echo htmlspecialchars($tou_h2, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $tou_s2; ?>
							</div>

							<div class="sr-legal-card" id="tou-services">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>3) <?php echo htmlspecialchars($tou_h3, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $tou_s3; ?>
							</div>

							<div class="sr-legal-card" id="tou-quotes">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>4) <?php echo htmlspecialchars($tou_h4, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $tou_s4; ?>
							</div>

							<div class="sr-legal-card" id="tou-ip">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>5) <?php echo htmlspecialchars($tou_h5, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $tou_s5; ?>
							</div>

							<div class="sr-legal-card" id="tou-acceptable">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>6) <?php echo htmlspecialchars($tou_h6, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $tou_s6; ?>
							</div>

							<div class="sr-legal-card" id="tou-links">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>7) <?php echo htmlspecialchars($tou_h7, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $tou_s7; ?>
							</div>

							<div class="sr-legal-card" id="tou-disclaimer">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>8) <?php echo htmlspecialchars($tou_h8, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $tou_s8; ?>
							</div>

							<div class="sr-legal-card" id="tou-liability">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>9) <?php echo htmlspecialchars($tou_h9, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $tou_s9; ?>
							</div>

							<div class="sr-legal-card" id="tou-indemnity">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>10) <?php echo htmlspecialchars($tou_h10, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $tou_s10; ?>
							</div>

							<div class="sr-legal-card" id="tou-law">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>11) <?php echo htmlspecialchars($tou_h11, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $tou_s11; ?>
							</div>

							<div class="sr-legal-card" id="tou-changes">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>12) <?php echo htmlspecialchars($tou_h12, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $tou_s12; ?>
							</div>

							<div class="sr-legal-card sr-legal-contact" id="tou-contact">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>13) <?php echo htmlspecialchars($tou_h13, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $tou_s13; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php } ?>
</div>
<script>
	(function () {
		function onReady(fn) {
			if (document.readyState !== 'loading') {
				fn();
				return;
			}
			document.addEventListener('DOMContentLoaded', fn);
		}

		onReady(function () {
			var toc = document.querySelector('.sr-legal-toc');
			if (!toc) return;

			var links = Array.prototype.slice.call(toc.querySelectorAll('a[href^="#"]'));
			if (!links.length) return;

			function getTargetFromHref(href) {
				if (!href || href.charAt(0) !== '#') return null;
				var id = href.slice(1);
				return document.getElementById(id);
			}

			function setActive(id) {
				links.forEach(function (a) {
					a.classList.toggle('is-active', a.getAttribute('href') === '#' + id);
				});
				var cards = document.querySelectorAll('.sr-legal-card');
				cards.forEach(function (card) {
					card.classList.toggle('is-active', card.id === id);
				});
			}

			links.forEach(function (a) {
				a.addEventListener('click', function (e) {
					var href = a.getAttribute('href') || '';
					var target = getTargetFromHref(href);
					if (!target) return;
					e.preventDefault();
					target.scrollIntoView({ behavior: 'smooth', block: 'start' });
					try {
						history.pushState(null, '', href);
					} catch (err) {
					}
					setActive(target.id);
				});
			});

			var sections = links.map(function (a) {
				return getTargetFromHref(a.getAttribute('href'));
			}).filter(Boolean);

			if ('IntersectionObserver' in window && sections.length) {
				var io = new IntersectionObserver(function (entries) {
					var best = null;
					entries.forEach(function (en) {
						if (!en.isIntersecting) return;
						if (!best || en.intersectionRatio > best.intersectionRatio) best = en;
					});
					if (best && best.target && best.target.id) setActive(best.target.id);
				}, { rootMargin: '-22% 0px -68% 0px', threshold: [0.06, 0.18, 0.35, 0.6] });

				sections.forEach(function (s) { io.observe(s); });
			}

			if (location.hash) {
				var initialTarget = getTargetFromHref(location.hash);
				if (initialTarget) setActive(initialTarget.id);
			} else if (sections[0]) {
				setActive(sections[0].id);
			}
		});
	})();
</script>
<?php include 'includes/footer.php'; ?>
