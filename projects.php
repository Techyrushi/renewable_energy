<?php include 'includes/header.php'; ?>
<?php
$sr_projects_page = sr_cms_page_get('projects');
$sr_projects_intro_title = $sr_projects_page && trim((string)$sr_projects_page['hero_title']) !== '' ? (string)$sr_projects_page['hero_title'] : 'Projects That Prove Our Promise';
$sr_projects_intro_desc = $sr_projects_page && trim((string)$sr_projects_page['hero_subtitle']) !== '' ? (string)$sr_projects_page['hero_subtitle'] : 'From rooftop systems in Nashik to megawatt-scale solar farms, every project reflects our commitment to quality, efficiency, and clean energy.';
$sr_banner_image = $sr_projects_page && trim((string)($sr_projects_page['banner_image'] ?? '')) !== '' ? (string)$sr_projects_page['banner_image'] : '';

$sr_projects_gallery = [];
$sr_db = sr_cms_db_try();
if ($sr_db instanceof mysqli) {
	$res = $sr_db->query("SELECT id, slug, category, category_label, title, location_label, capacity_label, savings_label, outcome_label, image
		FROM cms_projects
		WHERE featured = 1
		ORDER BY sort_order ASC, updated_at DESC
		LIMIT 60");
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$sr_projects_gallery[] = $row;
		}
		$res->free();
	}
}
?>
</header>

<!-- Title Bar -->
<div class="pbmit-title-bar-wrapper sr-projects-hero"<?php echo $sr_banner_image !== '' ? (' style="background-image:url(' . htmlspecialchars($sr_banner_image, ENT_QUOTES, 'UTF-8') . ');"') : ''; ?>>
	<div class="container">
		<div class="pbmit-title-bar-content">
			<div class="pbmit-title-bar-content-inner">
				<div class="pbmit-tbar">
					<div class="pbmit-tbar-inner container">
						<h1 class="pbmit-tbar-title"> Projects</h1>
					</div>
				</div>
				<div class="pbmit-breadcrumb">
					<div class="pbmit-breadcrumb-inner">
						<span>
							<a title="" href="./" class="home"><span>Home</span></a>
						</span>
						<i class="pbmit-base-icon-arrow-right-2"></i>
						<span><span class="post-root post post-post current-item"> Projects</span></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Title Bar End-->

