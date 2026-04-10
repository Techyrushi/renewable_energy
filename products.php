<?php include 'includes/header.php'; ?>
<?php
$sr_products_page = sr_cms_page_get('products');
$sr_products_tbar = $sr_products_page && trim((string)$sr_products_page['title']) !== '' ? (string)$sr_products_page['title'] : 'Products';
$sr_products_hero_title = $sr_products_page && trim((string)$sr_products_page['hero_title']) !== '' ? (string)$sr_products_page['hero_title'] : 'Solar Systems for Every Scale — 3 kW to 20 MW';
$sr_products_hero_subtitle = $sr_products_page && trim((string)$sr_products_page['hero_subtitle']) !== '' ? (string)$sr_products_page['hero_subtitle'] : 'Whether you are a homeowner, a factory owner, or a large-scale developer, we have the right solar solution to match your energy needs and budget.';
$sr_banner_image = $sr_products_page && trim((string)($sr_products_page['banner_image'] ?? '')) !== '' ? (string)$sr_products_page['banner_image'] : '';
$sr_page_override = $sr_products_page && trim((string)($sr_products_page['content'] ?? '')) !== '' ? (string)$sr_products_page['content'] : '';
$sr_open_product = isset($_GET['open']) ? trim((string)$_GET['open']) : '';

