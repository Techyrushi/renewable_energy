<?php include 'includes/header.php'; ?>
<?php
$sr_page = sr_cms_page_get('why-us');
$sr_hero_title = $sr_page && trim((string)$sr_page['hero_title']) !== '' ? (string)$sr_page['hero_title'] : 'The Shivanjali Difference';
$sr_hero_subtitle = $sr_page && trim((string)$sr_page['hero_subtitle']) !== '' ? (string)$sr_page['hero_subtitle'] : 'We don&#8217;t just install solar panels. We build long-term energy partnerships that deliver measurable results.';
$sr_banner_image = $sr_page && trim((string)($sr_page['banner_image'] ?? '')) !== '' ? (string)$sr_page['banner_image'] : '';
$sr_page_override = $sr_page && trim((string)($sr_page['content'] ?? '')) !== '' ? (string)$sr_page['content'] : '';

$sr_diff_title = sr_cms_setting_get('why_diff_title', 'Why Clients Choose Us');
$sr_tech_title = sr_cms_setting_get('why_tech_title', 'Backed by Technology');
$sr_testimonials_title = sr_cms_setting_get('why_testimonials_title', 'Client Success Stories');
$sr_impact_title = sr_cms_setting_get('why_impact_title', 'Our Environmental Impact');

$sr_diff_icons = [
	'pbmit-base-icon-tick-1',
	'pbmit-base-icon-user-2',
	'pbmit-base-icon-fast-delivery',
	'pbmit-base-icon-check-square-regular',
	'pbmit-base-icon-method-draw-image',
	'pbmit-base-icon-support',
];
$sr_tech_icons = [
	'pbmit-base-icon-lightening',
	'pbmit-base-icon-search',
	'pbmit-base-icon-checked',
];
$sr_impact_icons = [
	'pbmit-base-icon-lightening',
	'pbmit-base-icon-check-mark',
	'pbmit-base-icon-customer',
];

$sr_testimonials = [];
$sr_db = sr_cms_db_try();
if ($sr_db instanceof mysqli) {
	$res = $sr_db->query('SELECT name, company, quote, image, rating FROM cms_testimonials WHERE is_active=1 ORDER BY sort_order ASC, updated_at DESC LIMIT 50');
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$sr_testimonials[] = $row;
		}
		$res->free();
	}
}
?>

<!-- Title Bar -->
<div class="pbmit-title-bar-wrapper sr-why-hero"<?php echo $sr_banner_image !== '' ? (' style="background-image:url(' . htmlspecialchars($sr_banner_image, ENT_QUOTES, 'UTF-8') . ');"') : ''; ?>>
	<div class="container">
		<div class="pbmit-title-bar-content">
			<div class="pbmit-title-bar-content-inner">
				<div class="pbmit-tbar">
					<div class="pbmit-tbar-inner container">
						<h1 class="pbmit-tbar-title"><?php echo htmlspecialchars($sr_hero_title, ENT_QUOTES, 'UTF-8'); ?></h1>
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
						<span><span class="post-root post post-post current-item"> Why Choose Us</span></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Title Bar End-->
</header>

