<?php include 'includes/header.php'; ?>
<?php
$sr_projects_page = sr_cms_page_get('projects');
$sr_projects_intro_title = $sr_projects_page && trim((string)$sr_projects_page['hero_title']) !== '' ? (string)$sr_projects_page['hero_title'] : 'Projects That Prove Our Promise';
$sr_projects_intro_desc = $sr_projects_page && trim((string)$sr_projects_page['hero_subtitle']) !== '' ? (string)$sr_projects_page['hero_subtitle'] : 'From rooftop systems in Nashik to megawatt-scale solar farms, every project reflects our commitment to quality, efficiency, and clean energy.';
$sr_banner_image = $sr_projects_page && trim((string)($sr_projects_page['banner_image'] ?? '')) !== '' ? (string)$sr_projects_page['banner_image'] : '';
$sr_projects_tbar = $sr_projects_page && trim((string)($sr_projects_page['title'] ?? '')) !== '' ? (string)$sr_projects_page['title'] : 'Projects';
$sr_page_override = $sr_projects_page && trim((string)($sr_projects_page['content'] ?? '')) !== '' ? (string)$sr_projects_page['content'] : '';

$sr_card1_badge = sr_cms_setting_get('projects_card1_badge', 'ROOFTOP');
$sr_card1_title = sr_cms_setting_get('projects_card1_title', 'Rooftop Solar Systems');
$sr_card1_desc = sr_cms_setting_get('projects_card1_desc', 'Custom-designed solar installations for commercial and industrial rooftops. We maximise every square foot of roof space to deliver the highest possible energy output, integrating seamlessly with existing electrical infrastructure.');
$sr_card1_list_title = sr_cms_setting_get('projects_card1_list_title', 'Featured Projects (placeholders)');
$sr_card1_list = array_values(array_filter([
	sr_cms_setting_get('projects_card1_list1', 'Commercial warehouse — Nashik — 100 kW — Savings: ~₹8 lakh/year'),
	sr_cms_setting_get('projects_card1_list2', 'Educational Institution — Nashik — 50 kW — Savings: ~₹4 lakh/year'),
	sr_cms_setting_get('projects_card1_list3', 'Hotel — Maharashtra — 80 kW — Savings: ~₹6.5 lakh/year'),
], static fn($v) => trim((string)$v) !== ''));

$sr_card2_badge = sr_cms_setting_get('projects_card2_badge', 'OPEN ACCESS');
$sr_card2_title = sr_cms_setting_get('projects_card2_title', 'Open Access Captive Projects');
$sr_card2_desc = sr_cms_setting_get('projects_card2_desc', 'Large-scale solar projects ranging from 1 MW to 20 MW, developed for industrial and institutional clients seeking direct access to cost-efficient, clean energy under the Open Access regulatory framework.');
$sr_card2_list_title = sr_cms_setting_get('projects_card2_list_title', 'Featured Projects (placeholders)');
$sr_card2_list = array_values(array_filter([
	sr_cms_setting_get('projects_card2_list1', 'Varun Agro Food Processing Pvt. Ltd. — 900 kW — Transformative results in energy savings and sustainability'),
	sr_cms_setting_get('projects_card2_list2', ''),
	sr_cms_setting_get('projects_card2_list3', ''),
], static fn($v) => trim((string)$v) !== ''));

$sr_card3_badge = sr_cms_setting_get('projects_card3_badge', 'SOLAR PARKS');
$sr_card3_title = sr_cms_setting_get('projects_card3_title', 'Solar Farming & Parks');
$sr_card3_desc = sr_cms_setting_get('projects_card3_desc', 'We develop and manage utility-scale solar farms, providing developers and investors with end-to-end EPC services, land facilitation, and grid connectivity. Our solar parks offer a plug-and-play model for large-scale renewable energy generation.');
$sr_card3_list_title = sr_cms_setting_get('projects_card3_list_title', 'What we provide in Solar Parks');
$sr_card3_list = array_values(array_filter([
	sr_cms_setting_get('projects_card3_list1', 'Land identification and acquisition support'),
	sr_cms_setting_get('projects_card3_list2', 'Grid connectivity and evacuation planning'),
	sr_cms_setting_get('projects_card3_list3', 'End-to-end EPC execution and commissioning'),
], static fn($v) => trim((string)$v) !== ''));