$sr_products_items = [];
$sr_db = sr_cms_db_try();
if ($sr_db instanceof mysqli) {
	$res = $sr_db->query("SELECT slug, category_anchor, badge_label, title, range_label, short_desc, bullets, image, content
		FROM cms_products
		WHERE published = 1
		ORDER BY sort_order ASC, updated_at DESC
		LIMIT 100");
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$sr_products_items[] = $row;
		}
		$res->free();
	}
}
?>
</header>
<div class="pbmit-title-bar-wrapper sr-projects-hero"<?php echo $sr_banner_image !== '' ? (' style="background-image:url(' . htmlspecialchars($sr_banner_image, ENT_QUOTES, 'UTF-8') . ');"') : ''; ?>>
	<div class="container">
		<div class="pbmit-title-bar-content">
			<div class="pbmit-title-bar-content-inner">
				<div class="pbmit-tbar">
					<div class="pbmit-tbar-inner container">
						<h1 class="pbmit-tbar-title"><?php echo htmlspecialchars($sr_products_tbar, ENT_QUOTES, 'UTF-8'); ?></h1>
					</div>
				</div>
				<div class="pbmit-breadcrumb">
					<div class="pbmit-breadcrumb-inner">
						<span>
							<a title="" href="./" class="home"><span>Home</span></a>
						</span>
						<i class="pbmit-base-icon-arrow-right-2"></i>
						<span><span class="post-root post post-post current-item"> Products</span></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="page-content products">
	<?php if ($sr_page_override !== '') { ?>
		<?php echo $sr_page_override; ?>
	<?php } else { ?>
	<section class="section-xl products-hero" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_products_hero_title, ENT_QUOTES, 'UTF-8'); ?></h2>
				<?php if (trim($sr_products_hero_subtitle) !== '') { ?>
					<p class="mb-0"><?php echo $sr_products_hero_subtitle; ?></p>
				<?php } ?>
			</div>
		</div>
	</section>

	<section class="section-xl pt-0" data-aos="fade-up" data-aos-duration="800" data-aos-delay="80">
		<div class="container">
			<div class="row g-4 sr-product-grid">
				<?php if ($sr_products_items) { ?>
					<?php foreach ($sr_products_items as $idx => $p) { ?>
						<?php
						$slug = trim((string)($p['slug'] ?? ''));
						if ($slug === '') continue;
						$anchor = trim((string)($p['category_anchor'] ?? 'products'));
						$badge = trim((string)($p['badge_label'] ?? ''));
						$title = (string)($p['title'] ?? '');
						$range = trim((string)($p['range_label'] ?? ''));
						$desc = (string)($p['short_desc'] ?? '');
						$image = trim((string)($p['image'] ?? ''));
						if ($image === '') $image = 'images/homepage-1/service/service-img-01.jpg';
							$html = (string)($p['content'] ?? '');

						$badgeClass = 'sr-product-badge';
						$iconClass = 'pbmit-base-icon-lightening';
						if (stripos($anchor, 'residential') !== false) $iconClass = 'pbmit-base-icon-home';
						if (stripos($anchor, 'commercial') !== false) { $iconClass = 'pbmit-base-icon-city'; $badgeClass .= ' sr-product-badge--blue'; }
						if (stripos($anchor, 'industrial') !== false || stripos($anchor, 'ht') !== false) { $iconClass = 'pbmit-base-icon-budgeting'; $badgeClass .= ' sr-product-badge--teal'; }
						if (stripos($anchor, 'open') !== false || stripos($anchor, 'utility') !== false) { $iconClass = 'pbmit-base-icon-location-1'; $badgeClass .= ' sr-product-badge--orange'; }

						$points = preg_split('/\\r\\n|\\r|\\n/', (string)($p['bullets'] ?? ''));
						$points = array_values(array_filter(array_map('trim', $points), function ($x) { return $x !== ''; }));
						$points = array_slice($points, 0, 3);
						?>
						<div class="col-md-6 col-lg-3" id="<?php echo htmlspecialchars($anchor !== '' ? $anchor : ('product-' . (int)$idx), ENT_QUOTES, 'UTF-8'); ?>" data-aos="fade-up" data-aos-duration="800" data-aos-delay="<?php echo (int)min(360, $idx * 90); ?>">
							<div class="sr-product-card">
								<div class="sr-product-media">
									<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#productModal" data-product="<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>" data-title="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
										<img src="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
									</a>
									<div class="sr-product-icon"><i class="<?php echo htmlspecialchars($iconClass, ENT_QUOTES, 'UTF-8'); ?>"></i></div>
								</div>
								<?php if ($badge !== '') { ?><div class="<?php echo htmlspecialchars($badgeClass, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($badge, ENT_QUOTES, 'UTF-8'); ?></div><?php } ?>
								<h3 class="sr-product-title"><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#productModal" data-product="<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>" data-title="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></a></h3>
								<?php if ($range !== '') { ?><div class="sr-product-range"><i class="pbmit-base-icon-lightening"></i> <?php echo htmlspecialchars($range, ENT_QUOTES, 'UTF-8'); ?></div><?php } ?>
								<p class="sr-product-desc"><?php echo htmlspecialchars($desc, ENT_QUOTES, 'UTF-8'); ?></p>
								<?php if ($points) { ?>
									<ul class="sr-product-points">
										<?php foreach ($points as $pt) { ?>
											<li><i class="pbmit-base-icon-tick-1"></i><?php echo htmlspecialchars($pt, ENT_QUOTES, 'UTF-8'); ?></li>
										<?php } ?>
									</ul>
								<?php } ?>
								<button type="button" class="pbmit-btn sr-readmore" data-bs-toggle="modal" data-bs-target="#productModal" data-product="<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>" data-title="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
									<span class="pbmit-button-text">Read more</span>
								</button>
							</div>
							<div class="d-none" id="product-detail-<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>">
								<div class="sr-modal-top">
									<div class="sr-modal-media">
										<img src="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="sr-modal-meta">
										<?php if ($badge !== '') { ?><div class="<?php echo htmlspecialchars($badgeClass, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($badge, ENT_QUOTES, 'UTF-8'); ?></div><?php } ?>
										<?php if ($range !== '') { ?><div class="sr-modal-range"><i class="pbmit-base-icon-lightening"></i> Capacity Range: <strong><?php echo htmlspecialchars($range, ENT_QUOTES, 'UTF-8'); ?></strong></div><?php } ?>
									</div>
								</div>
								<?php if (trim($html) !== '') { ?>
									<?php echo $html; ?>
								<?php } else { ?>
									<p><?php echo htmlspecialchars($desc, ENT_QUOTES, 'UTF-8'); ?></p>
									<?php if ($points) { ?>
										<div class="sr-modal-section-title">Key Benefits</div>
										<ul class="sr-modal-list sr-icon-list">
											<?php foreach ($points as $pt) { ?>
												<li><i class="pbmit-base-icon-tick"></i><?php echo htmlspecialchars($pt, ENT_QUOTES, 'UTF-8'); ?></li>
											<?php } ?>
										</ul>
									<?php } ?>
								<?php } ?>
								<div class="sr-modal-cta">
									<a href="contact" class="pbmit-btn"><span class="pbmit-button-text">Request a proposal</span></a>
								</div>
							</div>
						</div>
					<?php } ?>
				<?php } else { ?>
				<div class="col-md-6 col-lg-3" id="residential" data-aos="fade-up" data-aos-duration="800" data-aos-delay="0">
					<div class="sr-product-card">
						<div class="sr-product-media">
							<img src="images/homepage-1/service/service-img-01.jpg" alt="Residential Solar Systems">
							<div class="sr-product-icon"><i class="pbmit-base-icon-home"></i></div>
						</div>
						<div class="sr-product-badge">FOR HOMES</div>
						<h3 class="sr-product-title">Residential Solar Systems</h3>
						<div class="sr-product-range"><i class="pbmit-base-icon-lightening"></i> 3 kW – 19 kW</div>
						<p class="sr-product-desc">Designed for Indian homes, custom-built to match your monthly electricity consumption, rooftop space, and budget.</p>
						<ul class="sr-product-points">
							<li><i class="pbmit-base-icon-tick-1"></i>Reduce electricity bill by 70–100%</li>
							<li><i class="pbmit-base-icon-tick-1"></i>Earn from net metering / excess export</li>
							<li><i class="pbmit-base-icon-tick-1"></i>25-year panel performance warranty</li>
						</ul>
						<button type="button" class="pbmit-btn sr-readmore" data-bs-toggle="modal" data-bs-target="#productModal" data-product="residential" data-title="Residential Solar Systems">
							<span class="pbmit-button-text">Read more</span>
						</button>
					</div>
					<div class="d-none" id="product-detail-residential">
						<div class="sr-modal-top">
							<div class="sr-modal-media">
								<img src="images/homepage-1/service/service-img-01.jpg" alt="Residential Solar Systems">
							</div>
							<div class="sr-modal-meta">
								<div class="sr-product-badge">FOR HOMES</div>
								<div class="sr-modal-range"><i class="pbmit-base-icon-lightening"></i> Capacity Range: <strong>3 kW – 19 kW</strong></div>
							</div>
						</div>
						<p>Designed for Indian homes, our residential solar systems are custom-built to match your monthly electricity consumption, rooftop space, and budget. With net metering support, you can sell excess energy back to the grid — making your home a small power plant.</p>
						<div class="sr-modal-section-title">Ideal for</div>
						<ul class="sr-modal-list sr-icon-list">
							<li><i class="pbmit-base-icon-check-mark"></i>Independent houses and bungalows</li>
							<li><i class="pbmit-base-icon-check-mark"></i>Row houses and villas</li>
							<li><i class="pbmit-base-icon-check-mark"></i>Housing societies (common areas)</li>
						</ul>
						<div class="sr-modal-section-title">Key Benefits</div>
						<ul class="sr-modal-list sr-icon-list">
							<li><i class="pbmit-base-icon-tick"></i>Reduce electricity bill by 70–100%</li>
							<li><i class="pbmit-base-icon-tick"></i>Earn from net metering / excess export</li>
							<li><i class="pbmit-base-icon-tick"></i>25-year panel performance warranty</li>
							<li><i class="pbmit-base-icon-tick"></i>Government subsidy eligibility under PM Surya Ghar Muft Bijli Yojana</li>
						</ul>
						<div class="sr-modal-cta">
							<div class="sr-modal-cta-label">Starting From</div>
							<a href="contact" class="pbmit-btn"><span class="pbmit-button-text">Contact us for current pricing</span></a>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-3" id="commercial" data-aos="fade-up" data-aos-duration="800" data-aos-delay="90">
					<div class="sr-product-card">
						<div class="sr-product-media">
							<img src="images/homepage-1/service/service-img-02.jpg" alt="Commercial Solar Systems">
							<div class="sr-product-icon"><i class="pbmit-base-icon-city"></i></div>
						</div>
						<div class="sr-product-badge sr-product-badge--blue">FOR BUSINESSES</div>
						<h3 class="sr-product-title">Commercial Solar Systems</h3>
						<div class="sr-product-range"><i class="pbmit-base-icon-lightening"></i> 20 kW – 200 kW</div>
						<p class="sr-product-desc">Scalable solar systems for offices, hospitals, hotels, schools, and retail with maximum savings on commercial tariff.</p>
						<ul class="sr-product-points">
							<li><i class="pbmit-base-icon-tick-1"></i>Reduce high commercial electricity tariff</li>
							<li><i class="pbmit-base-icon-tick-1"></i>Accelerated depreciation benefit (Year 1)</li>
							<li><i class="pbmit-base-icon-tick-1"></i>Monitoring portal for real-time tracking</li>
						</ul>
						<button type="button" class="pbmit-btn sr-readmore" data-bs-toggle="modal" data-bs-target="#productModal" data-product="commercial" data-title="Commercial Solar Systems">
							<span class="pbmit-button-text">Read more</span>
						</button>
					</div>
					<div class="d-none" id="product-detail-commercial">
						<div class="sr-modal-top">
							<div class="sr-modal-media">
								<img src="images/homepage-1/service/service-img-02.jpg" alt="Commercial Solar Systems">
							</div>
							<div class="sr-modal-meta">
								<div class="sr-product-badge sr-product-badge--blue">FOR BUSINESSES</div>
								<div class="sr-modal-range"><i class="pbmit-base-icon-lightening"></i> Capacity Range: <strong>20 kW – 200 kW</strong></div>
							</div>
						</div>
						<p>Scalable, high-performance solar systems designed for commercial establishments including offices, hotels, hospitals, educational institutions, and retail businesses. Our commercial systems are engineered to align with your load profile and maximise savings on your commercial tariff.</p>
						<div class="sr-modal-section-title">Ideal for</div>
						<ul class="sr-modal-list sr-icon-list">
							<li><i class="pbmit-base-icon-check-mark"></i>Offices, IT parks, and coworking spaces</li>
							<li><i class="pbmit-base-icon-check-mark"></i>Hospitals, hotels, and schools</li>
							<li><i class="pbmit-base-icon-check-mark"></i>Shopping centres and malls</li>
							<li><i class="pbmit-base-icon-check-mark"></i>Warehouses and cold storage facilities</li>
						</ul>
						<div class="sr-modal-section-title">Key Benefits</div>
						<ul class="sr-modal-list sr-icon-list">
							<li><i class="pbmit-base-icon-tick"></i>Significant reduction in commercial electricity tariff</li>
							<li><i class="pbmit-base-icon-tick"></i>Accelerated depreciation benefit (40% in Year 1) for businesses</li>
							<li><i class="pbmit-base-icon-tick"></i>Scalable design — easy to expand as your load grows</li>
							<li><i class="pbmit-base-icon-tick"></i>Monitoring portal for real-time generation tracking</li>
						</ul>
						<div class="sr-modal-cta">
							<a href="contact" class="pbmit-btn"><span class="pbmit-button-text">Talk to our team</span></a>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-3" id="ht-consumer" data-aos="fade-up" data-aos-duration="800" data-aos-delay="180">
					<div class="sr-product-card">
						<div class="sr-product-media">
							<img src="images/homepage-1/service/service-img-03.jpg" alt="HT Consumer Solar Projects">
							<div class="sr-product-icon"><i class="pbmit-base-icon-budgeting"></i></div>
						</div>
						<div class="sr-product-badge sr-product-badge--teal">FOR INDUSTRY</div>
						<h3 class="sr-product-title">HT Consumer Solar Projects</h3>
						<div class="sr-product-range"><i class="pbmit-base-icon-lightening"></i> 200 kW – 990 kW</div>
						<p class="sr-product-desc">Industrial-grade systems for HT consumers to offset demand charges and deliver fast payback with captive consumption.</p>
						<ul class="sr-product-points">
							<li><i class="pbmit-base-icon-tick-1"></i>Reduce HT tariff and demand charges</li>
							<li><i class="pbmit-base-icon-tick-1"></i>Captive consumption model</li>
							<li><i class="pbmit-base-icon-tick-1"></i>Dedicated project manager</li>
						</ul>
						<button type="button" class="pbmit-btn sr-readmore" data-bs-toggle="modal" data-bs-target="#productModal" data-product="ht" data-title="HT Consumer Solar Projects">
							<span class="pbmit-button-text">Read more</span>
						</button>
					</div>
					<div class="d-none" id="product-detail-ht">
						<div class="sr-modal-top">
							<div class="sr-modal-media">
								<img src="images/homepage-1/service/service-img-03.jpg" alt="HT Consumer Solar Projects">
							</div>
							<div class="sr-modal-meta">
								<div class="sr-product-badge sr-product-badge--teal">FOR INDUSTRY</div>
								<div class="sr-modal-range"><i class="pbmit-base-icon-lightening"></i> Capacity Range: <strong>200 kW – 990 kW</strong></div>
							</div>
						</div>
						<p>High-tension electricity consumers — factories, large manufacturing plants, processing units — face the highest power costs. Our HT consumer projects are designed to offset a significant portion of your HT tariff with clean solar energy, delivering payback in as little as 3–5 years.</p>
						<div class="sr-modal-section-title">Ideal for</div>
						<ul class="sr-modal-list sr-icon-list">
							<li><i class="pbmit-base-icon-check-mark"></i>Factories and manufacturing units</li>
							<li><i class="pbmit-base-icon-check-mark"></i>Food processing and agro-industrial plants</li>
							<li><i class="pbmit-base-icon-check-mark"></i>Textile mills and engineering companies</li>
							<li><i class="pbmit-base-icon-check-mark"></i>Large pumping stations and water treatment plants</li>
						</ul>
						<div class="sr-modal-section-title">Key Benefits</div>
						<ul class="sr-modal-list sr-icon-list">
							<li><i class="pbmit-base-icon-tick"></i>Substantial reduction in HT tariff and demand charges</li>
							<li><i class="pbmit-base-icon-tick"></i>Captive consumption model — no export dependency</li>
							<li><i class="pbmit-base-icon-tick"></i>Robust industrial-grade equipment for harsh environments</li>
							<li><i class="pbmit-base-icon-tick"></i>Dedicated project manager for seamless execution</li>
						</ul>
						<div class="sr-modal-cta">
							<a href="contact" class="pbmit-btn"><span class="pbmit-button-text">Request a proposal</span></a>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-3" id="open-access" data-aos="fade-up" data-aos-duration="800" data-aos-delay="270">
					<div class="sr-product-card">
						<div class="sr-product-media">
							<img src="images/homepage-1/service/service-img-04.jpg" alt="Open Access Solar Projects">
							<div class="sr-product-icon"><i class="pbmit-base-icon-location-1"></i></div>
						</div>
						<div class="sr-product-badge sr-product-badge--orange">LARGE SCALE</div>
						<h3 class="sr-product-title">Open Access Solar Projects</h3>
						<div class="sr-product-range"><i class="pbmit-base-icon-lightening"></i> 1 MW – 20 MW</div>
						<p class="sr-product-desc">Large-scale clean energy through solar parks and direct PPAs with full EPC lifecycle and regulatory handling.</p>
						<ul class="sr-product-points">
							<li><i class="pbmit-base-icon-tick-1"></i>Typically 30–50% savings vs grid</li>
							<li><i class="pbmit-base-icon-tick-1"></i>Long-term fixed PPA rates</li>
							<li><i class="pbmit-base-icon-tick-1"></i>End-to-end regulatory approvals</li>
						</ul>
						<button type="button" class="pbmit-btn sr-readmore" data-bs-toggle="modal" data-bs-target="#productModal" data-product="oa" data-title="Open Access Solar Projects">
							<span class="pbmit-button-text">Read more</span>
						</button>
					</div>
					<div class="d-none" id="product-detail-oa">
						<div class="sr-modal-top">
							<div class="sr-modal-media">
								<img src="images/homepage-1/service/service-img-04.jpg" alt="Open Access Solar Projects">
							</div>
							<div class="sr-modal-meta">
								<div class="sr-product-badge sr-product-badge--orange">LARGE SCALE</div>
								<div class="sr-modal-range"><i class="pbmit-base-icon-lightening"></i> Capacity Range: <strong>1 MW – 20 MW</strong></div>
							</div>
						</div>
						<p>For organisations with high power requirements — or developers looking to build solar infrastructure — our Open Access solar projects deliver large-scale, cost-effective clean energy through solar parks and direct Power Purchase Agreements (PPAs). We manage the full EPC lifecycle and regulatory process.</p>
						<div class="sr-modal-section-title">Ideal for</div>
						<ul class="sr-modal-list sr-icon-list">
							<li><i class="pbmit-base-icon-check-mark"></i>Large industrial groups and conglomerates</li>
							<li><i class="pbmit-base-icon-check-mark"></i>Solar park developers seeking land and infrastructure</li>
							<li><i class="pbmit-base-icon-check-mark"></i>Government or institutional bulk energy buyers</li>
							<li><i class="pbmit-base-icon-check-mark"></i>Real estate developers building green-rated projects</li>
						</ul>
						<div class="sr-modal-section-title">Key Benefits</div>
						<ul class="sr-modal-list sr-icon-list">
							<li><i class="pbmit-base-icon-tick"></i>Competitive solar power tariff vs. grid — typically 30–50% savings</li>
							<li><i class="pbmit-base-icon-tick"></i>Long-term energy price certainty through fixed PPA rates</li>
							<li><i class="pbmit-base-icon-tick"></i>Full regulatory handling — open access approvals, wheeling charges, banking</li>
							<li><i class="pbmit-base-icon-tick"></i>Shovel-ready infrastructure with grid connectivity</li>
						</ul>
						<div class="sr-modal-cta">
							<a href="contact" class="pbmit-btn"><span class="pbmit-button-text">Discuss Open Access</span></a>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</section>

	<style>
		#productModalBody img,
		#productModalBody figure img {
			max-width: 100%;
			height: auto;
			display: block;
			margin: 18px 0;
			border-radius: 18px;
			box-shadow: 0 18px 40px rgba(0, 0, 0, .10);
			border: 1px solid rgba(10, 25, 38, .10);
			background: #fff;
		}
		#productModalBody figure { margin: 18px 0; }
		#productModalBody figcaption { margin-top: 10px; font-weight: 600; color: rgba(10, 25, 38, .70); }
	</style>

	<div class="modal fade sr-modal" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="productModalLabel">Product</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body" id="productModalBody"></div>
			</div>
		</div>
	</div>

	<script>
		(function () {
			var modalEl = document.getElementById('productModal');
			if (!modalEl) return;
			modalEl.addEventListener('show.bs.modal', function (event) {
				var btn = event.relatedTarget;
				if (!btn) return;
				var key = btn.getAttribute('data-product');
				var title = btn.getAttribute('data-title') || 'Product';
				var source = document.getElementById('product-detail-' + key);
				var body = document.getElementById('productModalBody');
				var titleEl = document.getElementById('productModalLabel');
				if (titleEl) titleEl.textContent = title;
				if (body) body.innerHTML = source ? source.innerHTML : '';
			});

			var openKey = <?php echo json_encode($sr_open_product, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
			if (openKey) {
				var trigger = document.querySelector('[data-bs-target="#productModal"][data-product="' + openKey.replace(/"/g, '\\"') + '"]');
				if (trigger) {
					trigger.click();
					return;
				}
				var source = document.getElementById('product-detail-' + openKey);
				if (source && window.bootstrap && bootstrap.Modal) {
					var body = document.getElementById('productModalBody');
					var titleEl = document.getElementById('productModalLabel');
					if (titleEl) titleEl.textContent = 'Product';
					if (body) body.innerHTML = source.innerHTML;
					var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
					modal.show();
				}
			}
		})();
	</script>
	<?php } ?>
</div>
<?php include 'includes/footer.php'; ?>
