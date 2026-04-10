<?php include 'includes/header.php'; ?>
</header>
<?php
$sr_page = sr_cms_page_get('privacy-policy');
$sr_legal_title = $sr_page && trim((string)$sr_page['hero_title']) !== '' ? (string)$sr_page['hero_title'] : 'Privacy Policy';
$sr_legal_lead = $sr_page && trim((string)$sr_page['hero_subtitle']) !== '' ? (string)$sr_page['hero_subtitle'] : 'This Privacy Policy explains how Shivanjali Renewables (“Shivanjali Renewables”, “we”, “us”, “our”) collects, uses, shares, and protects information when you visit our website, request a quote, or contact our team.';
$sr_legal_icon_svg = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l7 4v6c0 5-3.6 9.4-7 10-3.4-.6-7-5-7-10V6l7-4zm0 2.2L7 6.8V12c0 3.9 2.7 7.3 5 7.9 2.3-.6 5-4 5-7.9V6.8l-5-2.6zm2.9 5.6l1.2 1.2-4.6 4.6-2.5-2.5 1.2-1.2 1.3 1.3 3.4-3.4z"/></svg>';
$sr_banner_image = $sr_page && trim((string)($sr_page['banner_image'] ?? '')) !== '' ? (string)$sr_page['banner_image'] : '';
$sr_page_override = $sr_page && trim((string)($sr_page['content'] ?? '')) !== '' ? (string)$sr_page['content'] : '';

$pp_defaults = [
	'pp_updated_text' => 'Last updated: 03 April 2026',
	'pp_toc_title' => 'On this page',
	'pp_cta_label' => 'Request a Consultation',
	'pp_cta_url' => 'contact',
	'pp_scope_h' => 'Scope',
	'pp_scope_html' => '<p class="mb-0">This policy applies to information collected through our website and related communications (including phone, email, WhatsApp, and enquiry forms). It does not cover information collected offline outside of our business interactions.</p>',
	'pp_info_h' => 'Information We Collect',
	'pp_info_html' => '<p class="mb-2">We may collect the following categories of information:</p><ul class="sr-legal-list mb-0"><li><strong>Contact details</strong> such as name, phone number, email address, and city/location.</li><li><strong>Project details</strong> such as customer type (residential/commercial/industrial), rooftop/land information, power requirements, and any notes you provide.</li><li><strong>Communication records</strong> when you contact us by phone, email, or messaging apps.</li><li><strong>Technical data</strong> such as device/browser type, IP address, and pages visited (collected via cookies or similar technologies where applicable).</li></ul>',
	'pp_use_h' => 'How We Use Information',
	'pp_use_html' => '<ul class="sr-legal-list mb-0"><li>Respond to enquiries and provide quotations, proposals, and consultation scheduling.</li><li>Perform site visit planning and feasibility evaluation where required.</li><li>Improve website performance, user experience, and service quality.</li><li>Send service-related updates (for example, follow-up calls or emails about your request).</li><li>Comply with legal obligations and prevent misuse or fraud.</li></ul>',
	'pp_cookies_h' => 'Cookies &amp; Analytics',
	'pp_cookies_html' => '<p class="mb-2">Cookies are small files stored on your device to help websites function and remember preferences.</p><ul class="sr-legal-list mb-0"><li><strong>Essential cookies</strong> may be used to enable core website functionality.</li><li><strong>Analytics</strong> may be used to understand traffic and improve performance. Analytics scripts (if enabled) are loaded based on your cookie consent preference.</li><li>You can change your preference anytime by clearing your browser storage for this site and revisiting the website.</li></ul>',
	'pp_sharing_h' => 'Sharing &amp; Disclosure',
	'pp_sharing_html' => '<p class="mb-2">We do not sell your personal information. We may share information only in the following cases:</p><ul class="sr-legal-list mb-0"><li><strong>Service providers</strong> who support website operations (for example, hosting) and are bound by confidentiality obligations.</li><li><strong>Business operations</strong> such as arranging site surveys, installations, or maintenance with our internal team and authorized partners.</li><li><strong>Legal reasons</strong> where disclosure is required by law, regulation, or to protect rights and safety.</li></ul>',
	'pp_retention_h' => 'Data Retention',
	'pp_retention_html' => '<p class="mb-0">We retain information only as long as necessary for the purposes described in this policy (for example, to respond to your enquiry, maintain business records, or comply with applicable legal requirements). Retention periods may vary depending on the nature of the interaction.</p>',
	'pp_security_h' => 'Security',
	'pp_security_html' => '<p class="mb-0">We implement reasonable administrative and technical measures to protect information. However, no method of transmission or storage is fully secure, and we cannot guarantee absolute security.</p>',
	'pp_rights_h' => 'Your Rights',
	'pp_rights_html' => '<p class="mb-2">Depending on applicable law, you may have rights to access, correct, or delete your personal information.</p><ul class="sr-legal-list mb-0"><li>To request an update or deletion, email us with sufficient details to identify your enquiry.</li><li>We may need to retain certain records for legal compliance or legitimate business purposes.</li></ul>',
	'pp_links_h' => 'Third-Party Links',
	'pp_links_html' => '<p class="mb-0">Our website may contain links to third-party websites. We are not responsible for their privacy practices. Please review the privacy policies of any third-party sites you visit.</p>',
	'pp_changes_h' => 'Changes to This Policy',
	'pp_changes_html' => '<p class="mb-0">We may update this Privacy Policy from time to time. The “Last updated” date at the top indicates when changes were last made.</p>',
	'pp_contact_h' => 'Contact',
	'pp_contact_html' => '<p class="mb-2">For questions or requests about this Privacy Policy, contact:</p><ul class="sr-legal-contact-list mb-0"><li><strong>Shivanjali Renewables</strong></li><li>Office No. 505, ABH Samruddhi, Near Dream Castle Signal, Makhamalabad Road, Nashik – 422003, Maharashtra, India</li><li><a href="mailto:info@shivanjalirenewables.com">info@shivanjalirenewables.com</a></li><li><a href="tel:+918686313133">+91 8686 313 133</a></li></ul>',
	'pp_highlight1_html' => '<strong>We collect</strong> contact details and project requirements you share with us.',
	'pp_highlight2_html' => '<strong>We use it</strong> to respond, provide proposals, and improve our services.',
	'pp_highlight3_html' => '<strong>We don’t sell</strong> your personal information to third parties.',
	'pp_highlight4_html' => '<strong>You control</strong> cookies/analytics preferences via the consent banner.',
];