$sr_gallery_title = sr_cms_setting_get('projects_gallery_title', 'Featured Project Gallery');
$sr_gallery_desc = sr_cms_setting_get('projects_gallery_desc', 'Placeholders shown below — client to provide 6–8 completed projects (name, location, capacity, photo) for the final gallery.');
$sr_filter_all_label = sr_cms_setting_get('projects_filter_all', 'All');
$sr_filter_rooftop_label = sr_cms_setting_get('projects_filter_rooftop', 'Rooftop');
$sr_filter_openaccess_label = sr_cms_setting_get('projects_filter_openaccess', 'Open Access');
$sr_filter_parks_label = sr_cms_setting_get('projects_filter_parks', 'Solar Parks');
$sr_cta_title = sr_cms_setting_get('projects_cta_title', 'Have a project in mind?');
$sr_cta_desc = sr_cms_setting_get('projects_cta_desc', 'Tell us about your energy requirements and we will design the perfect solar solution for you. Our team will respond within 24 hours with a preliminary proposal.');
$sr_cta_btn_label = sr_cms_setting_get('projects_cta_btn_label', 'Start Your Project');
$sr_cta_btn_url = sr_cms_setting_get('projects_cta_btn_url', 'contact');

$sr_cat = isset($_GET['cat']) ? strtolower(trim((string)$_GET['cat'])) : '';
if (!in_array($sr_cat, ['rooftop', 'openaccess', 'parks'], true)) {
	$sr_cat = '';
}
$sr_page_num = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($sr_page_num < 1) $sr_page_num = 1;
$sr_per_page = 9;
$sr_total = 0;
$sr_total_pages = 1;
$sr_offset = ($sr_page_num - 1) * $sr_per_page;