<div class="page-content projects-page">
	<section class="section-xl projects-hero" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_projects_intro_title, ENT_QUOTES, 'UTF-8'); ?></h2>
				<?php if (trim($sr_projects_intro_desc) !== '') { ?>
					<p class="mb-0"><?php echo $sr_projects_intro_desc; ?></p>
				<?php } ?>
			</div>
		</div>
	</section>

	<section class="section-xl" data-aos="fade-up" data-aos-duration="800" data-aos-delay="80">
		<div class="container">
			<div class="row g-4">
				<div class="col-lg-4">
					<div class="sr-product-card h-100">
						<div class="sr-product-media">
							<img src="images/portfolio/portfolio-06.jpg" alt="Rooftop Solar Systems">
							<div class="sr-product-icon"><i class="pbmit-base-icon-home"></i></div>
						</div>
						<div class="sr-product-badge">ROOFTOP</div>
						<h3 class="sr-product-title">Rooftop Solar Systems</h3>
						<p class="sr-product-desc">Custom-designed solar installations for commercial and industrial rooftops. We maximise every square foot of roof space to deliver the highest possible energy output, integrating seamlessly with existing electrical infrastructure.</p>
						<div class="sr-modal-section-title">Featured Projects (placeholders)</div>
						<ul class="sr-modal-list sr-icon-list">
							<li><i class="pbmit-base-icon-tick"></i>Commercial warehouse — Nashik — 100 kW — Savings: ~₹8 lakh/year</li>
							<li><i class="pbmit-base-icon-tick"></i>Educational Institution — Nashik — 50 kW — Savings: ~₹4 lakh/year</li>
							<li><i class="pbmit-base-icon-tick"></i>Hotel — Maharashtra — 80 kW — Savings: ~₹6.5 lakh/year</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="sr-product-card h-100">
						<div class="sr-product-media">
							<img src="images/portfolio/portfolio-04.jpg" alt="Open Access Captive Projects">
							<div class="sr-product-icon"><i class="pbmit-base-icon-budgeting"></i></div>
						</div>
						<div class="sr-product-badge sr-product-badge--blue">OPEN ACCESS</div>
						<h3 class="sr-product-title">Open Access Captive Projects</h3>
						<p class="sr-product-desc">Large-scale solar projects ranging from 1 MW to 20 MW, developed for industrial and institutional clients seeking direct access to cost-efficient, clean energy under the Open Access regulatory framework.</p>
						<div class="sr-modal-section-title">Featured Projects (placeholders)</div>
						<ul class="sr-modal-list sr-icon-list">
							<li><i class="pbmit-base-icon-tick"></i>Varun Agro Food Processing Pvt. Ltd. — 900 kW — Transformative results in energy savings and sustainability</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="sr-product-card h-100">
						<div class="sr-product-media">
							<img src="images/portfolio/portfolio-05.jpg" alt="Solar Farming &amp; Parks">
							<div class="sr-product-icon"><i class="pbmit-base-icon-location-1"></i></div>
						</div>
						<div class="sr-product-badge sr-product-badge--orange">SOLAR PARKS</div>
						<h3 class="sr-product-title">Solar Farming &amp; Parks</h3>
						<p class="sr-product-desc">We develop and manage utility-scale solar farms, providing developers and investors with end-to-end EPC services, land facilitation, and grid connectivity. Our solar parks offer a plug-and-play model for large-scale renewable energy generation.</p>
						<div class="sr-modal-section-title">What we provide in Solar Parks</div>
						<ul class="sr-modal-list sr-icon-list">
							<li><i class="pbmit-base-icon-tick"></i>Land identification and acquisition support</li>
							<li><i class="pbmit-base-icon-tick"></i>Grid connectivity and evacuation planning</li>
							<li><i class="pbmit-base-icon-tick"></i>End-to-end EPC execution and commissioning</li>
							<li><i class="pbmit-base-icon-tick"></i>Operations &amp; maintenance support</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section-xl" id="gallery" data-aos="fade-up" data-aos-duration="800" data-aos-delay="120">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title">Featured Project Gallery</h2>
				<p class="mb-0">Placeholders shown below — client to provide 6–8 completed projects (name, location, capacity, photo) for the final gallery.</p>
			</div>

			<div class="pbmit-sortable-yes">
				<div class="pbmit-sortable-list text-center mb-4">
					<a href="javascript:void(0)" class="pbmit-selected" data-sortby="*">All</a>
					<a href="javascript:void(0)" data-sortby="rooftop">Rooftop</a>
					<a href="javascript:void(0)" data-sortby="openaccess">Open Access</a>
					<a href="javascript:void(0)" data-sortby="parks">Solar Parks</a>
				</div>
				<div class="row g-4 pbmit-element-posts-wrapper">
					<?php if ($sr_projects_gallery) { ?>
						<?php foreach ($sr_projects_gallery as $item) { ?>
							<?php
							$cat = strtolower((string)($item['category'] ?? 'rooftop'));
							if (!in_array($cat, ['rooftop', 'openaccess', 'parks'], true)) {
								$cat = 'rooftop';
							}
							$catLabel = trim((string)($item['category_label'] ?? ''));
							if ($catLabel === '') {
								$catLabel = $cat === 'openaccess' ? 'Open Access' : ($cat === 'parks' ? 'Solar Parks' : 'Rooftop');
							}
							$chipClass = $cat === 'openaccess' ? 'sr-project-chip--openaccess' : ($cat === 'parks' ? 'sr-project-chip--parks' : 'sr-project-chip--rooftop');
							$markIcon = $cat === 'openaccess' ? 'pbmit-base-icon-budgeting' : ($cat === 'parks' ? 'pbmit-base-icon-location-1' : 'pbmit-base-icon-home');
							$image = trim((string)($item['image'] ?? ''));
							if ($image === '') {
								$image = $cat === 'openaccess' ? 'images/portfolio/portfolio-04.jpg' : ($cat === 'parks' ? 'images/portfolio/portfolio-05.jpg' : 'images/portfolio/portfolio-01.jpg');
							}
							$title = (string)($item['title'] ?? '');
							$slug = trim((string)($item['slug'] ?? ''));
							$projectHref = $slug !== '' ? ('projects/' . rawurlencode($slug)) : 'projects#gallery';
							$loc = trim((string)($item['location_label'] ?? ''));
							$cap = trim((string)($item['capacity_label'] ?? ''));
							$sav = trim((string)($item['savings_label'] ?? ''));
							$outcome = trim((string)($item['outcome_label'] ?? ''));
							$portCatText = $cat === 'openaccess' ? 'Open Access' : ($cat === 'parks' ? 'Solar Park' : 'Rooftop Solar');
							?>
							<article class="pbmit-portfolio-style-1 pbmit-ele <?php echo htmlspecialchars($cat, ENT_QUOTES, 'UTF-8'); ?> col-md-6 col-lg-4">
								<div class="pbminfotech-post-content">
									<div class="pbmit-featured-img-wrapper">
										<div class="pbmit-featured-wrapper">
											<a href="<?php echo htmlspecialchars($projectHref, ENT_QUOTES, 'UTF-8'); ?>" class="d-block">
												<img src="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
											</a>
											<div class="sr-project-chip <?php echo $chipClass; ?>"><?php echo htmlspecialchars($catLabel, ENT_QUOTES, 'UTF-8'); ?></div>
											<div class="sr-project-mark"><i class="<?php echo htmlspecialchars($markIcon, ENT_QUOTES, 'UTF-8'); ?>"></i></div>
										</div>
									</div>
									<div class="pbminfotech-box-content">
										<div class="pbminfotech-titlebox">
											<div class="pbmit-port-cat"><a href="projects#gallery" rel="tag"><?php echo htmlspecialchars($portCatText, ENT_QUOTES, 'UTF-8'); ?></a></div>
											<h3 class="pbmit-portfolio-title"><a href="<?php echo htmlspecialchars($projectHref, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></a></h3>
											<ul class="sr-project-details">
												<?php if ($loc !== '') { ?><li><i class="pbmit-base-icon-location-1"></i><span>Location:</span> <?php echo htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
												<?php if ($cap !== '') { ?><li><i class="pbmit-base-icon-lightening"></i><span>Capacity:</span> <?php echo htmlspecialchars($cap, ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
												<?php if ($sav !== '') { ?><li><i class="pbmit-base-icon-budgeting"></i><span>Savings:</span> <?php echo htmlspecialchars($sav, ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
												<?php if ($outcome !== '') { ?><li><i class="pbmit-base-icon-check-mark"></i><span>Outcome:</span> <?php echo htmlspecialchars($outcome, ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
												<?php if ($loc === '' && $cap === '' && $sav === '' && $outcome === '') { ?><li><i class="pbmit-base-icon-tick"></i><span>Details:</span> Available on request</li><?php } ?>
											</ul>
										</div>
									</div>
								</div>
							</article>
						<?php } ?>
					<?php } else { ?>
						<article class="pbmit-portfolio-style-1 pbmit-ele rooftop col-md-6 col-lg-4">
							<div class="pbminfotech-post-content">
								<div class="pbmit-featured-img-wrapper">
									<div class="pbmit-featured-wrapper">
										<img src="images/portfolio/portfolio-01.jpg" class="img-fluid" alt="">
										<div class="sr-project-chip sr-project-chip--rooftop">Rooftop</div>
										<div class="sr-project-mark"><i class="pbmit-base-icon-home"></i></div>
									</div>
								</div>
								<div class="pbminfotech-box-content">
									<div class="pbminfotech-titlebox">
										<div class="pbmit-port-cat"><a href="projects#gallery" rel="tag">Rooftop Solar</a></div>
										<h3 class="pbmit-portfolio-title">Commercial Warehouse - Nashik</h3>
										<ul class="sr-project-details">
											<li><i class="pbmit-base-icon-location-1"></i><span>Location:</span> Nashik</li>
											<li><i class="pbmit-base-icon-lightening"></i><span>Capacity:</span> 100 kW</li>
											<li><i class="pbmit-base-icon-budgeting"></i><span>Savings:</span> ~₹8 lakh/year</li>
										</ul>
									</div>
								</div>
							</div>
						</article>

						<article class="pbmit-portfolio-style-1 pbmit-ele rooftop col-md-6 col-lg-4">
							<div class="pbminfotech-post-content">
								<div class="pbmit-featured-img-wrapper">
									<div class="pbmit-featured-wrapper">
										<img src="images/portfolio/portfolio-02.jpg" class="img-fluid" alt="">
										<div class="sr-project-chip sr-project-chip--rooftop">Rooftop</div>
										<div class="sr-project-mark"><i class="pbmit-base-icon-home"></i></div>
									</div>
								</div>
								<div class="pbminfotech-box-content">
									<div class="pbminfotech-titlebox">
										<div class="pbmit-port-cat"><a href="projects#gallery" rel="tag">Rooftop Solar</a></div>
										<h3 class="pbmit-portfolio-title">Educational Institution — Nashik</h3>
										<ul class="sr-project-details">
											<li><i class="pbmit-base-icon-location-1"></i><span>Location:</span> Nashik</li>
											<li><i class="pbmit-base-icon-lightening"></i><span>Capacity:</span> 50 kW</li>
											<li><i class="pbmit-base-icon-budgeting"></i><span>Savings:</span> ~₹4 lakh/year</li>
										</ul>
									</div>
								</div>
							</div>
						</article>

						<article class="pbmit-portfolio-style-1 pbmit-ele rooftop col-md-6 col-lg-4">
							<div class="pbminfotech-post-content">
								<div class="pbmit-featured-img-wrapper">
									<div class="pbmit-featured-wrapper">
										<img src="images/portfolio/portfolio-03.jpg" class="img-fluid" alt="">
										<div class="sr-project-chip sr-project-chip--rooftop">Rooftop</div>
										<div class="sr-project-mark"><i class="pbmit-base-icon-home"></i></div>
									</div>
								</div>
								<div class="pbminfotech-box-content">
									<div class="pbminfotech-titlebox">
										<div class="pbmit-port-cat"><a href="projects#gallery" rel="tag">Rooftop Solar</a></div>
										<h3 class="pbmit-portfolio-title">Hotel — Maharashtra</h3>
										<ul class="sr-project-details">
											<li><i class="pbmit-base-icon-location-1"></i><span>Location:</span> Maharashtra</li>
											<li><i class="pbmit-base-icon-lightening"></i><span>Capacity:</span> 80 kW</li>
											<li><i class="pbmit-base-icon-budgeting"></i><span>Savings:</span> ~₹6.5 lakh/year</li>
										</ul>
									</div>
								</div>
							</div>
						</article>

						<article class="pbmit-portfolio-style-1 pbmit-ele openaccess col-md-6 col-lg-4">
							<div class="pbminfotech-post-content">
								<div class="pbmit-featured-img-wrapper">
									<div class="pbmit-featured-wrapper">
										<img src="images/portfolio/portfolio-04.jpg" class="img-fluid" alt="">
										<div class="sr-project-chip sr-project-chip--openaccess">Open Access</div>
										<div class="sr-project-mark"><i class="pbmit-base-icon-budgeting"></i></div>
									</div>
								</div>
								<div class="pbminfotech-box-content">
									<div class="pbminfotech-titlebox">
										<div class="pbmit-port-cat"><a href="projects#gallery" rel="tag">Open Access</a></div>
										<h3 class="pbmit-portfolio-title">Varun Agro Food Processing Pvt. Ltd.</h3>
										<ul class="sr-project-details">
											<li><i class="pbmit-base-icon-lightening"></i><span>Capacity:</span> 900 kW</li>
											<li><i class="pbmit-base-icon-check-mark"></i><span>Outcome:</span> Transformative savings &amp; sustainability</li>
										</ul>
									</div>
								</div>
							</div>
						</article>

						<article class="pbmit-portfolio-style-1 pbmit-ele parks col-md-6 col-lg-4">
							<div class="pbminfotech-post-content">
								<div class="pbmit-featured-img-wrapper">
									<div class="pbmit-featured-wrapper">
										<img src="images/portfolio/portfolio-05.jpg" class="img-fluid" alt="">
										<div class="sr-project-chip sr-project-chip--parks">Solar Parks</div>
										<div class="sr-project-mark"><i class="pbmit-base-icon-location-1"></i></div>
									</div>
								</div>
								<div class="pbminfotech-box-content">
									<div class="pbminfotech-titlebox">
										<div class="pbmit-port-cat"><a href="projects#gallery" rel="tag">Solar Park</a></div>
										<h3 class="pbmit-portfolio-title">Utility Solar Park — Maharashtra</h3>
										<ul class="sr-project-details">
											<li><i class="pbmit-base-icon-location-1"></i><span>Location:</span> Maharashtra</li>
											<li><i class="pbmit-base-icon-lightening"></i><span>Capacity:</span> 5 MW</li>
											<li><i class="pbmit-base-icon-tick"></i><span>Service:</span> End-to-end EPC &amp; O&amp;M</li>
										</ul>
									</div>
								</div>
							</div>
						</article>
					<?php } ?>
				</div>
			</div>
		</div>
	</section>

	<section class="section-xl sr-projects-cta" data-aos="fade-up" data-aos-duration="800" data-aos-delay="160">
		<div class="container">
			<div class="sr-projects-cta-inner">
				<div class="sr-projects-cta-text">
					<h2 class="sr-projects-cta-title">Have a project in mind?</h2>
					<p class="sr-projects-cta-desc mb-0">Tell us about your energy requirements and we will design the
						perfect solar solution for you. Our team will respond within 24 hours with a preliminary
						proposal.</p>
				</div>
				<a href="contact" class="pbmit-btn sr-projects-cta-btn"><span class="pbmit-button-text">Start Your
						Project</span></a>
			</div>
		</div>
	</section>
</div>

<?php include 'includes/footer.php'; ?>