$pp_updated = sr_cms_setting_get('pp_updated_text', $pp_defaults['pp_updated_text']);
$pp_toc_title = sr_cms_setting_get('pp_toc_title', $pp_defaults['pp_toc_title']);
$pp_cta_label = sr_cms_setting_get('pp_cta_label', $pp_defaults['pp_cta_label']);
$pp_cta_url = sr_cms_setting_get('pp_cta_url', $pp_defaults['pp_cta_url']);
$pp_high1 = sr_cms_setting_get('pp_highlight1_html', $pp_defaults['pp_highlight1_html']);
$pp_high2 = sr_cms_setting_get('pp_highlight2_html', $pp_defaults['pp_highlight2_html']);
$pp_high3 = sr_cms_setting_get('pp_highlight3_html', $pp_defaults['pp_highlight3_html']);
$pp_high4 = sr_cms_setting_get('pp_highlight4_html', $pp_defaults['pp_highlight4_html']);

$pp_h1 = sr_cms_setting_get('pp_scope_h', $pp_defaults['pp_scope_h']);
$pp_h2 = sr_cms_setting_get('pp_info_h', $pp_defaults['pp_info_h']);
$pp_h3 = sr_cms_setting_get('pp_use_h', $pp_defaults['pp_use_h']);
$pp_h4 = sr_cms_setting_get('pp_cookies_h', $pp_defaults['pp_cookies_h']);
$pp_h5 = sr_cms_setting_get('pp_sharing_h', $pp_defaults['pp_sharing_h']);
$pp_h6 = sr_cms_setting_get('pp_retention_h', $pp_defaults['pp_retention_h']);
$pp_h7 = sr_cms_setting_get('pp_security_h', $pp_defaults['pp_security_h']);
$pp_h8 = sr_cms_setting_get('pp_rights_h', $pp_defaults['pp_rights_h']);
$pp_h9 = sr_cms_setting_get('pp_links_h', $pp_defaults['pp_links_h']);
$pp_h10 = sr_cms_setting_get('pp_changes_h', $pp_defaults['pp_changes_h']);
$pp_h11 = sr_cms_setting_get('pp_contact_h', $pp_defaults['pp_contact_h']);

