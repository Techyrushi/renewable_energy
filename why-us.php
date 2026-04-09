<?php include 'includes/header.php'; ?>
<?php
$sr_page = sr_cms_page_get('why-us');
$sr_hero_title = $sr_page && trim((string)$sr_page['hero_title']) !== '' ? (string)$sr_page['hero_title'] : 'The Shivanjali Difference';
$sr_hero_subtitle = $sr_page && trim((string)$sr_page['hero_subtitle']) !== '' ? (string)$sr_page['hero_subtitle'] : 'We don&#8217;t just install solar panels. We build long-term energy partnerships that deliver measurable results.';
$sr_banner_image = $sr_page && trim((string)($sr_page['banner_image'] ?? '')) !== '' ? (string)$sr_page['banner_image'] : '';
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
	<section class="section-xl pbmit-bg-color-white sr-why-diff" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title">Why Clients Choose Us</h2>
			</div>
			<div class="row g-4 pt-3">
				<div class="col-md-6 col-lg-4">
					<div class="sr-diff-card">
						<div class="sr-diff-icon"><i class="pbmit-base-icon-tick-1"></i></div>
						<h3 class="sr-diff-title">Proven Experience</h3>
						<p class="sr-diff-desc">Years of hands-on expertise in solar EPC across Maharashtra with a
							growing portfolio of successful projects.</p>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="sr-diff-card">
						<div class="sr-diff-icon"><i class="pbmit-base-icon-user-2"></i></div>
						<h3 class="sr-diff-title">Expert Team</h3>
						<p class="sr-diff-desc">A multidisciplinary team of certified engineers, experienced
							technicians, and knowledgeable consultants committed to project excellence.</p>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="sr-diff-card">
						<div class="sr-diff-icon"><i class="pbmit-base-icon-fast-delivery"></i></div>
						<h3 class="sr-diff-title">End-to-End Service</h3>
						<p class="sr-diff-desc">We manage every step from design and procurement to installation, grid
							connectivity, and lifetime maintenance.</p>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="sr-diff-card">
						<div class="sr-diff-icon"><i class="pbmit-base-icon-check-square-regular"></i></div>
						<h3 class="sr-diff-title">Certified Quality</h3>
						<p class="sr-diff-desc">All our products meet stringent national and international quality and
							safety standards, with Tier-1 certified components only.</p>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="sr-diff-card">
						<div class="sr-diff-icon"><i class="pbmit-base-icon-method-draw-image"></i></div>
						<h3 class="sr-diff-title">Dedicated Project Design Team</h3>
						<p class="sr-diff-desc">Specialised in solar project design, planning, and architectural
							integration for optimal performance and aesthetics.</p>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="sr-diff-card">
						<div class="sr-diff-icon"><i class="pbmit-base-icon-support"></i></div>
						<h3 class="sr-diff-title">Comprehensive Warranty &amp; Support</h3>
						<p class="sr-diff-desc">Full after-sales support including preventive maintenance, performance
							monitoring, and warranty services.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section-xl sr-why-tech" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title">Backed by Technology</h2>
			</div>
			<div class="row g-4 pt-3">
				<div class="col-md-6 col-lg-4">
					<div class="sr-tech-card">
						<div class="sr-tech-icon"><i class="pbmit-base-icon-lightening"></i></div>
						<h3 class="sr-tech-title">Innovative Solutions</h3>
						<p class="sr-tech-desc">We leverage the latest advancements in solar technology, including
							bifacial panels, smart inverters, and energy management systems, to deliver unmatched
							performance.</p>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="sr-tech-card">
						<div class="sr-tech-icon"><i class="pbmit-base-icon-search"></i></div>
						<h3 class="sr-tech-title">R&amp;D Focus</h3>
						<p class="sr-tech-desc">Continuous investment in research and development to improve system
							efficiency, reliability, and integration with emerging energy storage technologies.</p>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="sr-tech-card">
						<div class="sr-tech-icon"><i class="pbmit-base-icon-checked"></i></div>
						<h3 class="sr-tech-title">Certified Products</h3>
						<p class="sr-tech-desc">All equipment and installations meet stringent industry certifications
							for quality, safety, and long-term performance.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section-xl sr-why-testimonials" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title">Client Success Stories</h2>
			</div>
			<div class="swiper-slider" data-autoplay="true" data-loop="true" data-dots="true" data-arrows="false"
				data-columns="1" data-margin="30" data-effect="slide">
				<div class="swiper-wrapper">
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
				</div>
			</div>
		</div>
	</section>

	<section class="section-xl sr-why-impact" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title">Our Environmental Impact</h2>
			</div>
			<div class="row g-4 pt-3">
				<div class="col-md-6 col-lg-4">
					<div class="sr-impact-card">
						<div class="sr-impact-top">
							<div class="sr-impact-icon"><i class="pbmit-base-icon-lightening"></i></div>
							<div class="sr-impact-label">Solar Capacity Installed</div>
						</div>
						<div class="sr-impact-value">
							<span class="sr-impact-number pbmit-number-rotate numinate" data-appear-animation="animateDigits"
								data-from="0" data-to="20" data-interval="1" data-before="" data-before-style="" data-after=""
								data-after-style="">20</span>
							<span class="sr-impact-unit">MW+</span>
						</div>
						<p class="sr-impact-desc">Harnessing solar energy to reduce dependency on fossil fuels and lower
							the carbon footprint of our clients across Maharashtra.</p>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="sr-impact-card">
						<div class="sr-impact-top">
							<div class="sr-impact-icon"><i class="pbmit-base-icon-check-mark"></i></div>
							<div class="sr-impact-label">Projects Completed</div>
						</div>
						<div class="sr-impact-value">
							<span class="sr-impact-number pbmit-number-rotate numinate" data-appear-animation="animateDigits"
								data-from="0" data-to="500" data-interval="5" data-before="" data-before-style="" data-after=""
								data-after-style="">500</span>
							<span class="sr-impact-unit">+</span>
						</div>
						<p class="sr-impact-desc">Our installed systems collectively save crores of rupees in electricity
							bills for residential, commercial, and industrial customers every year.</p>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="sr-impact-card">
						<div class="sr-impact-top">
							<div class="sr-impact-icon"><i class="pbmit-base-icon-customer"></i></div>
							<div class="sr-impact-label">Trusted Customers</div>
						</div>
						<div class="sr-impact-value">
							<span class="sr-impact-number pbmit-number-rotate numinate" data-appear-animation="animateDigits"
								data-from="0" data-to="2386" data-interval="5" data-before="" data-before-style="" data-after=""
								data-after-style="">2386</span>
							<span class="sr-impact-unit">+</span>
						</div>
						<p class="sr-impact-desc">By enabling widespread solar adoption, we play a vital role in
							India&#8217;s national clean energy transition and Net Zero goals.</p>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<!-- Page Content End -->

<?php include 'includes/footer.php'; ?>	