<!-- Page Content -->
<div class="page-content why-us">
	<?php if ($sr_page_override !== '') { ?>
		<?php echo $sr_page_override; ?>
	<?php } else { ?>
	<section class="section-xl pbmit-bg-color-white sr-why-diff" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_diff_title, ENT_QUOTES, 'UTF-8'); ?></h2>
			</div>
			<div class="row g-4 pt-3">
				<?php for ($i = 1; $i <= 6; $i++) { ?>
					<?php
					$cardTitle = sr_cms_setting_get('why_diff_card' . $i . '_title', '');
					$cardDesc = sr_cms_setting_get('why_diff_card' . $i . '_desc', '');
					$icon = $sr_diff_icons[$i - 1] ?? 'pbmit-base-icon-tick-1';
					?>
					<div class="col-md-6 col-lg-4">
						<div class="sr-diff-card">
							<div class="sr-diff-icon"><i class="<?php echo htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?>"></i></div>
							<h3 class="sr-diff-title"><?php echo htmlspecialchars($cardTitle, ENT_QUOTES, 'UTF-8'); ?></h3>
							<p class="sr-diff-desc"><?php echo nl2br(htmlspecialchars($cardDesc, ENT_QUOTES, 'UTF-8')); ?></p>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

	<section class="section-xl sr-why-tech" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_tech_title, ENT_QUOTES, 'UTF-8'); ?></h2>
			</div>
			<div class="row g-4 pt-3">
				<?php for ($i = 1; $i <= 3; $i++) { ?>
					<?php
					$cardTitle = sr_cms_setting_get('why_tech_card' . $i . '_title', '');
					$cardDesc = sr_cms_setting_get('why_tech_card' . $i . '_desc', '');
					$icon = $sr_tech_icons[$i - 1] ?? 'pbmit-base-icon-lightening';
					?>
					<div class="col-md-6 col-lg-4">
						<div class="sr-tech-card">
							<div class="sr-tech-icon"><i class="<?php echo htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?>"></i></div>
							<h3 class="sr-tech-title"><?php echo htmlspecialchars($cardTitle, ENT_QUOTES, 'UTF-8'); ?></h3>
							<p class="sr-tech-desc"><?php echo nl2br(htmlspecialchars($cardDesc, ENT_QUOTES, 'UTF-8')); ?></p>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

	<section class="section-xl sr-why-testimonials" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_testimonials_title, ENT_QUOTES, 'UTF-8'); ?></h2>
			</div>
			<div class="swiper-slider" data-autoplay="true" data-loop="true" data-dots="true" data-arrows="false"
				data-columns="1" data-margin="30" data-effect="slide">
				<div class="swiper-wrapper">
					<?php if ($sr_testimonials) { ?>
						<?php
						$fallbackImgs = [
							'images/homepage-1/testimonial/testimonial-01.jpg',
							'images/homepage-1/testimonial/testimonial-02.jpg',
							'images/homepage-1/testimonial/testimonial-03.jpg',
						];
						?>
						<?php foreach ($sr_testimonials as $idx => $t) { ?>
							<?php
							$name = trim((string)($t['name'] ?? ''));
							$company = trim((string)($t['company'] ?? ''));
							$quote = trim((string)($t['quote'] ?? ''));
							$image = trim((string)($t['image'] ?? ''));
							$rating = (int)($t['rating'] ?? 5);
							if ($rating < 1) { $rating = 1; }
							if ($rating > 5) { $rating = 5; }
							if ($image === '') {
								$image = $fallbackImgs[$idx % count($fallbackImgs)];
							}
							?>
							<article class="pbmit-testimonial-style-2 swiper-slide">
								<div class="pbminfotech-post-item">
									<div class="pbmit-box-content-wrap">
										<div class="pbminfotech-box-desc">
											<div class="pbminfotech-box-star-ratings">
												<?php for ($s = 1; $s <= 5; $s++) { ?>
													<i class="pbmit-base-icon-star-1 <?php echo $s <= $rating ? 'pbmit-active' : ''; ?>"></i>
												<?php } ?>
											</div>
											<blockquote class="pbminfotech-testimonial-text">
												<p>&#8220;<?php echo htmlspecialchars($quote, ENT_QUOTES, 'UTF-8'); ?>&#8221;</p>
											</blockquote>
										</div>
										<div class="pbminfotech-box-author-wrapper d-flex align-items-center">
											<div class="pbminfotech-box-author d-flex align-items-center">
												<div class="pbminfotech-box-img">
													<div class="pbmit-featured-img-wrapper">
														<div class="pbmit-featured-wrapper">
															<img src="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>" alt="">
														</div>
													</div>
												</div>
												<div class="pbmit-auther-content">
													<h3 class="pbminfotech-box-title"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h3>
													<?php if ($company !== '') { ?>
														<div class="pbminfotech-testimonial-detail"><?php echo htmlspecialchars($company, ENT_QUOTES, 'UTF-8'); ?></div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</article>
						<?php } ?>
					<?php } else { ?>
					<article class="pbmit-testimonial-style-2 swiper-slide">
						<div class="pbminfotech-post-item">
							<div class="pbmit-box-content-wrap">
								<div class="pbminfotech-box-desc">
									<div class="pbminfotech-box-star-ratings">
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
									</div>
									<blockquote class="pbminfotech-testimonial-text">
										<p>&#8220;Partnering with Shivanjali Renewables for our 900 kW solar project has
											been a transformative experience. Their expertise, professionalism, and
											commitment to quality ensured the successful completion of our project. We
											are delighted with the energy savings and sustainability impact we&#8217;ve
											achieved.&#8221;</p>
									</blockquote>
								</div>
								<div class="pbminfotech-box-author-wrapper d-flex align-items-center">
									<div class="pbminfotech-box-author d-flex align-items-center">
										<div class="pbminfotech-box-img">
											<div class="pbmit-featured-img-wrapper">
												<div class="pbmit-featured-wrapper">
													<img src="images/homepage-1/testimonial/testimonial-01.jpg" alt="">
												</div>
											</div>
										</div>
										<div class="pbmit-auther-content">
											<h3 class="pbminfotech-box-title">Ms. Manisha Dhatrak</h3>
											<div class="pbminfotech-testimonial-detail">Varun Agro Food Processing Pvt.
												Ltd.</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</article>
					<article class="pbmit-testimonial-style-2 swiper-slide">
						<div class="pbminfotech-post-item">
							<div class="pbmit-box-content-wrap">
								<div class="pbminfotech-box-desc">
									<div class="pbminfotech-box-star-ratings">
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
									</div>
									<blockquote class="pbminfotech-testimonial-text">
										<p>&#8220;Smooth execution, transparent communication, and excellent quality of
											work. The monitoring setup and handover training were especially
											helpful.&#8221;</p>
									</blockquote>
								</div>
								<div class="pbminfotech-box-author-wrapper d-flex align-items-center">
									<div class="pbminfotech-box-author d-flex align-items-center">
										<div class="pbminfotech-box-img">
											<div class="pbmit-featured-img-wrapper">
												<div class="pbmit-featured-wrapper">
													<img src="images/homepage-1/testimonial/testimonial-02.jpg" alt="">
												</div>
											</div>
										</div>
										<div class="pbmit-auther-content">
											<h3 class="pbminfotech-box-title">Mr. Client Name</h3>
											<div class="pbminfotech-testimonial-detail">Company Name</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</article>
					<article class="pbmit-testimonial-style-2 swiper-slide">
						<div class="pbminfotech-post-item">
							<div class="pbmit-box-content-wrap">
								<div class="pbminfotech-box-desc">
									<div class="pbminfotech-box-star-ratings">
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
										<i class="pbmit-base-icon-star-1 pbmit-active"></i>
									</div>
									<blockquote class="pbminfotech-testimonial-text">
										<p>&#8220;From design to commissioning, the team was professional and detail
											oriented. We&#8217;re seeing strong performance and reliable support.&#8221;
										</p>
									</blockquote>
								</div>
								<div class="pbminfotech-box-author-wrapper d-flex align-items-center">
									<div class="pbminfotech-box-author d-flex align-items-center">
										<div class="pbminfotech-box-img">
											<div class="pbmit-featured-img-wrapper">
												<div class="pbmit-featured-wrapper">
													<img src="images/homepage-1/testimonial/testimonial-03.jpg" alt="">
												</div>
											</div>
										</div>
										<div class="pbmit-auther-content">
											<h3 class="pbminfotech-box-title">Ms. Client Name</h3>
											<div class="pbminfotech-testimonial-detail">Company Name</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</article>
					<?php } ?>
				</div>
			</div>
		</div>
	</section>

	<section class="section-xl sr-why-impact" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_impact_title, ENT_QUOTES, 'UTF-8'); ?></h2>
			</div>
			<div class="row g-4 pt-3">
				<?php for ($i = 1; $i <= 3; $i++) { ?>
					<?php
					$label = sr_cms_setting_get('why_impact' . $i . '_label', '');
					$toStr = sr_cms_setting_get('why_impact' . $i . '_to', '0');
					$to = (int) preg_replace('/\D+/', '', $toStr);
					$unit = sr_cms_setting_get('why_impact' . $i . '_unit', '');
					$desc = sr_cms_setting_get('why_impact' . $i . '_desc', '');
					$icon = $sr_impact_icons[$i - 1] ?? 'pbmit-base-icon-lightening';
					?>
					<div class="col-md-6 col-lg-4">
						<div class="sr-impact-card">
							<div class="sr-impact-top">
								<div class="sr-impact-icon"><i class="<?php echo htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?>"></i></div>
								<div class="sr-impact-label"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></div>
							</div>
							<div class="sr-impact-value">
								<span class="sr-impact-number pbmit-number-rotate numinate" data-appear-animation="animateDigits"
									data-from="0" data-to="<?php echo (int) $to; ?>" data-interval="5" data-before="" data-before-style="" data-after=""
									data-after-style=""><?php echo (int) $to; ?></span>
								<span class="sr-impact-unit"><?php echo htmlspecialchars($unit, ENT_QUOTES, 'UTF-8'); ?></span>
							</div>
							<p class="sr-impact-desc"><?php echo nl2br(htmlspecialchars($desc, ENT_QUOTES, 'UTF-8')); ?></p>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
	<?php } ?>
</div>
<!-- Page Content End -->

<?php include 'includes/footer.php'; ?>	
