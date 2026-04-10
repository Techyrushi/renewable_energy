<?php include 'includes/header.php'; ?>
<?php
$sr_page = sr_cms_page_get('services');
$sr_services_title = $sr_page && trim((string)$sr_page['hero_title']) !== '' ? (string)$sr_page['hero_title'] : 'Services';
$sr_services_subtitle = $sr_page && trim((string)($sr_page['hero_subtitle'] ?? '')) !== '' ? (string)$sr_page['hero_subtitle'] : '';
$sr_banner_image = $sr_page && trim((string)($sr_page['banner_image'] ?? '')) !== '' ? (string)$sr_page['banner_image'] : '';
$sr_services_override = $sr_page && trim((string)($sr_page['content'] ?? '')) !== '' ? (string)$sr_page['content'] : '';
$sr_services_intro_title = sr_cms_setting_get('services_intro_title', 'End-to-End Solar Services. Zero Compromise.');
$sr_services_intro_desc = sr_cms_setting_get('services_intro_desc', 'From feasibility study and design to installation, grid connection, and lifetime maintenance — Shivanjali Renewables handles it all.');
$sr_services_items = [];
$sr_db = sr_cms_db_try();
if ($sr_db instanceof mysqli) {
	$res = $sr_db->query("SELECT slug, title, short_desc, image, icon_svg
		FROM cms_services
		WHERE published = 1
		ORDER BY sort_order ASC, updated_at DESC
		LIMIT 50");
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$sr_services_items[] = $row;
		}
		$res->free();
	}
}