$sr_projects_gallery = [];
$sr_db = sr_cms_db_try();
if ($sr_db instanceof mysqli) {
	$where = 'featured = 1';
	$params = [];
	$types = '';
	if ($sr_cat !== '') {
		$where .= ' AND category = ?';
		$params[] = $sr_cat;
		$types .= 's';
	}

	$sqlCount = 'SELECT COUNT(*) AS cnt FROM cms_projects WHERE ' . $where;
	$stmtCnt = $sr_db->prepare($sqlCount);
	if ($stmtCnt) {
		if ($params) {
			$stmtCnt->bind_param($types, ...$params);
		}
		$stmtCnt->execute();
		$resCnt = $stmtCnt->get_result();
		if ($resCnt) {
			$row = $resCnt->fetch_assoc();
			$sr_total = (int)($row['cnt'] ?? 0);
			$resCnt->free();
		}
		$stmtCnt->close();
	}
	$sr_total_pages = max(1, (int)ceil($sr_total / $sr_per_page));
	if ($sr_page_num > $sr_total_pages) {
		$sr_page_num = $sr_total_pages;
		$sr_offset = ($sr_page_num - 1) * $sr_per_page;
	}

	$sql = "SELECT id, slug, category, category_label, title, location_label, capacity_label, savings_label, outcome_label, image, content
		FROM cms_projects
		WHERE $where
		ORDER BY sort_order ASC, updated_at DESC
		LIMIT ? OFFSET ?";
	$stmt = $sr_db->prepare($sql);
	if ($stmt) {
		$lim = (int)$sr_per_page;
		$off = (int)$sr_offset;
		if ($params) {
			$types2 = $types . 'ii';
			$bindArgs = array_merge($params, [$lim, $off]);
			$stmt->bind_param($types2, ...$bindArgs);
		} else {
			$stmt->bind_param('ii', $lim, $off);
		}
		$stmt->execute();
		$res = $stmt->get_result();
		if ($res) {
			while ($row = $res->fetch_assoc()) {
				$sr_projects_gallery[] = $row;
			}
			$res->free();
		}
		$stmt->close();
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
						<h1 class="pbmit-tbar-title"><?php echo htmlspecialchars($sr_projects_tbar, ENT_QUOTES, 'UTF-8'); ?></h1>
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
	<?php if ($sr_page_override !== '') { ?>
		<?php echo $sr_page_override; ?>
	<?php } else { ?>
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
						<div class="sr-product-badge"><?php echo htmlspecialchars($sr_card1_badge, ENT_QUOTES, 'UTF-8'); ?></div>
						<h3 class="sr-product-title"><?php echo htmlspecialchars($sr_card1_title, ENT_QUOTES, 'UTF-8'); ?></h3>
						<p class="sr-product-desc"><?php echo htmlspecialchars($sr_card1_desc, ENT_QUOTES, 'UTF-8'); ?></p>
						<div class="sr-modal-section-title"><?php echo htmlspecialchars($sr_card1_list_title, ENT_QUOTES, 'UTF-8'); ?></div>
						<ul class="sr-modal-list sr-icon-list">
							<?php foreach ($sr_card1_list as $li) { ?><li><i class="pbmit-base-icon-tick"></i><?php echo htmlspecialchars((string)$li, ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
						</ul>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="sr-product-card h-100">
						<div class="sr-product-media">
							<img src="images/portfolio/portfolio-04.jpg" alt="Open Access Captive Projects">
							<div class="sr-product-icon"><i class="pbmit-base-icon-budgeting"></i></div>
						</div>
						<div class="sr-product-badge sr-product-badge--blue"><?php echo htmlspecialchars($sr_card2_badge, ENT_QUOTES, 'UTF-8'); ?></div>
						<h3 class="sr-product-title"><?php echo htmlspecialchars($sr_card2_title, ENT_QUOTES, 'UTF-8'); ?></h3>
						<p class="sr-product-desc"><?php echo htmlspecialchars($sr_card2_desc, ENT_QUOTES, 'UTF-8'); ?></p>
						<div class="sr-modal-section-title"><?php echo htmlspecialchars($sr_card2_list_title, ENT_QUOTES, 'UTF-8'); ?></div>
						<ul class="sr-modal-list sr-icon-list">
							<?php foreach ($sr_card2_list as $li) { ?><li><i class="pbmit-base-icon-tick"></i><?php echo htmlspecialchars((string)$li, ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
						</ul>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="sr-product-card h-100">
						<div class="sr-product-media">
							<img src="images/portfolio/portfolio-05.jpg" alt="Solar Farming &amp; Parks">
							<div class="sr-product-icon"><i class="pbmit-base-icon-location-1"></i></div>
						</div>
						<div class="sr-product-badge sr-product-badge--orange"><?php echo htmlspecialchars($sr_card3_badge, ENT_QUOTES, 'UTF-8'); ?></div>
						<h3 class="sr-product-title"><?php echo htmlspecialchars($sr_card3_title, ENT_QUOTES, 'UTF-8'); ?></h3>
						<p class="sr-product-desc"><?php echo htmlspecialchars($sr_card3_desc, ENT_QUOTES, 'UTF-8'); ?></p>
						<div class="sr-modal-section-title"><?php echo htmlspecialchars($sr_card3_list_title, ENT_QUOTES, 'UTF-8'); ?></div>
						<ul class="sr-modal-list sr-icon-list">
							<?php foreach ($sr_card3_list as $li) { ?><li><i class="pbmit-base-icon-tick"></i><?php echo htmlspecialchars((string)$li, ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section-xl" id="gallery" data-aos="fade-up" data-aos-duration="800" data-aos-delay="120">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_gallery_title, ENT_QUOTES, 'UTF-8'); ?></h2>
				<p class="mb-0"><?php echo htmlspecialchars($sr_gallery_desc, ENT_QUOTES, 'UTF-8'); ?></p>
			</div>

			<div class="pbmit-sortable-yes">
				<div class="pbmit-sortable-list text-center mb-4">
					<a href="projects" class="<?php echo $sr_cat === '' ? 'pbmit-selected' : ''; ?>" data-sortby="*"><?php echo htmlspecialchars($sr_filter_all_label, ENT_QUOTES, 'UTF-8'); ?></a>
					<a href="projects?cat=rooftop" class="<?php echo $sr_cat === 'rooftop' ? 'pbmit-selected' : ''; ?>" data-sortby="rooftop"><?php echo htmlspecialchars($sr_filter_rooftop_label, ENT_QUOTES, 'UTF-8'); ?></a>
					<a href="projects?cat=openaccess" class="<?php echo $sr_cat === 'openaccess' ? 'pbmit-selected' : ''; ?>" data-sortby="openaccess"><?php echo htmlspecialchars($sr_filter_openaccess_label, ENT_QUOTES, 'UTF-8'); ?></a>
					<a href="projects?cat=parks" class="<?php echo $sr_cat === 'parks' ? 'pbmit-selected' : ''; ?>" data-sortby="parks"><?php echo htmlspecialchars($sr_filter_parks_label, ENT_QUOTES, 'UTF-8'); ?></a>
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
							$projectLink = $slug !== '' ? ('projects/' . rawurlencode($slug)) : 'projects#gallery';
							$projectHref = $slug !== '' ? 'javascript:void(0)' : $projectLink;
							$loc = trim((string)($item['location_label'] ?? ''));
							$cap = trim((string)($item['capacity_label'] ?? ''));
							$sav = trim((string)($item['savings_label'] ?? ''));
							$outcome = trim((string)($item['outcome_label'] ?? ''));
							$content = (string)($item['content'] ?? '');
							$portCatText = $cat === 'openaccess' ? 'Open Access' : ($cat === 'parks' ? 'Solar Park' : 'Rooftop Solar');
							?>
							<article class="pbmit-portfolio-style-1 pbmit-ele <?php echo htmlspecialchars($cat, ENT_QUOTES, 'UTF-8'); ?> col-md-6 col-lg-4">
								<div class="pbminfotech-post-content">
									<div class="pbmit-featured-img-wrapper">
										<div class="pbmit-featured-wrapper">
											<a href="<?php echo htmlspecialchars($projectHref, ENT_QUOTES, 'UTF-8'); ?>" class="d-block" <?php echo $slug !== '' ? 'data-bs-toggle="modal" data-bs-target="#projectModal" data-project="' . htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') . '" data-title="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
												<img src="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
											</a>
											<div class="sr-project-chip <?php echo $chipClass; ?>"><?php echo htmlspecialchars($catLabel, ENT_QUOTES, 'UTF-8'); ?></div>
											<div class="sr-project-mark"><i class="<?php echo htmlspecialchars($markIcon, ENT_QUOTES, 'UTF-8'); ?>"></i></div>
										</div>
									</div>
									<div class="pbminfotech-box-content">
										<div class="pbminfotech-titlebox">
											<div class="pbmit-port-cat"><a href="projects#gallery" rel="tag"><?php echo htmlspecialchars($portCatText, ENT_QUOTES, 'UTF-8'); ?></a></div>
											<h4 class="pbmit-portfolio-title"><a href="<?php echo htmlspecialchars($projectHref, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $slug !== '' ? 'data-bs-toggle="modal" data-bs-target="#projectModal" data-project="' . htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') . '" data-title="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></a></h4>	
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
								<?php if ($slug !== '') { ?>
									<div class="d-none" id="project-detail-<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>">
										<div class="sr-modal-top">
											<div class="sr-modal-media">
												<img src="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
											</div>
											<div class="sr-modal-meta">
												<div class="sr-project-chip <?php echo $chipClass; ?>" style="position: static; display: inline-flex;"><?php echo htmlspecialchars($catLabel, ENT_QUOTES, 'UTF-8'); ?></div>
												<div class="sr-modal-section-title mt-3">Project details</div>
												<ul class="sr-modal-list sr-icon-list mb-0">
													<?php if ($loc !== '') { ?><li><i class="pbmit-base-icon-location-1"></i><span>Location:</span> <?php echo htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
													<?php if ($cap !== '') { ?><li><i class="pbmit-base-icon-lightening"></i><span>Capacity:</span> <?php echo htmlspecialchars($cap, ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
													<?php if ($sav !== '') { ?><li><i class="pbmit-base-icon-budgeting"></i><span>Savings:</span> <?php echo htmlspecialchars($sav, ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
													<?php if ($outcome !== '') { ?><li><i class="pbmit-base-icon-check-mark"></i><span>Outcome:</span> <?php echo htmlspecialchars($outcome, ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
												</ul>
											</div>
										</div>
										<?php if (trim($content) !== '') { ?>
											<div class="mt-3"><?php echo $content; ?></div>
										<?php } ?>
										<div class="sr-modal-cta">
											<a href="<?php echo htmlspecialchars($projectLink, ENT_QUOTES, 'UTF-8'); ?>" class="pbmit-btn outline"><span class="pbmit-button-text">Open full details</span></a>
											<a href="contact" class="pbmit-btn"><span class="pbmit-button-text">Request similar project</span></a>
										</div>
									</div>
								<?php } ?>
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
										<h3 class="pbmit-portfolio-title text-dark">Commercial Warehouse - Nashik</h3>
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
				<?php if ($sr_total_pages > 1) { ?>
					<nav aria-label="Projects pagination" class="pt-4">
						<ul class="pagination justify-content-center mb-0">
							<?php
							$base = 'projects';
							$qCat = $sr_cat !== '' ? ('cat=' . urlencode($sr_cat) . '&') : '';
							$prev = max(1, $sr_page_num - 1);
							$next = min($sr_total_pages, $sr_page_num + 1);
							?>
							<li class="page-item <?php echo $sr_page_num <= 1 ? 'disabled' : ''; ?>">
								<a class="page-link" href="<?php echo $base . ($sr_page_num <= 1 ? '#' : ('?' . $qCat . 'page=' . $prev . '#gallery')); ?>">Prev</a>
							</li>
							<?php for ($p = 1; $p <= $sr_total_pages; $p++) { ?>
								<li class="page-item <?php echo $p === $sr_page_num ? 'active' : ''; ?>">
									<a class="page-link" href="<?php echo $base . '?' . $qCat . 'page=' . $p . '#gallery'; ?>"><?php echo (int)$p; ?></a>
								</li>
							<?php } ?>
							<li class="page-item <?php echo $sr_page_num >= $sr_total_pages ? 'disabled' : ''; ?>">
								<a class="page-link" href="<?php echo $base . ($sr_page_num >= $sr_total_pages ? '#' : ('?' . $qCat . 'page=' . $next . '#gallery')); ?>">Next</a>
							</li>
						</ul>
					</nav>
				<?php } ?>
			</div>
		</div>
	</section>

	<style>
		#projectModalBody img,
		#projectModalBody figure img {
			max-width: 100%;
			height: auto;
			display: block;
			margin: 18px 0;
			border-radius: 18px;
			box-shadow: 0 18px 40px rgba(0, 0, 0, .10);
			border: 1px solid rgba(10, 25, 38, .10);
			background: #fff;
		}
		#projectModalBody figure { margin: 18px 0; }
		#projectModalBody figcaption { margin-top: 10px; font-weight: 600; color: rgba(10, 25, 38, .70); }
	</style>

	<div class="modal fade sr-modal" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="projectModalLabel">Project</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body" id="projectModalBody"></div>
			</div>
		</div>
	</div>

	<script>
		(function () {
			var modalEl = document.getElementById('projectModal');
			if (!modalEl) return;
			modalEl.addEventListener('show.bs.modal', function (event) {
				var btn = event.relatedTarget;
				if (!btn) return;
				var key = btn.getAttribute('data-project');
				var title = btn.getAttribute('data-title') || 'Project';
				var source = document.getElementById('project-detail-' + key);
				var body = document.getElementById('projectModalBody');
				var titleEl = document.getElementById('projectModalLabel');
				if (titleEl) titleEl.textContent = title;
				if (body) body.innerHTML = source ? source.innerHTML : '';
			});
		})();
	</script>

	<section class="section-xl sr-projects-cta" data-aos="fade-up" data-aos-duration="800" data-aos-delay="160">
		<div class="container">
			<div class="sr-projects-cta-inner">
				<div class="sr-projects-cta-text">
					<h2 class="sr-projects-cta-title"><?php echo htmlspecialchars($sr_cta_title, ENT_QUOTES, 'UTF-8'); ?></h2>
					<p class="sr-projects-cta-desc mb-0"><?php echo htmlspecialchars($sr_cta_desc, ENT_QUOTES, 'UTF-8'); ?></p>
				</div>
				<a href="<?php echo htmlspecialchars($sr_cta_btn_url, ENT_QUOTES, 'UTF-8'); ?>" class="pbmit-btn sr-projects-cta-btn"><span class="pbmit-button-text"><?php echo htmlspecialchars($sr_cta_btn_label, ENT_QUOTES, 'UTF-8'); ?></span></a>
			</div>
		</div>
	</section>
	<?php } ?>
</div>

<?php include 'includes/footer.php'; ?>