$pp_s1 = sr_cms_setting_get('pp_scope_html', $pp_defaults['pp_scope_html']);
$pp_s2 = sr_cms_setting_get('pp_info_html', $pp_defaults['pp_info_html']);
$pp_s3 = sr_cms_setting_get('pp_use_html', $pp_defaults['pp_use_html']);
$pp_s4 = sr_cms_setting_get('pp_cookies_html', $pp_defaults['pp_cookies_html']);
$pp_s5 = sr_cms_setting_get('pp_sharing_html', $pp_defaults['pp_sharing_html']);
$pp_s6 = sr_cms_setting_get('pp_retention_html', $pp_defaults['pp_retention_html']);
$pp_s7 = sr_cms_setting_get('pp_security_html', $pp_defaults['pp_security_html']);
$pp_s8 = sr_cms_setting_get('pp_rights_html', $pp_defaults['pp_rights_html']);
$pp_s9 = sr_cms_setting_get('pp_links_html', $pp_defaults['pp_links_html']);
$pp_s10 = sr_cms_setting_get('pp_changes_html', $pp_defaults['pp_changes_html']);
$pp_s11 = sr_cms_setting_get('pp_contact_html', $pp_defaults['pp_contact_html']);
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
						<span><span class="post-root post post-post current-item"> Privacy Policy</span></span>
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
							<span class="sr-legal-updated"><?php echo htmlspecialchars($pp_updated, ENT_QUOTES, 'UTF-8'); ?></span>
						</div>
						<h2 class="pbmit-title mb-2"><?php echo htmlspecialchars($sr_legal_title, ENT_QUOTES, 'UTF-8'); ?></h2>
						<p class="sr-legal-lead mb-0"><?php echo $sr_legal_lead; ?></p>
						<div class="sr-legal-highlights">
							<div class="sr-legal-highlight"><?php echo $pp_high1; ?></div>
							<div class="sr-legal-highlight"><?php echo $pp_high2; ?></div>
							<div class="sr-legal-highlight"><?php echo $pp_high3; ?></div>
							<div class="sr-legal-highlight"><?php echo $pp_high4; ?></div>
						</div>
					</div>

					<div class="row g-4 mt-2">
						<div class="col-lg-4">
							<aside class="sr-legal-toc">
								<div class="sr-legal-toc-title"><?php echo htmlspecialchars($pp_toc_title, ENT_QUOTES, 'UTF-8'); ?></div>
								<ul class="sr-legal-toc-list">
									<li><a href="#pp-scope"><?php echo htmlspecialchars($pp_h1, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#pp-info"><?php echo htmlspecialchars($pp_h2, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#pp-use"><?php echo htmlspecialchars($pp_h3, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#pp-cookies"><?php echo htmlspecialchars($pp_h4, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#pp-sharing"><?php echo htmlspecialchars($pp_h5, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#pp-retention"><?php echo htmlspecialchars($pp_h6, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#pp-security"><?php echo htmlspecialchars($pp_h7, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#pp-rights"><?php echo htmlspecialchars($pp_h8, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#pp-links"><?php echo htmlspecialchars($pp_h9, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#pp-changes"><?php echo htmlspecialchars($pp_h10, ENT_QUOTES, 'UTF-8'); ?></a></li>
									<li><a href="#pp-contact"><?php echo htmlspecialchars($pp_h11, ENT_QUOTES, 'UTF-8'); ?></a></li>
								</ul>
								<div class="sr-legal-toc-cta">
									<a href="<?php echo htmlspecialchars($pp_cta_url, ENT_QUOTES, 'UTF-8'); ?>" class="pbmit-btn"><span class="pbmit-button-text"><?php echo htmlspecialchars($pp_cta_label, ENT_QUOTES, 'UTF-8'); ?></span></a>
								</div>
							</aside>
						</div>

						<div class="col-lg-8">
							<div class="sr-legal-card" id="pp-scope">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>1) <?php echo htmlspecialchars($pp_h1, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $pp_s1; ?>
							</div>

							<div class="sr-legal-card" id="pp-info">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>2) <?php echo htmlspecialchars($pp_h2, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $pp_s2; ?>
							</div>

							<div class="sr-legal-card" id="pp-use">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>3) <?php echo htmlspecialchars($pp_h3, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $pp_s3; ?>
							</div>

							<div class="sr-legal-card" id="pp-cookies">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>4) <?php echo htmlspecialchars($pp_h4, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $pp_s4; ?>
							</div>

							<div class="sr-legal-card" id="pp-sharing">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>5) <?php echo htmlspecialchars($pp_h5, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $pp_s5; ?>
							</div>

							<div class="sr-legal-card" id="pp-retention">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>6) <?php echo htmlspecialchars($pp_h6, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $pp_s6; ?>
							</div>

							<div class="sr-legal-card" id="pp-security">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>7) <?php echo htmlspecialchars($pp_h7, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $pp_s7; ?>
							</div>

							<div class="sr-legal-card" id="pp-rights">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>8) <?php echo htmlspecialchars($pp_h8, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $pp_s8; ?>
							</div>

							<div class="sr-legal-card" id="pp-links">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>9) <?php echo htmlspecialchars($pp_h9, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $pp_s9; ?>
							</div>

							<div class="sr-legal-card" id="pp-changes">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>10) <?php echo htmlspecialchars($pp_h10, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $pp_s10; ?>
							</div>

							<div class="sr-legal-card sr-legal-contact" id="pp-contact">
								<h3 class="sr-legal-h"><span class="sr-legal-h-icon" aria-hidden="true"><?php echo $sr_legal_icon_svg; ?></span>11) <?php echo htmlspecialchars($pp_h11, ENT_QUOTES, 'UTF-8'); ?></h3>
								<?php echo $pp_s11; ?>
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