$sr_default_icon_sig = 'M13 2L3 14h7l-1 8 12-14h-7l-1-6z';
$sr_icon_palette = [
	'<svg viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M4 4h16v7H4V4zm0 9h16v7H4v-7zm3-7v3h3V6H7zm0 9v3h3v-3H7zm8-9v3h3V6h-3zm0 9v3h3v-3h-3z"/></svg>',
	'<svg viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l4 7h-8l4-7zm7 9v9a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-9h14zm-9 2H8v2h2v-2zm6 0h-2v2h2v-2z"/></svg>',
	'<svg viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M12 3a6 6 0 0 1 6 6c0 2.4-1.4 4.6-3.6 5.6L14 21h-4l-.4-6.4A6.01 6.01 0 0 1 6 9a6 6 0 0 1 6-6zm-2 16h4l.2-3H9.8l.2 3z"/></svg>',
	'<svg viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M6 3h12v2H6V3zm-2 4h16v6a6 6 0 0 1-6 6h-4a6 6 0 0 1-6-6V7zm6 3v2h4v-2h-4zm0 4v2h4v-2h-4z"/></svg>',
];
?>
</header>
<div class="pbmit-title-bar-wrapper sr-projects-hero"<?php echo $sr_banner_image !== '' ? (' style="background-image:url(' . htmlspecialchars($sr_banner_image, ENT_QUOTES, 'UTF-8') . ');"') : ''; ?>>
	<div class="container">
		<div class="pbmit-title-bar-content">
			<div class="pbmit-title-bar-content-inner">
				<div class="pbmit-tbar">
					<div class="pbmit-tbar-inner container">
						<h1 class="pbmit-tbar-title"><?php echo htmlspecialchars($sr_services_title, ENT_QUOTES, 'UTF-8'); ?></h1>
						<?php if ($sr_services_subtitle !== '') { ?>
							<p class="pbmit-tbar-subtitle mb-0"><?php echo htmlspecialchars($sr_services_subtitle, ENT_QUOTES, 'UTF-8'); ?></p>
						<?php } ?>
					</div>
				</div>
				<div class="pbmit-breadcrumb">
					<div class="pbmit-breadcrumb-inner">
						<span>
							<a title="" href="./" class="home"><span>Home</span></a>
						</span>
						<i class="pbmit-base-icon-arrow-right-2"></i>
						<span><span class="post-root post post-post current-item"> Services</span></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="page-content services">
	<?php if ($sr_services_override !== '') { ?>
		<?php echo $sr_services_override; ?>
	<?php } else { ?>
		<section class="section-xl pbmit-bg-color-white services-cards-modern" data-aos="fade-up" data-aos-duration="800">
			<div class="container">
				<div class="pbmit-heading-subheading text-center">
					<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_services_intro_title, ENT_QUOTES, 'UTF-8'); ?></h2>
					<p class="mb-0"><?php echo htmlspecialchars($sr_services_intro_desc, ENT_QUOTES, 'UTF-8'); ?></p>
				</div>
				<div class="row g-4 pt-3">
					<?php if ($sr_services_items) { ?>
						<?php foreach ($sr_services_items as $idx => $s) { ?>
							<?php
							$slug = trim((string)($s['slug'] ?? ''));
							if ($slug === '') continue;
							$href = 'services/' . rawurlencode($slug);
							$title = (string)($s['title'] ?? '');
							$desc = (string)($s['short_desc'] ?? '');
							$image = trim((string)($s['image'] ?? ''));
							if ($image === '') $image = 'images/homepage-2/service/service-img-01.jpg';
							$iconSvg = trim((string)($s['icon_svg'] ?? ''));
							$useAutoIcon = $iconSvg === '' || strpos($iconSvg, $sr_default_icon_sig) !== false;
							if ($useAutoIcon) {
								$h = abs((int) crc32($slug));
								$iconSvg = $sr_icon_palette[$h % count($sr_icon_palette)];
							}
							$cardClass = $idx % 4 === 0 ? 'svc-card--install' : ($idx % 4 === 1 ? 'svc-card--om' : ($idx % 4 === 2 ? 'svc-card--consult' : 'svc-card--ppa'));
							?>
							<div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="<?php echo (int)min(360, $idx * 120); ?>">
								<div class="pbmit-service-style-4 card-elevated svc-card <?php echo $cardClass; ?>">
									<div class="pbminfotech-post-item">
										<div class="pbmit-box-content-wrap">
											<div class="pbmit-content-box">
												<div class="pbminfotech-box-number"><?php echo str_pad((string)($idx + 1), 2, '0', STR_PAD_LEFT); ?></div>
												<div class="pbmit-service-icon" aria-hidden="true">
													<?php echo $iconSvg; ?>
												</div>
												<h3 class="pbmit-service-title"><a href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></a></h3>
												<p class="home-service-desc"><?php echo htmlspecialchars($desc, ENT_QUOTES, 'UTF-8'); ?></p>
												<div class="svc-card-cta">
													<a href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>" class="pbmit-btn outline svc-readmore"><span class="pbmit-button-text">Read More</span></a>
												</div>
											</div>
											<div class="pbmit-service-image-wrapper">
												<div class="pbmit-featured-img-wrapper">
													<div class="pbmit-featured-wrapper">
														<img src="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					<?php } else { ?>
					<div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="0">
						<div class="pbmit-service-style-4 card-elevated svc-card svc-card--install">
							<div class="pbminfotech-post-item">
								<div class="pbmit-box-content-wrap">
									<div class="pbmit-content-box">
										<div class="pbminfotech-box-number">01</div>
										<div class="pbmit-service-icon" aria-hidden="true">
											<svg viewBox="0 0 64 64" role="presentation">
												<rect x="6" y="10" width="52" height="30" rx="4" fill="none"
													stroke="currentColor" stroke-width="2"></rect>
												<path d="M6 20h52M6 30h52" fill="none" stroke="currentColor"
													stroke-width="2"></path>
												<path d="M20 56h24M26 50h12" fill="none" stroke="currentColor"
													stroke-width="2" stroke-linecap="round"></path>
											</svg>
										</div>
										<h3 class="pbmit-service-title">
											<a href="services/solar-installation">Solar Module &amp; System
												Installation</a>
										</h3>
										<p class="home-service-desc">Turnkey EPC from survey to commissioning</p>
										<div class="svc-card-cta">
											<a href="services/solar-installation" class="pbmit-btn outline svc-readmore"><span
													class="pbmit-button-text">Read More</span></a>
										</div>
									</div>
									<div class="pbmit-service-image-wrapper">
										<div class="pbmit-featured-img-wrapper">
											<div class="pbmit-featured-wrapper">
												<img src="images/homepage-2/service/service-img-01.jpg"
													alt="Solar Module & System Installation">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="120">
						<div class="pbmit-service-style-4 card-elevated svc-card svc-card--om">
							<div class="pbminfotech-post-item">
								<div class="pbmit-box-content-wrap">
									<div class="pbmit-content-box">
										<div class="pbminfotech-box-number">02</div>
										<div class="pbmit-service-icon" aria-hidden="true">
											<svg viewBox="0 0 64 64" role="presentation">
												<circle cx="40" cy="24" r="10" fill="none" stroke="currentColor"
													stroke-width="2"></circle>
												<path d="M40 12v-4M40 40v4M52 24h4M24 24h-4M48 16l3-3M32 32l-3 3"
													fill="none" stroke="currentColor" stroke-width="2"
													stroke-linecap="round"></path>
												<path d="M12 50l12-12" fill="none" stroke="currentColor" stroke-width="2"
													stroke-linecap="round"></path>
											</svg>
										</div>
										<h3 class="pbmit-service-title">
											<a href="services/operations-maintenance">Operations &amp; Maintenance
												(O&amp;M)</a>
										</h3>
										<p class="home-service-desc">Monitoring, preventive care, rapid troubleshooting</p>
										<div class="svc-card-cta">
											<a href="services/operations-maintenance" class="pbmit-btn outline svc-readmore"><span
													class="pbmit-button-text">Read More</span></a>
										</div>
									</div>
									<div class="pbmit-service-image-wrapper">
										<div class="pbmit-featured-img-wrapper">
											<div class="pbmit-featured-wrapper">
												<img src="images/homepage-2/service/service-img-02.jpg"
													alt="Operations & Maintenance">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
						<div class="pbmit-service-style-4 card-elevated svc-card svc-card--consult">
							<div class="pbminfotech-post-item">
								<div class="pbmit-box-content-wrap">
									<div class="pbmit-content-box">
										<div class="pbminfotech-box-number">03</div>
										<div class="pbmit-service-icon" aria-hidden="true">
											<svg viewBox="0 0 64 64" role="presentation">
												<path
													d="M32 10c-9.94 0-18 8.06-18 18 0 7.52 4.63 14.14 11.3 16.8V50h13.4v-5.2C45.37 42.14 50 35.52 50 28c0-9.94-8.06-18-18-18Z"
													fill="none" stroke="currentColor" stroke-width="2"
													stroke-linejoin="round"></path>
												<path d="M27 54h10M29 58h6" fill="none" stroke="currentColor"
													stroke-width="2" stroke-linecap="round"></path>
												<path d="M32 26l6-6M26 30l4-4" fill="none" stroke="currentColor"
													stroke-width="2" stroke-linecap="round"></path>
											</svg>
										</div>
										<h3 class="pbmit-service-title">
											<a href="services/energy-consulting">Energy Efficiency Consulting</a>
										</h3>
										<p class="home-service-desc">Audits, load analysis, ROI planning</p>
										<div class="svc-card-cta">
											<a href="services/energy-consulting" class="pbmit-btn outline svc-readmore"><span
													class="pbmit-button-text">Read More</span></a>
										</div>
									</div>
									<div class="pbmit-service-image-wrapper">
										<div class="pbmit-featured-img-wrapper">
											<div class="pbmit-featured-wrapper">
												<img src="images/homepage-2/service/service-img-03.jpg"
													alt="Energy Efficiency Consulting">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
						<div class="pbmit-service-style-4 card-elevated svc-card svc-card--ppa">
							<div class="pbminfotech-post-item">
								<div class="pbmit-box-content-wrap">
									<div class="pbmit-content-box">
										<div class="pbminfotech-box-number">04</div>
										<div class="pbmit-service-icon" aria-hidden="true">
											<svg viewBox="0 0 64 64" role="presentation">
												<path d="M12 18h28v28H12z" fill="none" stroke="currentColor"
													stroke-width="2"></path>
												<path d="M40 24h12v18H40" fill="none" stroke="currentColor"
													stroke-width="2"></path>
												<path d="M18 24h16M18 30h16M18 36h10" fill="none" stroke="currentColor"
													stroke-width="2" stroke-linecap="round"></path>
												<path d="M44 18v-6M44 50v6" fill="none" stroke="currentColor"
													stroke-width="2" stroke-linecap="round"></path>
											</svg>
										</div>
										<h3 class="pbmit-service-title">
											<a href="services/open-access-ppa">Open Access &amp; Power Purchase</a>
										</h3>
										<p class="home-service-desc">Solar parks and long-term PPA strategy</p>
										<div class="svc-card-cta">
											<a href="services/open-access-ppa" class="pbmit-btn outline svc-readmore"><span
													class="pbmit-button-text">Read More</span></a>
										</div>
									</div>
									<div class="pbmit-service-image-wrapper">
										<div class="pbmit-featured-img-wrapper">
											<div class="pbmit-featured-wrapper">
												<img src="images/homepage-2/service/service-img-04.jpg"
													alt="Open Access & Power Purchase">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</section>
	<?php } ?>
</div>
<?php include 'includes/footer.php'; ?>
