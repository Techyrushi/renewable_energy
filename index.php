<?php include 'includes/header.php'; ?>
<div class="pbmit-slider-area pbmit-slider-two">
	<div class="swiper-slider" data-autoplay="true" data-loop="true" data-dots="false" data-arrows="false"
		data-columns="1" data-margin="0" data-effect="fade">
		<div class="swiper-wrapper">
			<?php
			$bannerDir = __DIR__ . '/images/banner-slider-img';
			$patterns = ['Slider*.jpg','Slider*.jpeg','Slider*.png','Slider*.JPG','Slider*.JPEG','Slider*.PNG'];
			$files = [];
			foreach ($patterns as $p) {
				$files = array_merge($files, glob($bannerDir . '/' . $p, GLOB_BRACE));
			}
			$files = array_unique($files);
			natsort($files);
			if (empty($files)) {
				$files = [__DIR__ . '/images/banner-slider-img/Slider02-1.jpg'];
			}
			foreach ($files as $filePath):
				$rel = 'images/banner-slider-img/' . basename($filePath);
			?>
			<div class="swiper-slide">
				<div class="pbmit-slider-item" style="position: relative;">
					<div class="pbmit-slider-bg" style="background-image: url(<?php echo htmlspecialchars($rel, ENT_QUOTES); ?>);"></div>
					<div class="slider-gradient"></div>
					<img src="images/logo_shivanjali.png" class="slider-logo-overlay" alt="Shivanjali Renewables">
					<div class="container" style="position: relative; z-index: 1;">
						<div class="row g-0">
							<div class="col-md-12 col-lg-7">
								<div class="pbmit-slider-block" style="background: transparent !important; backdrop-filter: none; padding: 0; width: 100%; opacity: 1; transform: none;">
									<div class="pbmit-slider-content" style="background: transparent; padding: 0; width: 100%; max-width: 820px;">
										<h5 class="pbmit-slider-subtitle transform-top transform-delay-1" style="color: var(--pbmit-white-color); border-color: rgba(255,255,255,0.7);">
											Maharashtra’s Trusted Solar EPC Partner
										</h5>
										<h1 class="pbmit-slider-title transform-left transform-delay-2" style="color: var(--pbmit-white-color); font-size: 62px; line-height: 68px; margin-bottom: 18px;">
											Powering a Greener Tomorrow — One Solar Panel at a Time
										</h1>
										<p class="transform-left transform-delay-3" style="color: rgba(255,255,255,0.92); font-size: 18px; line-height: 30px; margin-bottom: 28px; max-width: 720px;">
											Shivanjali Renewables is Maharashtra's trusted Solar EPC partner for homes,
											businesses, industries, and large-scale solar parks. Clean energy. Real
											savings. Lasting impact.
										</p>
										<div class="pbmit-button d-flex flex-wrap align-items-center gap-3">
											<div class="transform-bottom transform-delay-4">
												<a href="/contact" class="pbmit-btn">
													<span class="pbmit-button-text">Get a Free Solar Quote</span>
												</a>
											</div>
											<div class="transform-bottom transform-delay-5">
												<a href="/services" class="pbmit-btn outline" style="border-color: rgba(255,255,255,0.85); color: var(--pbmit-white-color);">
													<span class="pbmit-button-text">Explore Our Services</span>
												</a>
											</div>
										</div>
										<div class="d-block d-lg-none" style="height: 22px;"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
</header>
<!-- Header Main Area End Here -->
<!-- Page Content -->
<div class="page-content">

	<!-- Stats / Trust Bar Start -->
	<section class="fid-section-two home-trustbar" data-aos="fade-up" data-aos-duration="800">
		<div class="container-fluid p-0">
			<div class="row g-4">
				<div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="0">
					<div class="fid-style-wrap">
						<div class="pbminfotech-ele-fid-style-2">
							<div class="pbmit-fld-contents">
								<div class="pbmit-fld-wrap">
									<div class="pbmit-fid-icon-title">
										<span class="pbmit-fid-title">Projects<br>Completed</span>
									</div>
									<h4 class="pbmit-fid-inner">
										<span class="pbmit-fid-before"></span>
										<span class="pbmit-number-rotate numinate" data-appear-animation="animateDigits"
											data-from="0" data-to="500" data-interval="5" data-before=""
											data-before-style="" data-after="" data-after-style="">500</span>
										<span class="pbmit-fid"><span>+</span></span>
									</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
					<div class="fid-style-wrap">
						<div class="pbminfotech-ele-fid-style-2">
							<div class="pbmit-fld-contents">
								<div class="pbmit-fld-wrap">
									<div class="pbmit-fid-icon-title">
										<span class="pbmit-fid-title">Solar Capacity<br>Installed</span>
									</div>
									<h4 class="pbmit-fid-inner">
										<span class="pbmit-fid-before"></span>
										<span class="pbmit-number-rotate numinate" data-appear-animation="animateDigits"
											data-from="0" data-to="20" data-interval="1" data-before=""
											data-before-style="" data-after="" data-after-style="">20</span>
										<span class="pbmit-fid"><span> MW+</span></span>
									</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
					<div class="fid-style-wrap">
						<div class="pbminfotech-ele-fid-style-2">
							<div class="pbmit-fld-contents">
								<div class="pbmit-fld-wrap">
									<div class="pbmit-fid-icon-title">
										<span class="pbmit-fid-title">System<br>Range</span>
									</div>
									<h4 class="pbmit-fid-inner">
										<span class="pbmit-fid-before"></span>
										<span class="pbmit-number-rotate numinate" data-appear-animation="animateDigits"
											data-from="0" data-to="3" data-interval="1" data-before=""
											data-before-style="" data-after="" data-after-style="">3</span>
										<span class="pbmit-fid"><span> kW – 20 MW</span></span>
									</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
					<div class="fid-style-wrap">
						<div class="pbminfotech-ele-fid-style-2">
							<div class="pbmit-fld-contents">
								<div class="pbmit-fld-wrap">
									<div class="pbmit-fid-icon-title">
										<span class="pbmit-fid-title">After-Sales<br>Support</span>
									</div>
									<h4 class="pbmit-fid-inner">
										<span class="pbmit-fid-before"></span>
										<span class="pbmit-number-rotate numinate" data-appear-animation="animateDigits"
											data-from="0" data-to="100" data-interval="2" data-before=""
											data-before-style="" data-after="" data-after-style="">100</span>
										<span class="pbmit-fid"><span>%</span></span>
									</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Stats / Trust Bar End -->

	<!-- Service Start -->
	<section class="service-section-two home-services-modern">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h4 class="pbmit-subtitle">Our Services</h4>
				<h2 class="pbmit-title">Comprehensive solar solutions from concept to completion</h2>
			</div>
			<div class="row g-4">
				<article class="pbmit-service-style-4 col-md-6 col-lg-4 col-xl-3" data-aos="fade-up"
					data-aos-duration="800" data-aos-delay="0">
					<div class="pbminfotech-post-item">
						<div class="pbmit-box-content-wrap">
							<div class="pbmit-content-box">
								<div class="pbminfotech-box-number">01</div>
								<div class="pbmit-service-icon">
									<svg id="Layer_12" enable-background="new 0 0 512 512" viewBox="0 0 512 512"
										xmlns="http://www.w3.org/2000/svg">
										<g>
											<g>
												<g>
													<path
														d="m362.7 325.6c-2 0-4-1.3-4.6-3.4l-90.8-274.7c-.8-2.6.5-5.3 3.1-6.2 2.6-.8 5.3.5 6.2 3.1l90.8 274.7c.8 2.6-.5 5.3-3.1 6.2-.6.2-1.1.3-1.6.3z">
													</path>
												</g>
												<g>
													<path
														d="m290.1 325.8c-2.1 0-4-1.3-4.6-3.4l-90.8-274.9c-.8-2.6.5-5.3 3.1-6.2 2.6-.8 5.3.5 6.2 3.1l90.8 274.9c.8 2.6-.5 5.3-3.1 6.2-.6.2-1.1.3-1.6.3z">
													</path>
												</g>
												<g>
													<path
														d="m217.5 326c-2.1 0-4-1.3-4.6-3.4l-90.9-275.1c-.8-2.6.5-5.3 3.1-6.2 2.6-.8 5.3.5 6.2 3.1l90.9 275.1c.8 2.6-.5 5.3-3.1 6.2-.6.2-1.1.3-1.6.3z">
													</path>
												</g>
												<g>
													<g>
														<path
															d="m374.3 142.6h-217.4c-2.7 0-4.9-2.2-4.9-4.9s2.2-4.9 4.9-4.9h217.4c2.7 0 4.9 2.2 4.9 4.9s-2.2 4.9-4.9 4.9z">
														</path>
													</g>
													<g>
														<path
															d="m404 234.4h-216.8c-2.7 0-4.9-2.2-4.9-4.9s2.2-4.9 4.9-4.9h216.8c2.7 0 4.9 2.2 4.9 4.9-.1 2.7-2.2 4.9-4.9 4.9z">
														</path>
													</g>
												</g>
												<g>
													<path
														d="m188.8 326c-5.3 0-10.1-3.4-11.9-8.6v-.1l-82.9-259.5c-1.3-3.9-.7-8.1 1.7-11.4 2.4-3.4 6.4-5.4 10.5-5.4h232.4c5.7 0 10.7 3.7 12.3 9l83.7 258.4c1.3 3.9.7 8.1-1.7 11.5s-6.4 5.4-10.5 5.4l-233 .7c-.2 0-.4 0-.6 0zm-2.6-11.9c.4 1 1.3 2.1 2.8 2.1h.3l233.2-.7c1.1 0 2-.5 2.6-1.3.3-.5.8-1.4.4-2.7l-83.8-258.5v-.1c-.4-1.2-1.6-2.1-2.9-2.1h-232.5c-1.1 0-2 .5-2.6 1.3-.3.5-.8 1.4-.4 2.7z">
													</path>
												</g>
												<g>
													<path
														d="m161.9 439.2c-2.7 0-4.9-2.2-4.9-4.9v-180.1c0-2.7 2.2-4.9 4.9-4.9s4.9 2.2 4.9 4.9v180.1c0 2.8-2.2 4.9-4.9 4.9z">
													</path>
												</g>
												<g>
													<path
														d="m123.8 439.2c-2.7 0-4.9-2.2-4.9-4.9v-299.4c0-2.7 2.2-4.9 4.9-4.9s4.9 2.2 4.9 4.9v299.4c0 2.8-2.2 4.9-4.9 4.9z">
													</path>
												</g>
												<g>
													<path
														d="m200.4 471h-114.9c-4.8 0-8.9-4.1-8.9-8.9v-8.3c0-13.3 10.8-24.1 24.1-24.1h84.3c13.3 0 24.1 10.8 24.1 24.1v8.3c0 4.7-3.8 8.7-8.5 8.9-.1 0-.1 0-.2 0zm-114.1-9.8h113v-7.4c0-7.9-6.4-14.4-14.4-14.4h-84.2c-7.9 0-14.4 6.4-14.4 14.4z">
													</path>
												</g>
											</g>
											<g>
												<path
													d="m426.6 471h-116c-4.9 0-8.8-3.9-8.8-8.8v-76c0-4.9 3.9-8.8 8.8-8.8h116c4.9 0 8.8 3.9 8.8 8.8v76c0 4.9-3.9 8.8-8.8 8.8zm-115-9.8h114v-74.1h-114z">
												</path>
											</g>
											<g>
												<path
													d="m368.7 451.9c-.7 0-1.4-.2-2.1-.5-2.4-1.2-3.5-4.1-2.3-6.5l7-14.5h-13c-1.7 0-3.3-.9-4.2-2.4s-.9-3.3-.1-4.8l13.4-24.2c1.3-2.4 4.3-3.2 6.6-1.9 2.4 1.3 3.2 4.3 1.9 6.6l-9.4 17h12.5c1.7 0 3.2.9 4.1 2.3s1 3.2.3 4.7l-10.4 21.5c-.8 1.7-2.5 2.7-4.3 2.7z">
												</path>
											</g>
											<g>
												<g>
													<path
														d="m347 387.2c-2.7 0-4.9-2.2-4.9-4.9v-14.1h-14.5v14.1c0 2.7-2.2 4.9-4.9 4.9s-4.9-2.2-4.9-4.9v-16.7c0-3.9 3.2-7.1 7.1-7.1h19.7c3.9 0 7.1 3.2 7.1 7.1v16.7c.2 2.7-2 4.9-4.7 4.9z">
													</path>
												</g>
												<g>
													<path
														d="m414.6 387.2c-2.7 0-4.9-2.2-4.9-4.9v-14.1h-14.5v14.1c0 2.7-2.2 4.9-4.9 4.9s-4.9-2.2-4.9-4.9v-16.7c0-3.9 3.2-7.1 7.1-7.1h19.7c3.9 0 7.1 3.2 7.1 7.1v16.7c.2 2.7-2 4.9-4.7 4.9z">
													</path>
												</g>
											</g>
											<g>
												<path
													d="m306.8 429.2h-58c-7.9 0-14.4-6.4-14.4-14.4v-28.7c0-2.5-2.1-4.6-4.6-4.6h-68c-2.7 0-4.9-2.2-4.9-4.9s2.2-4.9 4.9-4.9h68c7.9 0 14.4 6.4 14.4 14.4v28.7c0 2.5 2.1 4.6 4.6 4.6h58c2.7 0 4.9 2.2 4.9 4.9s-2.2 4.9-4.9 4.9z">
												</path>
											</g>
										</g>
									</svg>
								</div>
								<h3 class="pbmit-service-title">
									<a href="/services#solar-installation">Solar Module &amp; System Installation</a>
								</h3>
								<p class="home-service-desc">End-to-end installation for homes, businesses, and
									industries</p>
							</div>
							<div class="pbmit-service-image-wrapper">
								<div class="pbmit-featured-img-wrapper">
									<div class="pbmit-featured-wrapper">
										<img src="images/homepage-2/service/service-img-01.jpg" class="" alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</article>
				<article class="pbmit-service-style-4 col-md-6 col-lg-4 col-xl-3" data-aos="fade-up"
					data-aos-duration="800" data-aos-delay="100">
					<div class="pbminfotech-post-item">
						<div class="pbmit-box-content-wrap">
							<div class="pbmit-content-box">
								<div class="pbminfotech-box-number">02</div>
								<div class="pbmit-service-icon">
									<svg id="Layer_50" enable-background="new 0 0 512 512" viewBox="0 0 512 512"
										xmlns="http://www.w3.org/2000/svg">
										<g>
											<g>
												<g>
													<g>
														<g>
															<path
																d="m227.5 441.7c-2.7 0-4.9-2.2-4.9-4.9v-26.6c0-33.3-11.6-65.7-32.7-91.3l-58.4-70.8c-3.2-3.1-7.6-4.5-11.7-3.9h-.3c-.3 0-.8.1-1 .2-.1 0-.2.1-.4.1-1.5.4-3 1-4.1 1.8-6.2 4.3-8 12.5-4.1 18.8l47.2 76.2c1.4 2.3.7 5.3-1.6 6.7s-5.3.7-6.7-1.6l-47.2-76.2c-6.6-10.7-3.7-24.7 6.7-31.9 2.1-1.5 4.6-2.7 7.3-3.3.9-.3 1.8-.4 2.5-.5h.2c7.1-1.1 14.7 1.4 20.1 6.7.1.1.2.3.4.4l58.5 71c22.5 27.4 34.9 62 34.9 97.5v26.6c.1 2.8-2 5-4.7 5z">
															</path>
														</g>
													</g>
													<g>
														<g>
															<path
																d="m238.6 471h-114.6c-4.4 0-7.9-3.6-7.9-7.9v-17.8c0-7.4 6-13.4 13.4-13.4h103.7c7.4 0 13.4 6 13.4 13.4v17.8c-.1 4.3-3.6 7.9-8 7.9zm-112.8-9.8h110.9v-15.9c0-2-1.6-3.6-3.6-3.6h-103.7c-2 0-3.6 1.6-3.6 3.6z">
															</path>
														</g>
													</g>
													<g>
														<path
															d="m135 441.7c-2.7 0-4.9-2.2-4.9-4.9 0-18.3-6.6-36.1-18.5-50.1l-53.2-62.5c-.1-.1-.1-.2-.2-.3-12.7-17.1-17.6-38.8-13.6-59.5l29.2-150.3c1.3-6.8 5.3-12.7 11.1-16.6 5.9-3.9 12.8-5.3 19.7-3.9 9.6 2 17.1 9.1 19.7 18.6.7 2 1 4.4 1 7.1v.1l-3.5 120.6c-.1 2.7-2.3 4.8-5 4.7s-4.8-2.3-4.7-5l3.6-120.4c0-1.6-.2-3-.5-4.1 0-.1-.1-.2-.1-.3-1.6-6-6.3-10.5-12.3-11.7-4.2-.9-8.6 0-12.3 2.4s-6.1 6.1-7 10.3l-29.2 150.4c-3.5 18 .8 36.8 11.7 51.6l53.1 62.4c13.4 15.8 20.8 35.8 20.8 56.4 0 2.8-2.2 5-4.9 5z">
														</path>
													</g>
												</g>
												<g>
													<g>
														<g>
															<path
																d="m284.7 441.7c-2.7 0-4.9-2.2-4.9-4.9v-26.6c0-35.5 12.4-70.2 34.9-97.5l58.5-71c.1-.1.2-.2.3-.3 5.4-5.4 12.9-7.9 20.2-6.7h.3c.5.1 1.1.2 1.8.3.1 0 .3 0 .4.1 2.6.6 5.1 1.8 7.5 3.4 10.4 7 13.4 21 6.8 31.9l-47.3 76.2c-1.4 2.3-4.4 3-6.7 1.6s-3-4.4-1.6-6.7l47.3-76.1c3.9-6.3 2.1-14.6-4-18.7-1.3-.9-2.6-1.5-3.9-1.9-.2 0-.4-.1-.6-.1-.3-.1-.7-.2-1.1-.2h-.3c-4.1-.6-8.5.8-11.6 3.9l-58.4 70.9c-21.1 25.6-32.7 58-32.7 91.3v26.6c0 2.3-2.2 4.5-4.9 4.5z">
															</path>
														</g>
													</g>
													<g>
														<g>
															<path
																d="m388.1 471h-114.4c-4.4 0-7.9-3.6-7.9-7.9v-17.8c0-7.4 6-13.4 13.4-13.4h103.7c7.4 0 13.4 6 13.4 13.4v17.8.6c-.6 4.1-4.1 7.3-8.2 7.3zm-112.6-9.8h110.9v-15.9c0-2-1.6-3.6-3.6-3.6h-103.6c-2 0-3.6 1.6-3.6 3.6v15.9z">
															</path>
														</g>
													</g>
													<g>
														<path
															d="m377 441.7c-2.7 0-4.9-2.2-4.9-4.9 0-20.6 7.4-40.6 20.8-56.4l53.1-62.4c11-14.8 15.2-33.7 11.7-51.6l-29.3-150.4c-.8-4.2-3.3-7.9-6.9-10.3-3.7-2.4-8-3.3-12.3-2.4-5.9 1.2-10.7 5.7-12.3 11.7-.4 1.4-.6 3-.6 4.3l3.6 120.3v.1.1c0 2.7-2.2 4.9-4.9 4.9s-4.9-2.2-4.9-4.9l-3.6-120.3v-.1c0-2.3.3-4.7 1-7 2.6-9.5 10.2-16.7 19.7-18.7 6.9-1.4 13.9 0 19.7 3.9s9.8 9.8 11.1 16.6l29.3 150.3c4 20.7-.9 42.4-13.6 59.5-.1.1-.1.2-.2.3l-53.2 62.5c-11.9 14-18.5 31.8-18.5 50.1.1 2.6-2.1 4.8-4.8 4.8z">
														</path>
													</g>
												</g>
											</g>
											<g>
												<path
													d="m256.5 273.4c-64.1 0-116.2-52.1-116.2-116.2s52.1-116.2 116.2-116.2 116.2 52.1 116.2 116.2-52.1 116.2-116.2 116.2zm0-222.6c-58.7 0-106.4 47.7-106.4 106.4s47.7 106.4 106.4 106.4 106.4-47.7 106.4-106.4-47.7-106.4-106.4-106.4z">
												</path>
											</g>
											<g>
												<g>
													<g>
														<path
															d="m224.2 235.7c-.9 0-1.9-.3-2.7-.8-1.8-1.2-2.6-3.4-2-5.5l18.6-62.7-25.9-12.6c-1.5-.7-2.5-2.2-2.7-3.8s.4-3.3 1.7-4.3l79.5-66.2c1.7-1.4 4.1-1.5 5.9-.3s2.6 3.5 1.9 5.6l-20.8 63.2 23.5 15.5c1.3.8 2.1 2.3 2.2 3.8s-.5 3-1.7 4l-74.3 62.8c-.9.9-2 1.3-3.2 1.3zm-.9-87 22.8 11.1c2.2 1 3.2 3.5 2.6 5.8l-15.1 51 56.8-48.1-21.1-14c-1.8-1.2-2.6-3.5-1.9-5.6l16.7-50.6z">
														</path>
													</g>
												</g>
											</g>
										</g>
									</svg>
								</div>
								<h3 class="pbmit-service-title">
									<a href="/services#operations-maintenance">Operations &amp; Maintenance</a>
								</h3>
								<p class="home-service-desc">Expert monitoring and upkeep for peak system performance</p>
							</div>
							<div class="pbmit-service-image-wrapper">
								<div class="pbmit-featured-img-wrapper">
									<div class="pbmit-featured-wrapper">
										<img src="images/homepage-2/service/service-img-02.jpg" class="" alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</article>
				<article class="pbmit-service-style-4 col-md-6 col-lg-4 col-xl-3" data-aos="fade-up"
					data-aos-duration="800" data-aos-delay="200">
					<div class="pbminfotech-post-item">
						<div class="pbmit-box-content-wrap">
							<div class="pbmit-content-box">
								<div class="pbminfotech-box-number">03</div>
								<div class="pbmit-service-icon">
									<svg id="Layer_6" enable-background="new 0 0 512 512" viewBox="0 0 512 512"
										xmlns="http://www.w3.org/2000/svg">
										<g>
											<g>
												<g>
													<g>
														<path
															d="m339.7 204c-2.7 0-4.9-2.2-4.9-4.9v-63.5c0-2.7 2.2-4.9 4.9-4.9s4.9 2.2 4.9 4.9v63.5c-.1 2.7-2.2 4.9-4.9 4.9z">
														</path>
													</g>
													<g>
														<path
															d="m339.7 204c-1.2 0-2.5-.4-3.4-1.4l-6.6-6.6c-33.3-33.3-33.3-87.4 0-120.7l6.6-6.6c1.9-1.9 5-1.9 6.9 0l6.6 6.6c16.1 16.1 25 37.5 25 60.2 0 22.8-8.9 44.3-25 60.5l-6.6 6.6c-1.1.8-2.3 1.4-3.5 1.4zm0-124.9-3.1 3.1c-29.5 29.5-29.5 77.4 0 106.9l3.1 3.1 3.1-3.1c14.3-14.3 22.2-33.4 22.1-53.5 0-20.2-7.9-39.1-22.1-53.4z">
														</path>
													</g>
												</g>
												<g>
													<g>
														<path
															d="m322.9 263h-9.3c-47 0-85.4-38.3-85.4-85.4v-9.3c0-2.7 2.2-4.9 4.9-4.9h9.3c47 0 85.4 38.3 85.4 85.4v9.3c0 2.7-2.2 4.9-4.9 4.9zm-84.9-89.7v4.4c0 41.7 33.9 75.6 75.6 75.6h4.4v-4.4c0-41.7-33.9-75.6-75.6-75.6z">
														</path>
													</g>
													<g>
														<path
															d="m365.7 263h-9.3c-2.7 0-4.9-2.2-4.9-4.9v-9.3c0-47 38.3-85.4 85.4-85.4h9.3c2.7 0 4.9 2.2 4.9 4.9v9.3c0 47.2-38.3 85.4-85.4 85.4zm-4.3-9.7h4.4c41.7 0 75.6-33.9 75.6-75.6v-4.4h-4.4c-41.7 0-75.6 33.9-75.6 75.6z">
														</path>
													</g>
													<g>
														<path
															d="m322.9 263c-1.2 0-2.5-.4-3.4-1.4l-44.8-44.9c-1.9-1.9-1.9-5 0-6.9s5-1.9 6.9 0l44.8 44.9c1.9 1.9 1.9 5 0 6.9-.9 1-2.2 1.4-3.5 1.4z">
														</path>
													</g>
													<g>
														<path
															d="m356.4 263c-1.2 0-2.5-.4-3.4-1.4-1.9-1.9-1.9-5 0-6.9l44.8-44.9c1.9-1.9 5-1.9 6.9 0s1.9 5 0 6.9l-44.7 44.9c-1 1-2.3 1.4-3.6 1.4z">
														</path>
													</g>
												</g>
											</g>
											<g>
												<g>
													<g>
														<path
															d="m309.6 337.4c-1.9 0-3.9-.2-5.9-.6l-53.9-11.8c-2.6-.6-4.3-3.2-3.8-5.8.6-2.6 3.2-4.3 5.8-3.8l53.9 11.8c4.7 1.1 9.5.1 13.4-2.5 4-2.7 6.8-6.8 7.5-11.6 1.5-8.3-3.2-16.4-10.9-19.4-36.2-13.9-128-39.4-199.1 21.4-2 1.8-5.1 1.5-6.8-.5s-1.5-5.1.5-6.8c75.2-64.2 171.2-37.6 209-23.1 12.1 4.7 19.3 17.4 17 30.2-1.3 7.5-5.5 13.9-11.8 18.1-4.5 2.8-9.7 4.4-14.9 4.4z">
														</path>
													</g>
													<g>
														<path
															d="m154 402.3c-1.9 0-3.8-1.1-4.6-3.1-1-2.5.3-5.4 2.7-6.3l28-11.1c19.7-7.9 41.9-8.8 62.2-2.4l26.7 8.3c19.1 5.9 39.8 4.8 58.2-3.2l120.7-52.1c5.4-2.4 9.7-6.8 11.8-12.3s1.9-11.6-.5-16.9c-4.6-9.9-15.4-15.1-25.8-12.2l-100.5 27.5c-2.6.7-5.3-.8-6-3.4s.8-5.3 3.4-6l100.5-27.5c15.1-4.2 30.7 3.2 37.3 17.6 3.6 7.8 3.9 16.5.8 24.5-3.1 8.1-9.1 14.3-17 17.7l-120.7 52.1c-20.5 8.9-43.5 10.1-64.9 3.5l-26.7-8.3c-18.3-5.7-38-4.9-55.7 2.1l-28.2 11.2c-.5.2-1.1.3-1.7.3z">
														</path>
													</g>
													<g>
														<path
															d="m102 444.7c-4 0-7.9-2.3-9.8-6.1 0 0 0-.1-.1-.1l-50-106.8c-1.2-2.5-1.4-5.4-.4-8.2 1-2.7 3-5 5.6-6.2l41.5-19.1c10.8-5 23.7-.4 28.7 10.4l40.9 87.2c2.5 5.2 2.7 11.1.8 16.5-2 5.5-6.1 9.9-11.4 12.4l-41.4 19.1c-1.3.6-2.9.9-4.4.9zm-1-10.4c.3.5 1 .8 1.5.5l41.4-19.1c3-1.3 5.2-3.8 6.3-6.8s1-6.1-.4-9l-40.9-87.2c-2.8-6-9.8-8.5-15.8-5.8l-41.6 19.1c-.4.2-.5.5-.5.6-.1.2-.1.4 0 .7 0 0 0 .1.1.1z">
														</path>
													</g>
												</g>
												<g>
													<path
														d="m128.9 433c-1.8 0-3.7-1.1-4.5-2.9l-52.6-118.9c-1.1-2.5 0-5.4 2.5-6.4 2.5-1.1 5.4 0 6.4 2.5l52.6 118.7c1.1 2.5 0 5.4-2.5 6.4-.6.4-1.2.6-1.9.6z">
													</path>
												</g>
											</g>
										</g>
									</svg>
								</div>
								<h3 class="pbmit-service-title">
									<a href="/services#energy-consulting">Energy Efficiency Consulting</a>
								</h3>
								<p class="home-service-desc">Helping businesses adopt cost-effective, eco-friendly energy
									strategies</p>
							</div>
							<div class="pbmit-service-image-wrapper">
								<div class="pbmit-featured-img-wrapper">
									<div class="pbmit-featured-wrapper">
										<img src="images/homepage-2/service/service-img-03.jpg" class="" alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</article>
				<article class="pbmit-service-style-4 col-md-6 col-lg-4 col-xl-3" data-aos="fade-up"
					data-aos-duration="800" data-aos-delay="300">
					<div class="pbminfotech-post-item">
						<div class="pbmit-box-content-wrap">
							<div class="pbmit-content-box">
								<div class="pbminfotech-box-number">04</div>
								<div class="pbmit-service-icon">
									<svg id="Layer_16" enable-background="new 0 0 512 512" viewBox="0 0 512 512"
										xmlns="http://www.w3.org/2000/svg">
										<g>
											<g>
												<path
													d="m256 362.5c-41.1 0-74.6-33.4-74.6-74.6s33.4-74.6 74.6-74.6c41.1 0 74.6 33.4 74.6 74.6s-33.5 74.6-74.6 74.6zm0-139.3c-35.7 0-64.8 29.1-64.8 64.8s29.1 64.8 64.8 64.8 64.8-29.1 64.8-64.8c0-35.8-29.1-64.8-64.8-64.8z"
													fill="rgb(0,0,0)"></path>
											</g>
											<g>
												<g>
													<g>
														<path
															d="m239 225.2c-2.5 0-4.7-2-4.9-4.5l-10-132.5c-.3-3.9.7-7.5 3-10.6l24.9-34.6c.9-1.3 2.4-2 4-2s3 .8 4 2l25 34.6c2.2 3.1 3.3 6.7 3 10.6l-10.1 132.5c-.2 2.7-2.5 4.7-5.2 4.5s-4.7-2.6-4.5-5.2l10.1-132.5c.1-1.5-.3-2.9-1.1-4.1l-21.2-29.2-21.1 29.1c-.9 1.2-1.3 2.6-1.1 4.1l10.1 132.6c.2 2.7-1.8 5-4.5 5.2-.2 0-.3 0-.4 0z"
															fill="rgb(0,0,0)"></path>
													</g>
												</g>
												<g>
													<g>
														<path
															d="m90 418.4c-.6 0-1.2 0-1.8-.1l-42.4-4.4c-1.6-.2-2.9-1.1-3.7-2.4-.8-1.4-.9-3-.2-4.4l17.5-39c1.6-3.4 4.2-6.2 7.6-7.8l120-57.6c2.4-1.2 5.4-.1 6.5 2.3 1.2 2.4.1 5.4-2.3 6.5l-120 57.5c-1.3.6-2.3 1.7-2.9 3.1l-14.7 32.8 35.8 3.7c1.4.2 2.9-.2 4.1-1l109.8-75c2.2-1.5 5.3-1 6.8 1.3 1.5 2.2.9 5.3-1.3 6.8l-109.8 75c-2.8 1.7-5.9 2.7-9 2.7z"
															fill="rgb(0,0,0)"></path>
													</g>
												</g>
												<g>
													<g>
														<path
															d="m422 418.4c-3.1 0-6.2-1-8.9-2.8l-109.8-75c-2.2-1.5-2.8-4.6-1.3-6.8s4.6-2.8 6.8-1.3l109.8 75c1.2.8 2.7 1.2 4 1l35.8-3.7-14.7-32.8c-.6-1.4-1.6-2.4-3-3.1l-119.8-57.5c-2.4-1.2-3.5-4.1-2.3-6.5s4.1-3.5 6.5-2.3l119.9 57.6c3.5 1.8 6.1 4.5 7.6 7.9l17.5 39c.6 1.4.6 3.1-.2 4.4-.8 1.4-2.2 2.3-3.7 2.4l-42.5 4.4c-.6 0-1.1.1-1.7.1z"
															fill="rgb(0,0,0)"></path>
													</g>
												</g>
											</g>
											<g>
												<path
													d="m398.8 313.2c-.2 0-.5 0-.7-.1-2.7-.4-4.5-2.9-4.1-5.5.9-6 1.3-12.6 1.3-19.6 0-57.2-34.2-107.9-87.2-129.2-2.5-1-3.7-3.9-2.7-6.4s3.9-3.7 6.4-2.7c56.6 22.8 93.2 77.1 93.2 138.3 0 7.5-.5 14.5-1.4 21-.4 2.4-2.4 4.2-4.8 4.2z"
													fill="rgb(0,0,0)"></path>
											</g>
											<g>
												<path
													d="m432.1 318.1c-.2 0-.5 0-.7 0-2.7-.4-4.5-2.9-4.1-5.5 1.1-7.9 1.7-16.1 1.7-24.2 0-70.9-42.5-133.9-108.3-160.4-2.5-1-3.7-3.9-2.7-6.4s3.9-3.7 6.4-2.7c33.4 13.4 61.9 36.3 82.4 66.1 21 30.5 32.1 66.3 32.1 103.4 0 8.6-.6 17.2-1.8 25.6-.5 2.3-2.6 4.1-5 4.1z"
													fill="rgb(0,0,0)"></path>
											</g>
											<g>
												<path
													d="m113.1 313.2c-2.4 0-4.5-1.8-4.8-4.3-.9-7.2-1.4-14.2-1.4-21s.5-13.8 1.4-20.9c5.4-38.3 25.2-72.6 55.8-96.5 11.3-8.8 23.4-15.8 36.2-20.9 2.5-1 5.3.2 6.3 2.7s-.2 5.3-2.7 6.3c-11.9 4.7-23.3 11.3-33.8 19.5-28.6 22.4-47.1 54.4-52.1 90.1-.9 6.6-1.3 13.2-1.3 19.5s.4 12.9 1.3 19.7c.3 2.7-1.5 5.1-4.2 5.5-.2.3-.5.3-.7.3z"
													fill="rgb(0,0,0)"></path>
											</g>
											<g>
												<path
													d="m79.9 318.1c-2.4 0-4.5-1.8-4.8-4.2-1.2-8.3-1.8-16.9-1.8-25.6 0-8.6.6-17.2 1.8-25.6 6.6-46.9 30.9-89 68.4-118.3 13.6-10.6 28.5-19.3 44.3-25.6 2.5-1 5.3.2 6.4 2.7 1 2.5-.2 5.3-2.7 6.4-14.9 6-29 14.2-41.9 24.2-35.6 27.7-58.6 67.5-64.8 111.9-1.1 8-1.7 16.1-1.7 24.2 0 8.2.6 16.4 1.7 24.2.4 2.7-1.5 5.1-4.1 5.5-.3.1-.6.2-.8.2z"
													fill="rgb(0,0,0)"></path>
											</g>
											<g>
												<path
													d="m256.1 437.2c-18.9 0-37.8-3.6-55.8-10.9-13.2-5.3-25.4-12.3-36.2-20.8-2.1-1.7-2.5-4.7-.8-6.9 1.7-2.1 4.7-2.5 6.9-.8 10.1 7.9 21.4 14.5 33.8 19.4 33.7 13.6 70.7 13.6 104.1 0 5.9-2.4 11.8-5.3 17.7-8.6 5.2-2.9 10.5-6.5 16.3-11 2.1-1.6 5.2-1.2 6.8.9s1.2 5.2-.9 6.8c-6.2 4.7-11.9 8.5-17.5 11.7-6.3 3.5-12.6 6.6-18.8 9.2-17.9 7.3-36.8 11-55.6 11z"
													fill="rgb(0,0,0)"></path>
											</g>
											<g>
												<path
													d="m256.2 471c-23.1 0-46.3-4.4-68.5-13.3-16.1-6.5-31-15.1-44.3-25.6-2.1-1.7-2.5-4.7-.8-6.9 1.7-2.1 4.7-2.5 6.9-.8 12.6 10 26.7 18.1 41.9 24.2 41.9 16.8 87.9 16.8 129.3 0 7.7-3.2 15-6.8 21.8-10.7 7.6-4.5 14.2-8.9 20.1-13.6 2.1-1.7 5.2-1.3 6.9.8s1.3 5.2-.8 6.9c-6.3 4.9-13.2 9.6-21.3 14.3-7.1 4.1-14.8 7.9-23 11.2-21.9 9.1-45 13.5-68.2 13.5z"
													fill="rgb(0,0,0)"></path>
											</g>
											<g>
												<g>
													<g>
														<g>
															<path
																d="m235 337.8c-.9 0-1.9-.3-2.7-.8-1.8-1.2-2.6-3.5-2-5.6l10.9-34.1-14.6-9.5c-1.3-.8-2.1-2.3-2.2-3.8s.5-3 1.7-4l48.5-40.7c1.6-1.4 4-1.5 5.8-.4 1.8 1.2 2.6 3.4 2 5.4l-10.3 36.5 12.7 6.4c1.5.7 2.5 2.1 2.7 3.8.2 1.6-.4 3.2-1.6 4.3l-47.7 41.3c-.9.8-2.1 1.2-3.2 1.2zm2.5-54.5 12.2 7.9c1.8 1.2 2.7 3.5 2 5.6l-6.7 21 29-25.2-9.8-4.9c-2.1-1.1-3.1-3.4-2.5-5.7l7.1-25z"
																fill="rgb(0,0,0)"></path>
														</g>
													</g>
												</g>
											</g>
										</g>
									</svg>
								</div>
								<h3 class="pbmit-service-title">
									<a href="/services#open-access-ppa">Open Access &amp; Power Purchase</a>
								</h3>
								<p class="home-service-desc">Access clean solar energy through parks and power purchase
									agreements</p>
							</div>
							<div class="pbmit-service-image-wrapper">
								<div class="pbmit-featured-img-wrapper">
									<div class="pbmit-featured-wrapper">
										<img src="images/homepage-2/service/service-img-04.jpg" class="" alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</article>
			</div>
			<div class="text-center mt-5">
				<a href="/services" class="pbmit-btn">
					<span class="pbmit-button-text">View All Services</span>
				</a>
			</div>
		</div>
	</section>
	<!-- Service End -->

	<!-- Portfolio Start -->
	<section class="portfolio-section-two pbmit-bg-color-white" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="d-flex align-items-center justify-content-between">
				<div class="pbmit-heading-subheading">
					<h4 class="pbmit-subtitle">Products</h4>
					<h2 class="pbmit-title">Solar Solutions for Every Scale</h2>
				</div>
				<div class="d-inline-flex portfolio-arrow"></div>
			</div>
		</div>
		<div class="container-fluid p-0">
			<div class="swiper-slider pbminfotech-gap-0px" data-arrows-class="portfolio-arrow" data-autoplay="true"
				data-loop="true" data-dots="false" data-arrows="true" data-columns="4" data-margin="0"
				data-effect="slide">
				<div class="swiper-wrapper">
					<!-- Slide1 -->
					<article class="pbmit-portfolio-style-1 swiper-slide">
						<div class="pbminfotech-post-content">
							<div class="pbmit-featured-img-wrapper">
								<div class="pbmit-featured-wrapper">
									<img src="images/homepage-2/portfolio/portfolio-img-01.jpg" class="img-fluid"
										alt="">
								</div>
							</div>
							<div class="pbminfotech-box-content">
								<div class="pbminfotech-titlebox">
									<div class="pbmit-port-cat">
										<a href="/products#residential" rel="tag">3 kW to 19 kW</a>
									</div>
									<h3 class="pbmit-portfolio-title">
										<a href="/products#residential">Residential</a>
									</h3>
									<div class="home-product-desc">Tailored home solar for lower bills</div>
									<a class="pbmit-portfolio-btn" href="/products#residential" title="Residential">
										<span class="pbmit-button-icon-wrapper">
											<span class="pbmit-button-icon">
												<i class="demo-icon pbmit-base-icon-arrow-right"></i>
											</span>
										</span>
									</a>
								</div>
							</div>
						</div>
					</article>
					<!-- Slide2 -->
					<article class="pbmit-portfolio-style-1 swiper-slide">
						<div class="pbminfotech-post-content">
							<div class="pbmit-featured-img-wrapper">
								<div class="pbmit-featured-wrapper">
									<img src="images/homepage-2/portfolio/portfolio-img-02.jpg" class="img-fluid"
										alt="">
								</div>
							</div>
							<div class="pbminfotech-box-content">
								<div class="pbminfotech-titlebox">
									<div class="pbmit-port-cat">
										<a href="/products#commercial" rel="tag">20 kW to 200 kW</a>
									</div>
									<h3 class="pbmit-portfolio-title">
										<a href="/products#commercial">Commercial</a>
									</h3>
									<div class="home-product-desc">Scalable solar for business energy needs</div>
									<a class="pbmit-portfolio-btn" href="/products#commercial" title="Commercial">
										<span class="pbmit-button-icon-wrapper">
											<span class="pbmit-button-icon">
												<i class="demo-icon pbmit-base-icon-arrow-right"></i>
											</span>
										</span>
									</a>
								</div>
							</div>
						</div>
					</article>
					<!-- Slide3 -->
					<article class="pbmit-portfolio-style-1 swiper-slide">
						<div class="pbminfotech-post-content">
							<div class="pbmit-featured-img-wrapper">
								<div class="pbmit-featured-wrapper">
									<img src="images/homepage-2/portfolio/portfolio-img-03.jpg" class="img-fluid"
										alt="">
								</div>
							</div>
							<div class="pbminfotech-box-content">
								<div class="pbminfotech-titlebox">
									<div class="pbmit-port-cat">
										<a href="/products#ht-consumer" rel="tag">200 kW to 990 kW</a>
									</div>
									<h3 class="pbmit-portfolio-title">
										<a href="/products#ht-consumer">HT Consumer Projects</a>
									</h3>
									<div class="home-product-desc">High-capacity industrial solar</div>
									<a class="pbmit-portfolio-btn" href="/products#ht-consumer"
										title="HT Consumer Projects">
										<span class="pbmit-button-icon-wrapper">
											<span class="pbmit-button-icon">
												<i class="demo-icon pbmit-base-icon-arrow-right"></i>
											</span>
										</span>
									</a>
								</div>
							</div>
						</div>
					</article>
					<!-- Slide4 -->
					<article class="pbmit-portfolio-style-1 swiper-slide">
						<div class="pbminfotech-post-content">
							<div class="pbmit-featured-img-wrapper">
								<div class="pbmit-featured-wrapper">
									<img src="images/homepage-2/portfolio/portfolio-img-04.jpg" class="img-fluid"
										alt="">
								</div>
							</div>
							<div class="pbminfotech-box-content">
								<div class="pbminfotech-titlebox">
									<div class="pbmit-port-cat">
										<a href="/products#open-access" rel="tag">1 MW to 20 MW</a>
									</div>
									<h3 class="pbmit-portfolio-title">
										<a href="/products#open-access">Open Access Solar</a>
									</h3>
									<div class="home-product-desc">Large-scale direct power purchase</div>
									<a class="pbmit-portfolio-btn" href="/products#open-access"
										title="Open Access Solar">
										<span class="pbmit-button-icon-wrapper">
											<span class="pbmit-button-icon">
												<i class="demo-icon pbmit-base-icon-arrow-right"></i>
											</span>
										</span>
									</a>
								</div>
							</div>
						</div>
					</article>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="text-center pt-5 home-products-cta">
				<a href="/products" class="pbmit-btn">
					<span class="pbmit-button-text">Explore All Products</span>
				</a>
			</div>
		</div>
	</section>
	<!-- Portfolio End -->

	<!-- About Start -->
	<section class="about-section-two home-about-modern" data-aos="fade-up" data-aos-duration="800">
		<div class="container-fluid p-0">
			<div class="row g-0">
				<div class="col-md-4 full-width-1200">
					<div class="about-two-bg-img"></div>
				</div>
				<div class="col-md-8 full-width-1200">
					<div class="about-two-rightbox pbmit-bg-color-secondary">
						<div class="row">
							<div class="col-md-7">
								<div class="about-two-inner-box-left">
									<div class="pbmit-heading-subheading">
										<h4 class="pbmit-subtitle">Who We Are</h4>
										<h2 class="pbmit-title">Shivanjali Renewables Pvt. Ltd.</h2>
									</div>
									<p class="pbmit-firstletter pbmit-desc">Shivanjali Renewables Pvt. Ltd. is a
										pioneering Solar EPC (Engineering, Procurement &amp; Construction) company
										headquartered in Nashik, Maharashtra. With deep expertise across the entire
										solar
										value chain — from design and procurement to installation and maintenance — we
										deliver reliable, high-performance solar solutions for every scale.</p>
									<p class="pbmit-desc">Whether you are a homeowner looking to cut your electricity
										bill,
										a factory owner seeking energy independence, or a developer wanting to build a
										solar park, we are your end-to-end partner.</p>
									<ul class="list-group mb-4">
										<li class="list-group-item">
											<span class="pbmit-icon-list-icon">
												<i class="pbmit-base-icon-tick-1"></i>
											</span>
											<span class="pbmit-icon-list-text">End-to-end EPC delivery across every
												scale</span>
										</li>
										<li class="list-group-item">
											<span class="pbmit-icon-list-icon">
												<i class="pbmit-base-icon-tick-1"></i>
											</span>
											<span class="pbmit-icon-list-text">Design, procurement, installation &amp;
												maintenance</span>
										</li>
									</ul>
									<a href="/about" class="pbmit-btn">
										<span class="pbmit-button-text">Know More About Us</span>
									</a>
								</div>
							</div>
							<div class="col-md-5">
								<div class="about-two-inner-box-right">
									<div class="fid-style-wrap">
										<div class="pbminfotech-ele-fid-style-5">
											<div class="pbmit-fld-contents">
												<div class="pbmit-fld-wrap">
													<div class="pbmit-fld-wrap-inner d-flex">
														<div class="pbmit-sbox-icon-wrapper pbmit-icon-type-icon">
															<i
																class="pbmit-solaar-icon pbmit-solaar-icon-nuclear-plant"></i>
														</div>
														<h4 class="pbmit-fid-inner">
															<span class="pbmit-fid-before"></span>
															<span class="pbmit-number-rotate numinate"
																data-appear-animation="animateDigits" data-from="0"
																data-to="2386" data-interval="5" data-before=""
																data-before-style="" data-after=""
																data-after-style="">2386</span>
															<span class="pbmit-fid"><span>+</span></span>
														</h4>
													</div>
													<span class="pbmit-fid-title">Trusted customers around the
														world</span>
												</div>
											</div>
										</div>
									</div>
									<div class="pbmit-element-timeline-style-2">
										<div class="pbmit-timeline">
											<div class="swiper-slider" data-autoplay="false" data-loop="false"
												data-dots="false" data-arrows="true" data-columns="1" data-margin="30"
												data-effect="slide">
												<div class="swiper-wrapper">
													<!-- Slide1 -->
													<div class="swiper-slide pbmit-timeline-wrapper">
														<div class="pbmit-same-height steps-content_wrap">
															<h3 class="pbmit-timeline-title">Our Vision.</h3>
															<p class="pbmit-timeline-desc">Our mission is to create
																meaningful connections through the power of music. By
																fostering creativity, passion, and innovation</p>
														</div>
													</div>
													<!-- Slide2 -->
													<div class="swiper-slide pbmit-timeline-wrapper">
														<div class="pbmit-same-height steps-content_wrap">
															<h3 class="pbmit-timeline-title">Our Mission</h3>
															<p class="pbmit-timeline-desc">Our mission is to create
																meaningful connections through the power of music. By
																fostering creativity, passion, and innovation</p>
														</div>
													</div>
													<!-- Slide3 -->
													<div class="swiper-slide pbmit-timeline-wrapper">
														<div class="pbmit-same-height steps-content_wrap">
															<h3 class="pbmit-timeline-title">Our Achievements</h3>
															<p class="pbmit-timeline-desc">Our mission is to create
																meaningful connections through the power of music. By
																fostering creativity, passion, and innovation</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- About End -->



	<!-- Ihbox Start -->
	<section class="section-xl ihbox-section-two" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading">
				<h4 class="pbmit-subtitle">Why Choose us</h4>
				<h2 class="pbmit-title">Your partner for sustainable <br> environmental solutions</h2>
			</div>
			<div class="border-top">
				<div class="pt-5 mt-xl-4">
					<div class="row">
						<div class="col-md-4 mb-md-0 mb-4">
							<div class="pbmit-ihbox-style-12">
								<div class="pbmit-ihbox-box d-flex">
									<div class="pbmit-ihbox-icon">
										<div class="pbmit-ihbox-icon-wrapper pbmit-icon-type-icon">
											<svg id="Layer_10" enable-background="new 0 0 512 512" viewBox="0 0 512 512"
												xmlns="http://www.w3.org/2000/svg">
												<g>
													<g>
														<g>
															<g>
																<g>
																	<path
																		d="m227.4 441.9c-2.7 0-4.9-2.2-4.9-4.9v-26.8c0-33.3-11.6-65.7-32.7-91.3l-58.4-70.8c-3.2-3.1-7.6-4.5-11.7-3.9h-.3c-.3 0-.8.1-1 .2-.1 0-.2.1-.4.1-1.5.4-3 1-4.1 1.8-6.2 4.3-8 12.5-4.1 18.8l47.2 76.2c1.4 2.3.7 5.3-1.6 6.7s-5.3.7-6.7-1.6l-47.2-76.2c-6.6-10.7-3.7-24.7 6.7-31.9 2.1-1.5 4.6-2.7 7.3-3.3.9-.3 1.8-.4 2.5-.5h.2c7.1-1.1 14.7 1.4 20.1 6.7.1.1.2.3.4.4l58.5 71c22.5 27.4 34.9 62 34.9 97.5v26.9c.2 2.7-2 4.9-4.7 4.9z">
																	</path>
																</g>
															</g>
															<g>
																<g>
																	<path
																		d="m238.5 471h-114.6c-4.4 0-7.9-3.6-7.9-7.9v-17.8c0-7.4 6-13.4 13.4-13.4h103.6c7.4 0 13.4 6 13.4 13.4v17.8c0 4.3-3.5 7.9-7.9 7.9zm-112.8-9.8h110.9v-15.9c0-2-1.6-3.6-3.6-3.6h-103.7c-2 0-3.6 1.6-3.6 3.6z">
																	</path>
																</g>
															</g>
															<g>
																<path
																	d="m134.9 441.8c-2.7 0-4.9-2.2-4.9-4.9 0-18.3-6.6-36.2-18.5-50.2l-53.2-62.5c-.1-.1-.1-.2-.2-.3-12.7-17.1-17.6-38.8-13.6-59.5l29.2-150.3c1.3-6.8 5.3-12.7 11.1-16.6 5.9-3.9 12.8-5.3 19.7-3.9 9.6 2 17.1 9.1 19.7 18.6.7 2 1 4.4 1 7.1v.1l-3.5 120.6c-.1 2.7-2.4 4.8-5 4.7-2.7-.1-4.8-2.3-4.7-5l3.6-120.4c0-1.6-.2-3-.5-4.1 0-.1-.1-.2-.1-.3-1.6-6-6.3-10.5-12.3-11.7-4.2-.9-8.6 0-12.3 2.4s-6.1 6.1-7 10.3l-29.2 150.4c-3.5 18 .8 36.8 11.7 51.6l53.1 62.4c13.4 15.8 20.8 35.9 20.8 56.5 0 2.8-2.2 5-4.9 5z">
																</path>
															</g>
														</g>
														<g>
															<g>
																<g>
																	<path
																		d="m284.6 441.8c-2.7 0-4.9-2.2-4.9-4.9v-26.7c0-35.5 12.4-70.2 34.9-97.5l58.5-71c.1-.1.2-.2.3-.3 5.4-5.4 12.9-7.9 20.2-6.7l.4.1c.6.1 1.3.2 2.1.4 2.6.6 5.1 1.8 7.4 3.3 10.4 7 13.4 21 6.8 31.9l-47.3 76.2c-1.4 2.3-4.4 3-6.7 1.6s-3-4.4-1.6-6.7l47.3-76.1c3.9-6.3 2.1-14.6-4-18.7-1.4-1-2.8-1.6-4.3-1.9-.1 0-.1 0-.2-.1-.2-.1-.7-.1-1.1-.2l-.5-.1c-4.1-.6-8.5.8-11.6 3.9l-58.4 70.9c-21.1 25.6-32.7 58-32.7 91.3v26.7c.3 2.4-1.9 4.6-4.6 4.6z">
																	</path>
																</g>
															</g>
															<g>
																<g>
																	<path
																		d="m388 471h-114.4c-4.4 0-7.9-3.6-7.9-7.9v-17.8c0-7.4 6-13.4 13.4-13.4h103.7c7.4 0 13.4 6 13.4 13.4v17.8.6c-.6 4.1-4.1 7.3-8.2 7.3zm-112.6-9.8h110.9v-15.9c0-2-1.6-3.6-3.6-3.6h-103.6c-2 0-3.6 1.6-3.6 3.6v15.9z">
																	</path>
																</g>
															</g>
															<g>
																<path
																	d="m377 441.8c-2.7 0-4.9-2.2-4.9-4.9 0-20.7 7.4-40.7 20.8-56.5l53.1-62.4c11-14.8 15.2-33.7 11.7-51.6l-29.3-150.4c-.8-4.2-3.3-7.9-6.9-10.3-3.7-2.4-8-3.3-12.3-2.4-5.9 1.2-10.7 5.7-12.3 11.7-.4 1.4-.6 3-.6 4.3l3.6 120.4c.1 2.7-2 4.9-4.7 5s-5-2-5-4.7l-3.6-120.5v-.1c0-2.3.3-4.7 1-7 2.6-9.5 10.2-16.7 19.7-18.7 6.9-1.4 13.9 0 19.7 3.9s9.8 9.8 11.1 16.6l29.3 150.3c4 20.7-.9 42.4-13.6 59.5-.1.1-.1.2-.2.3l-53.2 62.5c-11.9 14-18.5 31.9-18.5 50.2 0 2.6-2.2 4.8-4.9 4.8z">
																</path>
															</g>
														</g>
													</g>
													<g>
														<path
															d="m256.4 273.4c-64.1 0-116.2-52.1-116.2-116.2s52.1-116.2 116.2-116.2 116.2 52.1 116.2 116.2-52.1 116.2-116.2 116.2zm0-222.6c-58.7 0-106.4 47.7-106.4 106.4s47.7 106.4 106.4 106.4 106.4-47.7 106.4-106.4-47.7-106.4-106.4-106.4z">
														</path>
													</g>
													<g>
														<g>
															<path
																d="m238.5 213.6h-6.4c-30.4 0-55.2-24.8-55.2-55.2v-6.4c0-2.7 2.2-4.9 4.9-4.9h6.4c30.4 0 55.2 24.8 55.2 55.2v6.4c0 2.7-2.2 4.9-4.9 4.9zm-51.8-56.7v1.5c0 25.1 20.4 45.4 45.4 45.4h1.5v-1.5c0-25.1-20.4-45.4-45.4-45.4z">
															</path>
														</g>
														<g>
															<path
																d="m256.5 152.9c-1.7 0-3.3-.6-4.6-1.9l-2.7-2.7c-9-8.9-13.9-20.8-13.9-33.4s4.9-24.5 13.9-33.4l2.7-2.7c2.6-2.6 6.7-2.6 9.3 0l2.7 2.7c8.9 8.9 13.8 20.8 13.8 33.4s-4.9 24.5-13.8 33.4l-2.7 2.7c-1.4 1.3-3 1.9-4.7 1.9zm0-65-.5.5c-7.1 7.1-11 16.5-11 26.5s3.9 19.4 11 26.5l.5.5.5-.5c7.1-7.1 11-16.5 11-26.5s-3.9-19.4-11-26.5z">
															</path>
														</g>
														<g>
															<path
																d="m256.4 231.6c-1.3 0-2.5-.5-3.5-1.4l-46.2-46.3c-1.9-1.9-1.9-5 0-6.9s5-1.9 6.9 0l46.2 46.3c1.9 1.9 1.9 5 0 6.9-.9.9-2.2 1.4-3.4 1.4z">
															</path>
														</g>
														<g>
															<g>
																<path
																	d="m280.7 213.6h-6.4c-2.7 0-4.9-2.2-4.9-4.9v-6.4c0-30.4 24.8-55.2 55.2-55.2h6.4c2.7 0 4.9 2.2 4.9 4.9v6.4c0 30.4-24.7 55.2-55.2 55.2zm-1.4-9.8h1.5c25.1 0 45.4-20.4 45.4-45.4v-1.5h-1.5c-25.1 0-45.4 20.4-45.4 45.4z">
																</path>
															</g>
															<g>
																<path
																	d="m256.5 231.6c-1.2 0-2.5-.5-3.5-1.4-1.9-1.9-1.9-5 0-6.9l46.2-46.3c1.9-1.9 5-1.9 6.9 0s1.9 5 0 6.9l-46.2 46.3c-.9.9-2.2 1.4-3.4 1.4z">
																</path>
															</g>
														</g>
														<g>
															<path
																d="m256.4 273.3c-2.7 0-4.9-2.2-4.9-4.9v-153.5c0-2.7 2.2-4.9 4.9-4.9s4.9 2.2 4.9 4.9v153.5c0 2.7-2.2 4.9-4.9 4.9z">
															</path>
														</g>
													</g>
												</g>
											</svg>
										</div>
									</div>
									<div class="pbmit-ihbox-contents">
										<h2 class="pbmit-element-title">Commercial Solutions</h2>
										<div class="pbmit-heading-desc">Our Climate change mitigation focus on
											sustainable practices such as rainwater harvesting, wastewater recycling.
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4 mb-md-0 mb-4">
							<div class="pbmit-ihbox-style-12">
								<div class="pbmit-ihbox-box d-flex">
									<div class="pbmit-ihbox-icon">
										<div class="pbmit-ihbox-icon-wrapper pbmit-icon-type-icon">
											<svg id="Layer_40" enable-background="new 0 0 512 512" viewBox="0 0 512 512"
												xmlns="http://www.w3.org/2000/svg">
												<g>
													<g>
														<path
															d="m157.1 257.3c-16.8 0-30.4-13.6-30.4-30.4s13.6-30.4 30.4-30.4 30.4 13.6 30.4 30.4-13.6 30.4-30.4 30.4zm0-51c-11.4 0-20.6 9.2-20.6 20.6s9.2 20.6 20.6 20.6 20.6-9.2 20.6-20.6-9.2-20.6-20.6-20.6z">
														</path>
													</g>
													<g>
														<g>
															<g>
																<path
																	d="m147.4 208.3c-2.6 0-4.8-2-4.9-4.6l-4.7-82.7c-.2-2.4.5-4.8 1.9-6.9 0-.1.1-.1.1-.2l13.4-18.4c.9-1.3 2.4-2 3.9-2s3 .8 3.9 2l13.3 18.4c1.5 2 2.2 4.5 2 7.1l-4.7 82.5c-.2 2.7-2.4 4.8-5.1 4.6s-4.8-2.5-4.6-5.1l4.7-82.6c0-.5-.1-.7-.1-.7l-9.4-13-9.3 12.8c-.2.3-.2.5-.2.8l4.7 82.7c.2 2.7-1.9 5-4.6 5.1-.1.2-.2.2-.3.2z">
																</path>
															</g>
														</g>
													</g>
													<g>
														<g>
															<g>
																<path
																	d="m69.1 298.5c-.5 0-.9 0-1.3-.1l-22.5-2.3c-1.6-.2-2.9-1.1-3.7-2.4-.8-1.4-.8-3-.2-4.5l9.4-20.7c1-2.3 2.8-4.2 5.1-5.3l73.8-37.2c2.4-1.2 5.4-.2 6.6 2.2s.2 5.4-2.2 6.6l-74 37.2c-.1 0-.1.1-.2.1-.2.1-.2.2-.3.3 0 .1-.1.1-.1.2l-6.5 14.5 15.9 1.7h.2c.1 0 .2 0 .5-.2l69.4-45.4c2.3-1.5 5.3-.8 6.8 1.4s.8 5.3-1.4 6.8l-69.4 45.3c-1.9 1.2-3.8 1.8-5.9 1.8z">
																</path>
															</g>
														</g>
													</g>
													<g>
														<g>
															<g>
																<path
																	d="m244.9 298.5c-2 0-4-.6-5.9-1.8l-69.1-45.3c-2.3-1.5-2.9-4.5-1.4-6.8s4.5-2.9 6.8-1.4l69.2 45.3c.4.2.5.2.6.2h.2l15.9-1.7-6.5-14.6c0-.1 0-.1-.1-.2 0-.1-.2-.2-.3-.3-.1 0-.1-.1-.2-.1l-73.9-37.3c-2.4-1.2-3.4-4.2-2.2-6.6s4.2-3.4 6.6-2.2l73.8 37.2c2.3 1.1 4.1 3 5.1 5.3l9.3 20.7c.6 1.4.5 3.1-.2 4.5s-2.2 2.3-3.7 2.4l-22.5 2.3c-.6.4-1 .4-1.5.4z">
																</path>
															</g>
														</g>
													</g>
													<g>
														<path
															d="m233.6 230.8c-2.6 0-4.8-2.1-4.9-4.8-.4-25.2-13.4-47.8-34.8-60.5-2.3-1.4-3.1-4.4-1.7-6.7s4.4-3.1 6.7-1.7c24.4 14.4 39.2 40.1 39.7 68.8 0 2.6-2.2 4.9-5 4.9z">
														</path>
													</g>
													<g>
														<path
															d="m80.5 230.7c-2.7 0-4.9-2.2-4.9-4.9 0-2.9.2-6.3.8-10.2 2.9-20.9 13.8-39.6 30.5-52.8 2.4-2 5.1-3.8 8.4-5.8 2.3-1.4 5.3-.7 6.7 1.7 1.4 2.3.7 5.3-1.7 6.7-2.9 1.8-5.3 3.4-7.4 5.1-14.9 11.5-24.4 28-27 46.4-.5 3.4-.6 6.3-.6 8.9.1 2.7-2.1 4.9-4.8 4.9z">
														</path>
													</g>
													<g>
														<g>
															<path
																d="m177 397.5c-2.6 0-4.7-2-4.8-4.6l-9.2-142.6c-.2-2.7 1.9-5 4.5-5.2 2.7-.2 5 1.9 5.2 4.5l9.2 142.5c.2 2.7-1.9 5-4.5 5.2-.1.2-.3.2-.4.2z">
															</path>
														</g>
														<g>
															<path
																d="m137.1 397.5c-.1 0-.2 0-.3 0-2.7-.2-4.8-2.5-4.5-5.2l9.2-142.5c.2-2.7 2.5-4.8 5.2-4.5 2.7.2 4.8 2.5 4.5 5.2l-9.2 142.4c-.2 2.6-2.4 4.6-4.9 4.6z">
															</path>
														</g>
													</g>
													<g>
														<path
															d="m196.3 418.5h-78.4c-3.5 0-6.3-2.7-6.3-6.3v-9.9c0-8 6.5-14.6 14.6-14.6h61.8c8 0 14.6 6.5 14.6 14.6v9.9c0 .2 0 .5-.1.7-.4 3.2-3.1 5.6-6.2 5.6zm-74.9-9.8h71.3v-6.3c0-2.7-2.1-4.8-4.8-4.8h-61.8c-2.7 0-4.8 2.1-4.8 4.8v6.3z">
														</path>
													</g>
													<g>
														<path
															d="m345.9 408h-148.3c-2.7 0-4.9-2.2-4.9-4.9s2.2-4.9 4.9-4.9h148.3c14.2 0 25.8-11.6 25.8-25.8v-32.9c0-2.7 2.2-4.9 4.9-4.9s4.9 2.2 4.9 4.9v32.9c0 19.6-16 35.6-35.6 35.6z">
														</path>
													</g>
													<g>
														<g>
															<g>
																<g>
																	<g>
																		<path
																			d="m358 296.9c-.9 0-1.9-.3-2.7-.8-1.8-1.2-2.6-3.5-2-5.6l9.5-29.5-12.6-8.2c-1.3-.8-2.1-2.3-2.2-3.8s.5-3 1.7-4.1l42.8-36c1.7-1.4 4-1.5 5.8-.4s2.6 3.4 2 5.4l-9 31.7 10.9 5.4c1.4.8 2.5 2.1 2.6 3.8.2 1.6-.5 3.2-1.7 4.3l-42.2 36.3c-.6 1-1.7 1.5-2.9 1.5zm3.1-48.7 10.2 6.6c1.9 1.2 2.6 3.5 2 5.6l-5.3 16.5 23.4-20.2-7.9-3.9c-2.1-1.1-3.2-3.5-2.5-5.7l5.7-20.3z">
																		</path>
																	</g>
																</g>
															</g>
														</g>
													</g>
													<g>
														<path
															d="m376.5 322c-39.7 0-71.9-32.3-71.9-71.9s32.3-71.9 71.9-71.9 71.9 32.3 71.9 71.9c.1 39.6-32.2 71.9-71.9 71.9zm0-134.2c-34.3 0-62.2 27.9-62.2 62.2s27.9 62.2 62.2 62.2 62.2-27.9 62.2-62.2-27.9-62.2-62.2-62.2z">
														</path>
													</g>
													<g>
														<path
															d="m376.5 344.5c-52.1 0-94.5-42.4-94.5-94.5s42.4-94.5 94.5-94.5 94.5 42.5 94.5 94.5-42.4 94.5-94.5 94.5zm0-179.1c-46.7 0-84.6 38-84.6 84.6s38 84.6 84.6 84.6c46.7 0 84.6-38 84.6-84.6s-37.9-84.6-84.6-84.6z">
														</path>
													</g>
												</g>
											</svg>
										</div>
									</div>
									<div class="pbmit-ihbox-contents">
										<h2 class="pbmit-element-title">Tailored Solutions</h2>
										<div class="pbmit-heading-desc">Our Climate change mitigation focus on
											sustainable practices such as rainwater harvesting, wastewater recycling.
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="pbmit-ihbox-style-12">
								<div class="pbmit-ihbox-box d-flex">
									<div class="pbmit-ihbox-icon">
										<div class="pbmit-ihbox-icon-wrapper pbmit-icon-type-icon">
											<svg id="Layer_39" enable-background="new 0 0 512 512" viewBox="0 0 512 512"
												xmlns="http://www.w3.org/2000/svg">
												<g>
													<g>
														<path
															d="m449.4 178.5h-386.8c-11.9 0-21.6-9.7-21.6-21.6s9.7-21.6 21.6-21.6h386.8c11.9 0 21.6 9.7 21.6 21.6s-9.7 21.6-21.6 21.6zm-386.8-33.3c-6.5 0-11.8 5.3-11.8 11.8s5.3 11.8 11.8 11.8h386.8c6.5 0 11.8-5.3 11.8-11.8s-5.3-11.8-11.8-11.8z">
														</path>
													</g>
													<g>
														<g>
															<g>
																<path
																	d="m88.3 370c-2.4 0-4.5-1.9-4.8-4.4l-22.4-191.3c-.4-2.7 1.6-5.1 4.3-5.4 2.7-.4 5.1 1.6 5.4 4.3l22.2 191.4c.4 2.7-1.6 5.1-4.3 5.4-.1 0-.3 0-.4 0z">
																</path>
															</g>
															<g>
																<g>
																	<path
																		d="m125.9 419.7c-13.9 0-25.2-11.3-25.2-25.2s11.3-25.2 25.2-25.2 25.2 11.3 25.2 25.2-11.2 25.2-25.2 25.2zm0-40.6c-8.5 0-15.4 6.9-15.4 15.4s6.9 15.4 15.4 15.4 15.4-6.9 15.4-15.4c.1-8.5-6.9-15.4-15.4-15.4z">
																	</path>
																</g>
																<g>
																	<path
																		d="m125.9 447.3c-29.1 0-52.8-23.7-52.8-52.8s23.7-52.8 52.8-52.8 52.8 23.7 52.8 52.8-23.7 52.8-52.8 52.8zm0-95.8c-23.7 0-43 19.3-43 43s19.3 43 43 43 43-19.3 43-43-19.2-43-43-43z">
																	</path>
																</g>
															</g>
														</g>
														<g>
															<g>
																<path
																	d="m423.7 370c-.2 0-.4 0-.5 0-2.7-.4-4.6-2.8-4.3-5.4l22.2-191.4c.4-2.7 2.8-4.6 5.4-4.3 2.7.4 4.6 2.8 4.3 5.4l-22.2 191.4c-.2 2.5-2.4 4.3-4.9 4.3z">
																</path>
															</g>
															<g>
																<g>
																	<path
																		d="m386.1 419.7c-13.9 0-25.2-11.3-25.2-25.2s11.3-25.2 25.2-25.2 25.2 11.3 25.2 25.2-11.3 25.2-25.2 25.2zm0-40.6c-8.5 0-15.4 6.9-15.4 15.4s6.9 15.4 15.4 15.4 15.5-6.9 15.5-15.4c-.1-8.5-7-15.4-15.5-15.4z">
																	</path>
																</g>
																<g>
																	<path
																		d="m386.1 447.3c-29.1 0-52.8-23.7-52.8-52.8s23.7-52.8 52.8-52.8 52.8 23.7 52.8 52.8-23.8 52.8-52.8 52.8zm0-95.8c-23.7 0-43 19.3-43 43s19.3 43 43 43 43-19.3 43-43-19.3-43-43-43z">
																	</path>
																</g>
															</g>
														</g>
													</g>
													<g>
														<path
															d="m77 145.3c-2.7 0-4.9-2.2-4.9-4.9 0-24.2 19.8-44 44-44 2.7 0 5.3.3 7.8.7 7.9-12.8 22.2-20.9 37.4-20.9 8.4 0 16.4 2.3 23.4 6.8 7.6-5.8 16.9-8.9 26.5-8.9 5 0 10 .9 14.5 2.5 8.1-7.6 18.9-11.8 30-11.8 11.2 0 21.9 4.3 30 11.8 4.6-1.6 9.6-2.5 14.5-2.5 9.6 0 18.9 3.1 26.5 8.9 6.9-4.4 15-6.8 23.4-6.8 15.5 0 29.5 7.9 37.4 20.8 2.6-.4 5.3-.7 7.8-.7 24.2 0 44 19.8 44 44 0 2.7-2.2 4.9-4.9 4.9s-4.9-2.2-4.9-4.9c0-18.9-15.3-34.2-34.2-34.2-3 0-6.1.4-9.1 1.2-2.2.6-4.6-.4-5.6-2.6-5.7-11.5-17.5-18.7-30.4-18.7-7.7 0-14.8 2.4-20.6 7-1.9 1.4-4.5 1.3-6.2-.3-6.4-5.8-14.5-8.9-23-8.9-4.8 0-9.5 1-13.7 2.9-2 .9-4.2.4-5.6-1.2-6.2-7.2-15.4-11.2-25-11.2s-18.8 4.1-25.2 11.1c-1.4 1.6-3.6 2-5.6 1.2-4.2-1.9-9-2.9-13.7-2.9-8.5 0-16.7 3.1-23 8.9-1.8 1.6-4.4 1.7-6.2.3-6.1-4.6-13.2-7-20.6-7-12.8 0-24.8 7.4-30.5 18.9-1.1 2-3.4 3.1-5.6 2.6-3-.8-6.1-1.2-9.1-1.2-18.9 0-34.2 15.3-34.2 34.2-.5 2.6-2.6 4.9-5.4 4.9z">
														</path>
													</g>
													<g>
														<path
															d="m338.1 399.4h-164.2c-2.7 0-4.9-2.2-4.9-4.9s2.2-4.9 4.9-4.9h164.4c2.7 0 4.9 2.2 4.9 4.9-.1 2.7-2.3 4.9-5.1 4.9z">
														</path>
													</g>
													<g>
														<g>
															<path
																d="m256 356.7c-40 0-72.6-32.6-72.6-72.6s32.6-72.6 72.6-72.6 72.6 32.6 72.6 72.6-32.6 72.6-72.6 72.6zm0-135.5c-34.6 0-62.8 28.2-62.8 62.8s28.2 62.8 62.8 62.8 62.8-28.2 62.8-62.8c0-34.5-28.2-62.8-62.8-62.8z">
															</path>
														</g>
														<g>
															<g>
																<g>
																	<g>
																		<path
																			d="m235.5 332.7c-.9 0-1.9-.3-2.7-.8-1.9-1.2-2.7-3.5-2-5.6l10.6-33-14.1-9.2c-1.3-.9-2.1-2.2-2.2-3.8-.1-1.5.5-3 1.7-4l47.1-39.6c1.6-1.4 4-1.5 5.8-.4 1.8 1.2 2.7 3.4 2 5.4l-9.9 35.3 12.2 6.2c1.4.7 2.4 2.1 2.7 3.7.2 1.6-.4 3.2-1.7 4.3l-46.3 40.1c-.9.9-2 1.4-3.2 1.4zm2.8-53.2 11.7 7.7c1.9 1.2 2.7 3.5 2 5.6l-6.4 19.9 27.7-23.9-9.3-4.7c-2-1.1-3.1-3.5-2.5-5.7l6.8-23.8z">
																		</path>
																	</g>
																</g>
															</g>
														</g>
													</g>
												</g>
											</svg>
										</div>
									</div>
									<div class="pbmit-ihbox-contents">
										<h2 class="pbmit-element-title">Expert Installation</h2>
										<div class="pbmit-heading-desc">Our Climate change mitigation focus on
											sustainable practices such as rainwater harvesting, wastewater recycling.
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="pt-md-5 pt-4">
					<div class="row">
						<div class="col-md-4 mb-md-0 mb-4">
							<div class="pbmit-ihbox-style-12">
								<div class="pbmit-ihbox-box d-flex">
									<div class="pbmit-ihbox-icon">
										<div class="pbmit-ihbox-icon-wrapper pbmit-icon-type-icon">
											<svg id="Layer_34" enable-background="new 0 0 512 512" viewBox="0 0 512 512"
												xmlns="http://www.w3.org/2000/svg">
												<g>
													<g>
														<path
															d="m375.2 387.6c-11 0-22.1-3.4-31.3-10.1-16.7-12.2-40-12.2-56.6 0-18.3 13.6-44 13.6-62.6 0-16.7-12.2-40-12.2-56.6 0-18.3 13.6-44 13.6-62.6 0-16.7-12.2-40-12.2-56.6 0-2.2 1.6-5.2 1.1-6.8-1-1.6-2.2-1.1-5.2 1-6.8 20-14.7 48.1-14.7 68.2 0 14.8 10.9 36.3 10.9 51 0 20.1-14.7 48.1-14.7 68.2 0 14.8 10.9 36.3 10.9 51 0 20.1-14.7 48.1-14.7 68.2 0 14.8 10.9 36.3 10.9 51 0 20.1-14.7 48.1-14.7 68.2 0 2.2 1.6 2.6 4.6 1 6.8s-4.6 2.6-6.8 1c-16.7-12.3-40-12.3-56.6 0-9.3 6.8-20.3 10.1-31.3 10.1z">
														</path>
													</g>
													<g>
														<g>
															<path
																d="m256 437.8c-11 0-21.9-3.5-31.3-10.3-16.9-12.4-39.7-12.4-56.6 0-18.7 13.7-43.8 13.7-62.6 0-16.7-12.2-40.1-12.2-56.7 0-1.5 1.1-3.5 1.3-5.1.4s-2.7-2.5-2.7-4.4v-100.2c0-1.5.8-3 2-4 20-14.7 48.1-14.7 68.2 0 14.8 10.9 36.3 10.9 51 0 20.1-14.7 48.1-14.7 68.2 0 14.8 10.9 36.3 10.9 51 0 20.1-14.7 48.1-14.7 68.2 0 14.8 10.9 36.3 10.9 51 0 20.1-14.7 48.1-14.7 68.2 0 1.3.9 2 2.4 2 4v99.3c.2.8.1 1.7-.2 2.5-.7 2-2.5 3.4-4.6 3.4-1.4 0-2.5-.5-3.4-1.4-16.7-11.8-39.6-11.6-56.1.4-18.7 13.7-43.8 13.7-62.6 0-16.9-12.4-39.7-12.4-56.6 0-9.4 6.8-20.4 10.3-31.3 10.3zm59.5-29.4c11.9 0 23.9 3.7 34.1 11.2 15.2 11.2 35.8 11.2 51 0 17.6-12.9 41.3-14.5 60.4-4.8v-88.9c-16.5-10.8-38.6-10.3-54.6 1.4-18.3 13.6-44 13.6-62.6 0-16.7-12.2-40-12.2-56.6 0-18.3 13.6-44 13.6-62.6 0-16.7-12.2-40-12.2-56.6 0-18.3 13.6-44 13.6-62.6 0-16-11.8-38.1-12.2-54.6-1.4v88.9c19.1-9.7 42.9-8.1 60.5 4.7 15.2 11.2 35.8 11.2 51 0 20.4-15 47.8-15 68.2 0 15.2 11.2 35.8 11.2 51 0 10.2-7.3 22.1-11.1 34-11.1z">
															</path>
														</g>
													</g>
													<g>
														<g>
															<path
																d="m255.7 300.8c-44.1 0-80.1-35.9-80.1-80.1s35.9-80.1 80.1-80.1 80.1 35.9 80.1 80.1-36 80.1-80.1 80.1zm0-150.4c-38.8 0-70.3 31.5-70.3 70.3s31.5 70.3 70.3 70.3 70.3-31.5 70.3-70.3-31.6-70.3-70.3-70.3z">
															</path>
														</g>
														<g>
															<g>
																<g>
																	<g>
																		<path
																			d="m232.9 274.2c-.9 0-1.9-.3-2.7-.8-1.9-1.2-2.6-3.5-1.9-5.6l11.9-36.9-16.1-10.5c-1.3-.8-2.1-2.3-2.2-3.8s.5-3 1.8-4.1l52.4-44c1.6-1.4 4-1.5 5.8-.3s2.6 3.4 2 5.4l-11.3 39.8 14 7.1c1.4.8 2.5 2.1 2.6 3.8.2 1.6-.4 3.2-1.7 4.3l-51.5 44.5c-.8.7-1.9 1.1-3.1 1.1zm2.1-58.4 13.7 8.9c1.9 1.2 2.7 3.5 2 5.6l-7.7 23.9 32.8-28.4-11.2-5.6c-2.1-1-3.1-3.5-2.5-5.7l8-28.3z">
																		</path>
																	</g>
																</g>
															</g>
														</g>
													</g>
													<g>
														<path
															d="m160.5 331.2c-1.3 0-2.5-.5-3.5-1.4l-16-16c-3.8-3.8-4.2-9.7-1-14l13.3-18c0-.1.1-.1.1-.2.3-.3.2-.8 0-1-5.7-9.6-9.8-19.8-12.5-30.3-.1-.3-.3-.6-.7-.7h-.1l-22-3.2c-5.2-.7-9.1-5.2-9.1-10.6v-30.4c0-5.3 4-9.9 9.2-10.6l21.9-3.2h.1c.3-.1.6-.4.7-.7 2.6-10.4 6.8-20.6 12.5-30.3.2-.3.2-.8-.1-1.1l-13.3-17.9-.1-.1c-3-4.3-2.6-10.1 1.1-13.9l21.8-21.5c3.8-3.8 9.6-4.2 13.9-1l17.8 13.3.1.1c.3.3.8.2 1 0 9.6-5.7 19.8-9.8 30.3-12.5.3-.1.6-.3.7-.7v-.1l3.2-22c.7-5.2 5.2-9.1 10.6-9.1h30.6c5.3 0 9.9 4 10.6 9.2l3.2 21.9v.1c.1.3.4.6.7.7 10.4 2.6 20.6 6.8 30.3 12.5.3.2.8.2 1.1-.1l17.8-13.3c4.3-3.2 10.1-2.8 13.9 1l21.6 21.6c3.8 3.8 4.2 9.7 1 14l-13.3 17.8-.1.1c-.3.3-.2.8 0 1 5.7 9.6 9.8 19.8 12.5 30.3.1.3.3.6.7.7h.1l22 3.2c5.2.7 9.1 5.2 9.1 10.6v30.6c0 5.3-4 9.9-9.2 10.6l-21.9 3.2h-.1c-.3.1-.6.4-.7.7-2.6 10.4-6.8 20.6-12.5 30.3-.2.3-.2.8.1 1.1l13.3 17.8c3.2 4.3 2.8 10.1-1 13.9l-15.8 15.8c-1.9 1.9-5 1.9-6.9 0s-1.9-5 0-6.9l15.8-15.8c.3-.3.4-.8.2-1.3l-13.3-17.9c-2.5-3.5-2.8-8.1-.7-11.8 5.2-8.9 9-18.3 11.5-27.8 1.1-4.3 4.6-7.4 8.9-7.9l21.9-3.2c.4-.1.8-.5.8-.9v-30.4c0-.5-.3-.8-.8-.9l-22-3.2c-4.3-.6-7.8-3.6-8.9-7.9-2.5-9.6-6.3-18.9-11.5-27.7-2.2-3.9-1.9-8.5.7-11.9l13.3-17.8c.3-.4.2-.9-.1-1.3l-21.6-21.8c-.3-.3-.8-.4-1.3-.2l-17.8 13.5c-3.5 2.5-8.1 2.8-11.8.7-8.9-5.2-18.3-9-27.8-11.5-4.3-1.1-7.4-4.6-7.9-8.9l-3.1-21.8c-.1-.4-.5-.8-.9-.8h-30.4c-.5 0-.8.3-.9.8l-3.2 22c-.6 4.3-3.6 7.8-7.9 8.9-9.6 2.5-18.9 6.3-27.7 11.5-3.9 2.2-8.5 1.9-11.9-.8l-18-13.4c-.4-.3-.9-.2-1.3.1l-21.8 21.4c-.3.3-.3.9 0 1.3l13.3 17.8c2.5 3.5 2.8 8.1.7 11.8-5.2 8.9-9 18.3-11.5 27.8-1.1 4.3-4.6 7.4-8.9 7.9l-21.9 3.2c-.4.1-.8.5-.8.9v30.4c0 .5.3.8.8.9l22 3.2c4.3.6 7.8 3.6 8.9 7.9 2.5 9.6 6.3 18.9 11.5 27.7 2.2 3.9 1.9 8.5-.7 11.8l-13.3 18c-.3.5-.2 1 .1 1.3l16 16c1.9 1.9 1.9 5 0 6.9-1.1 1.4-2.4 1.9-3.7 1.9z">
														</path>
													</g>
												</g>
											</svg>
										</div>
									</div>
									<div class="pbmit-ihbox-contents">
										<h2 class="pbmit-element-title">Expert Installation </h2>
										<div class="pbmit-heading-desc">Our Climate change mitigation focus on
											sustainable practices such as rainwater harvesting, wastewater recycling.
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4 mb-md-0 mb-4">
							<div class="pbmit-ihbox-style-12">
								<div class="pbmit-ihbox-box d-flex">
									<div class="pbmit-ihbox-icon">
										<div class="pbmit-ihbox-icon-wrapper pbmit-icon-type-icon">
											<svg id="Layer_37" enable-background="new 0 0 512 512" viewBox="0 0 512 512"
												xmlns="http://www.w3.org/2000/svg">
												<g>
													<g>
														<path
															d="m332.6 417c-2.7 0-4.8-2.2-4.8-4.8v-323.5c0-6.9-5.6-12.4-12.4-12.4h-235.2c-6.9 0-12.4 5.6-12.4 12.4v323.4c0 2.7-2.2 4.8-4.8 4.8s-4.8-2.2-4.8-4.8v-323.4c0-12.3 10-22.2 22.1-22.2h235.1c12.3 0 22.2 10 22.2 22.2v323.4c0 2.8-2.2 4.9-5 4.9z">
														</path>
													</g>
													<g>
														<path
															d="m343.8 445.5h-292c-5.9 0-10.8-4.8-10.8-10.8v-16.6c0-5.9 4.8-10.8 10.8-10.8h292.1c5.9 0 10.8 4.8 10.8 10.8v16.6c-.1 5.9-5 10.8-10.9 10.8zm-292-28.4c-.5 0-1 .4-1 1v16.6c0 .5.4 1 1 1h292.1c.5 0 1-.4 1-1v-16.5c0-.5-.4-1-1-1z">
														</path>
													</g>
													<g>
														<path
															d="m412.2 394c-15.7 0-30.4-6.2-41.6-17.3-11.1-11.1-17.3-25.9-17.3-41.6v-15.9c0-11.5-9.3-20.7-20.7-20.7-2.7 0-4.8-2.2-4.8-4.8 0-2.7 2.2-4.8 4.8-4.8 16.8 0 30.5 13.7 30.5 30.5v15.9c0 13 5.1 25.4 14.4 34.6 9.3 9.3 21.6 14.4 34.6 14.4 27.1 0 49.1-22 49.1-49.1v-185.8c0-2.1-.9-4.1-2.3-5.6l-49.8-49.7c-1.5-1.5-3.5-2.4-5.6-2.4s-4.1.8-5.6 2.3c-1.6 1.6-2.5 3.6-2.5 5.7s.9 4.1 2.4 5.6l42.1 42.1c3.3 3.3 5.2 7.8 5.2 12.5v33.8c0 2.7-2.2 4.8-4.8 4.8-2.7 0-4.8-2.2-4.8-4.8v-33.7c0-2.1-.9-4.1-2.4-5.6l-42.1-42c-3.3-3.3-5.2-7.8-5.2-12.6s1.9-9.3 5.2-12.6c3.2-3.3 7.8-5.2 12.5-5.2s9.3 1.9 12.5 5.2l49.7 49.6c3.3 3.2 5.3 7.8 5.3 12.5v185.8c0 32.5-26.4 58.9-58.8 58.9z">
														</path>
													</g>
													<g>
														<path
															d="m412.1 368c-18.2 0-33-14.8-33-33v-15.9c0-25.6-20.9-46.5-46.5-46.5-2.7 0-4.8-2.2-4.8-4.8 0-2.7 2.2-4.8 4.8-4.8 31 0 56.3 25.3 56.3 56.3v15.9c0 12.9 10.4 23.3 23.3 23.3 6.3 0 12.1-2.4 16.5-6.8s6.8-10.2 6.8-16.4v-65.5c0-2.7 2.2-4.8 4.8-4.8s4.8 2.2 4.8 4.8v65.5c0 8.8-3.4 17.1-9.6 23.3s-14.6 9.4-23.4 9.4z">
														</path>
													</g>
													<g>
														<path
															d="m466.1 274.6h-31.2c-11 0-20-9-20-20v-45.7c0-11 9-20 20-20h31.2c2.7 0 4.8 2.2 4.8 4.8s-2.2 4.8-4.8 4.8h-31.2c-5.6 0-10.2 4.6-10.2 10.2v45.7c0 5.6 4.6 10.2 10.2 10.2h31.2c2.7 0 4.8 2.2 4.8 4.8 0 3-2.1 5.2-4.8 5.2z">
														</path>
													</g>
													<g>
														<g>
															<path
																d="m282 207.6h-168.6c-6.8 0-12.3-5.6-12.3-12.3v-67.1c0-6.8 5.6-12.3 12.3-12.3h168.7c6.8 0 12.3 5.6 12.3 12.3v67.2.1c-.1 6.7-5.7 12.1-12.4 12.1zm-168.6-81.9c-1.4 0-2.6 1.1-2.6 2.6v67.2c0 1.4 1.1 2.6 2.6 2.6h168.6c1.4 0 2.6-1.1 2.6-2.6v-67.1c0-1.4-1.1-2.6-2.6-2.6h-168.6z">
															</path>
														</g>
													</g>
													<g>
														<path
															d="m153.5 319.9c-7.7 0-14.5-1.6-20.7-4.7-20.8-10.5-27.5-35.8-30.7-48-.3-1-.5-1.9-.7-2.7-.7-2.4-.1-4.8 1.6-6.7 1.8-1.9 4.4-2.7 7.1-2.2.2 0 .3.1.4.1 13 3.9 17.4 2.3 18.4 1.7 0 0 .1 0 .1-.1 5.9-3.2 11.4-4.8 16.7-5.3 11.1-.7 20.2 2.5 27.1 9.4 16 16 14.2 46.2 14.1 47.5-.1 1.9-1.3 3.5-3.1 4.2-11 4.5-21.2 6.8-30.3 6.8zm-41.5-53.6c3.2 11.9 9 32.1 25.2 40.2 10.3 5.2 23.8 4.8 39.9-1.1 0-7.5-1.1-26.7-11.3-36.9-4.9-4.9-11.4-7.1-19.5-6.5-3.9.3-8.1 1.7-12.7 4.1-4.7 2.5-11.8 2.6-21.6.2z">
														</path>
													</g>
													<g>
														<path
															d="m241.3 319c-8.9 0-18.4-2.6-28.6-7.5-1.5-.8-2.6-2.2-2.7-4-.1-1.2-2.6-30.3 12.7-46 6.9-7.1 16-10.2 27.2-9.5 5.5.4 11.1 2.2 16.7 5.3 1.1.6 5.5 2.2 18.4-1.7.2 0 .3-.1.4-.1 2.6-.5 5.3.3 7.1 2.2 1.7 1.9 2.2 4.3 1.5 6.7-7.5 26.6-19.4 43.8-35.3 50.9-5.4 2.5-11.2 3.7-17.4 3.7zm-21.7-15.1c13.5 6.1 25.4 7 35.3 2.6 12.3-5.5 21.9-19 28.6-40.2-9.8 2.4-16.8 2.3-21.5-.4-4.3-2.4-8.7-3.8-12.7-4.1-8.3-.5-14.7 1.6-19.5 6.5-9.9 10.1-10.4 28.7-10.2 35.6z">
														</path>
													</g>
													<g>
														<path
															d="m197.8 338.1c-2.1 0-4-1.3-4.7-3.3-9.9-30.3-50.7-45.7-51.1-45.9-2.6-1-3.8-3.7-2.9-6.3 1-2.6 3.7-3.8 6.3-2.9 1.7.6 36.5 13.7 52.2 41.2 14.6-26.5 50.9-40.7 52.6-41.3 2.6-1 5.4.3 6.3 2.8 1 2.6-.3 5.4-2.8 6.3-.4.2-43.4 17-51.2 45.8-.6 2.1-2.5 3.5-4.7 3.6.1 0 .1 0 0 0z">
														</path>
													</g>
													<g>
														<path
															d="m197.8 417c-2.7 0-4.8-2.2-4.8-4.8v-79c0-2.7 2.2-4.8 4.8-4.8s4.8 2.2 4.8 4.8v79c.1 2.7-2.1 4.8-4.8 4.8z">
														</path>
													</g>
												</g>
											</svg>
										</div>
									</div>
									<div class="pbmit-ihbox-contents">
										<h2 class="pbmit-element-title">Low Cost Operation</h2>
										<div class="pbmit-heading-desc">Our Climate change mitigation focus on
											sustainable practices such as rainwater harvesting, wastewater recycling.
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="pbmit-ihbox-style-12">
								<div class="pbmit-ihbox-box d-flex">
									<div class="pbmit-ihbox-icon">
										<div class="pbmit-ihbox-icon-wrapper pbmit-icon-type-icon">
											<svg id="Layer_24" enable-background="new 0 0 512 512" viewBox="0 0 512 512"
												xmlns="http://www.w3.org/2000/svg">
												<g>
													<g>
														<path
															d="m188.9 386.1c-2.7 0-4.9-2.2-4.9-4.9-.1-23.4-6.9-30.8-20.6-45.6-3-3.2-6.3-6.8-9.9-10.9-12.3-14.1-21.3-30.4-27-48.3-4.1-13-6.2-26.7-6.2-40.7 0-37.3 15.6-73.4 42.8-98.9 27.5-25.9 63.6-38.9 101.5-36.5 34 2.1 65.7 16.6 89.2 41s37 56.5 37.9 90.6c.4 15.1-1.7 30.1-6.2 44.5-5.7 18.2-15.4 35.2-28 49.4-3.2 3.6-6.1 6.8-8.8 9.6-14.3 15.3-20.8 22.3-20.8 45.8 0 2.7-2.2 4.9-4.9 4.9s-4.9-2.2-4.9-4.9c0-27.3 8.8-36.8 23.4-52.4 2.7-2.9 5.5-6 8.6-9.4 11.7-13.1 20.7-29 26-45.8 4.2-13.4 6.1-27.3 5.7-41.3-1.8-65.6-52.5-118.1-118-122.1-35.2-2.2-68.6 9.9-94.2 33.9-25.6 24.1-39.7 56.7-39.7 91.8 0 12.9 1.9 25.6 5.7 37.7 5.2 16.6 13.6 31.7 25 44.8 3.5 4 6.8 7.6 9.7 10.7 14.4 15.6 23.1 25 23.2 52.2.3 2.6-1.9 4.8-4.6 4.8z"
															fill="rgb(0,0,0)"></path>
													</g>
													<g>
														<path
															d="m256 79.4c-2.7 0-4.9-2.2-4.9-4.9v-28.6c0-2.7 2.2-4.9 4.9-4.9s4.9 2.2 4.9 4.9v28.6c0 2.7-2.2 4.9-4.9 4.9z"
															fill="rgb(0,0,0)"></path>
													</g>
													<g>
														<path
															d="m195.1 91.5c-1.9 0-3.7-1.1-4.5-3l-10.9-26.5c-1-2.5.2-5.4 2.6-6.4 2.5-1 5.4.2 6.4 2.6l10.9 26.5c1 2.5-.2 5.4-2.6 6.4-.6.3-1.3.4-1.9.4z"
															fill="rgb(0,0,0)"></path>
													</g>
													<g>
														<path
															d="m143.4 126c-1.3 0-2.5-.5-3.5-1.4l-20.2-20.2c-1.9-1.9-1.9-5 0-6.9s5-1.9 6.9 0l20.2 20.2c1.9 1.9 1.9 5 0 6.9-.9.9-2.1 1.4-3.4 1.4z"
															fill="rgb(0,0,0)"></path>
													</g>
													<g>
														<path
															d="m108.9 177.6c-.6 0-1.3-.1-1.9-.4l-26.4-10.9c-2.5-1-3.7-3.9-2.6-6.4 1-2.5 3.9-3.7 6.4-2.6l26.4 10.9c2.5 1 3.7 3.9 2.6 6.4-.7 1.9-2.6 3-4.5 3z"
															fill="rgb(0,0,0)"></path>
													</g>
													<g>
														<path
															d="m82.5 310.3c-1.9 0-3.7-1.1-4.5-3-1-2.5.1-5.4 2.6-6.4l26.4-10.9c2.5-1 5.4.1 6.4 2.6s-.1 5.4-2.6 6.4l-26.4 11c-.6.2-1.2.3-1.9.3z"
															fill="rgb(0,0,0)"></path>
													</g>
													<g>
														<path
															d="m429.5 310.3c-.6 0-1.3-.1-1.9-.4l-26.4-10.9c-2.5-1-3.7-3.9-2.6-6.4 1-2.5 3.9-3.7 6.4-2.6l26.4 10.9c2.5 1 3.7 3.9 2.6 6.4-.8 1.9-2.6 3-4.5 3z"
															fill="rgb(0,0,0)"></path>
													</g>
													<g>
														<g>
															<path
																d="m96.9 238.5h-28.6c-2.7 0-4.9-2.2-4.9-4.9s2.2-4.9 4.9-4.9h28.6c2.7 0 4.9 2.2 4.9 4.9s-2.2 4.9-4.9 4.9z"
																fill="rgb(0,0,0)"></path>
														</g>
														<g>
															<path
																d="m443.7 238.5h-28.6c-2.7 0-4.9-2.2-4.9-4.9s2.2-4.9 4.9-4.9h28.6c2.7 0 4.9 2.2 4.9 4.9s-2.2 4.9-4.9 4.9z"
																fill="rgb(0,0,0)"></path>
														</g>
													</g>
													<g>
														<path
															d="m403.1 177.6c-1.9 0-3.7-1.1-4.5-3-1-2.5.1-5.4 2.6-6.4l26.4-10.9c2.5-1 5.4.1 6.4 2.6s-.1 5.4-2.6 6.4l-26.4 10.9c-.7.3-1.3.4-1.9.4z"
															fill="rgb(0,0,0)"></path>
													</g>
													<g>
														<path
															d="m368.6 126c-1.3 0-2.5-.5-3.5-1.4-1.9-1.9-1.9-5 0-6.9l20.2-20.2c1.9-1.9 5-1.9 6.9 0s1.9 5 0 6.9l-20.2 20.2c-.9.9-2.2 1.4-3.4 1.4z"
															fill="rgb(0,0,0)"></path>
													</g>
													<g>
														<path
															d="m316.9 91.5c-.6 0-1.3-.1-1.9-.4-2.5-1-3.7-3.9-2.6-6.4l10.9-26.5c1-2.5 3.9-3.7 6.4-2.6 2.5 1 3.7 3.9 2.6 6.4l-10.9 26.5c-.8 1.9-2.6 3-4.5 3z"
															fill="rgb(0,0,0)"></path>
													</g>
													<g>
														<g>
															<path
																d="m326.1 414.5h-140.1c-9.3 0-16.8-7.5-16.8-16.8v-10.8c0-5.8 4.7-10.5 10.5-10.5h152.6c5.8 0 10.5 4.7 10.5 10.5v10.8c.1 4.4-1.6 8.6-4.8 11.8s-7.4 5-11.9 5zm-146.4-28.3c-.3 0-.7.3-.7.7v10.8c0 3.9 3.2 7 7 7h140c1.9 0 3.6-.7 5-2.1 1.3-1.3 2-3.1 2-4.9v-10.9c0-.3-.3-.7-.7-.7h-152.6z"
																fill="rgb(0,0,0)"></path>
														</g>
														<g>
															<path
																d="m296.4 443h-80.7c-10.5 0-19.1-8.5-19.1-19.1v-14.3c0-2.7 2.2-4.9 4.9-4.9s4.9 2.2 4.9 4.9v14.3c0 5.1 4.2 9.3 9.3 9.3h80.7c5.1 0 9.3-4.2 9.3-9.3v-14.3c0-2.7 2.2-4.9 4.9-4.9s4.9 2.2 4.9 4.9v14.3c-.1 10.5-8.6 19.1-19.1 19.1z"
																fill="rgb(0,0,0)"></path>
														</g>
														<g>
															<path
																d="m256 471c-18.2 0-32.9-14.8-32.9-32.9 0-2.7 2.2-4.9 4.9-4.9s4.9 2.2 4.9 4.9c0 12.8 10.4 23.2 23.2 23.2s23.2-10.4 23.2-23.2c0-2.7 2.2-4.9 4.9-4.9s4.9 2.2 4.9 4.9c-.2 18.1-14.9 32.9-33.1 32.9z"
																fill="rgb(0,0,0)"></path>
														</g>
													</g>
													<g>
														<g>
															<g>
																<path
																	d="m256 266.6c-2.7 0-4.9-2.2-4.9-4.9v-52.5c0-2.7 2.2-4.9 4.9-4.9s4.9 2.2 4.9 4.9v52.5c0 2.7-2.2 4.9-4.9 4.9z"
																	fill="rgb(0,0,0)"></path>
															</g>
															<g>
																<path
																	d="m256 266.6c-1.3 0-2.5-.5-3.5-1.4l-3.4-3.4c-14-14-21.7-32.6-21.7-52.5s7.7-38.5 21.7-52.5l3.4-3.4c1.9-1.9 5-1.9 6.9 0l3.4 3.4c14 14 21.7 32.6 21.7 52.5s-7.7 38.5-21.7 52.5l-3.4 3.4c-.9.9-2.1 1.4-3.4 1.4zm0-102.9c-12.1 12.2-18.8 28.3-18.8 45.5s6.7 33.4 18.8 45.5c12.1-12.2 18.8-28.3 18.8-45.5s-6.7-33.3-18.8-45.5z"
																	fill="rgb(0,0,0)"></path>
															</g>
														</g>
														<g>
															<g>
																<path
																	d="m242.1 315.4h-4.9c-40.9 0-74.2-33.3-74.2-74.2v-4.9c0-2.7 2.2-4.9 4.9-4.9h4.9c40.9 0 74.2 33.3 74.2 74.2v4.9c0 2.7-2.2 4.9-4.9 4.9zm-69.3-74.2c0 35.5 28.9 64.4 64.4 64.4 0-35.5-28.9-64.4-64.4-64.4z"
																	fill="rgb(0,0,0)"></path>
															</g>
															<g>
																<path
																	d="m274.8 315.4h-4.9c-2.7 0-4.9-2.2-4.9-4.9v-4.9c0-40.9 33.3-74.2 74.2-74.2h4.9c2.7 0 4.9 2.2 4.9 4.9v4.9c-.1 40.9-33.3 74.2-74.2 74.2zm64.4-74.2c-35.5 0-64.4 28.9-64.4 64.4 35.5 0 64.4-28.8 64.4-64.4z"
																	fill="rgb(0,0,0)"></path>
															</g>
															<g>
																<path
																	d="m242.1 315.4c-1.3 0-2.5-.5-3.5-1.4l-37-37.1c-1.9-1.9-1.9-5 0-6.9s5-1.9 6.9 0l37 37.1c1.9 1.9 1.9 5 0 6.9-.9.9-2.1 1.4-3.4 1.4z"
																	fill="rgb(0,0,0)"></path>
															</g>
															<g>
																<path
																	d="m269.9 315.4c-1.2 0-2.5-.5-3.5-1.4-1.9-1.9-1.9-5 0-6.9l37-37.1c1.9-1.9 5-1.9 6.9 0s1.9 5 0 6.9l-37 37.1c-.9.9-2.2 1.4-3.4 1.4z"
																	fill="rgb(0,0,0)"></path>
															</g>
														</g>
													</g>
												</g>
											</svg>
										</div>
									</div>
									<div class="pbmit-ihbox-contents">
										<h2 class="pbmit-element-title">Expert Solar Worker</h2>
										<div class="pbmit-heading-desc">Our Climate change mitigation focus on
											sustainable practices such as rainwater harvesting, wastewater recycling.
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Ihbox Start -->

	<section class="pbmit-bg-color-secondary pbmit-color-white" style="padding: 70px 0;">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title" style="color: var(--pbmit-white-color);">Why Shivanjali Renewables?</h2>
			</div>
			<div class="row g-4 text-center">
				<div class="col-md-4">
					<div style="font-size: 44px; margin-bottom: 10px;">
						<i class="pbmit-solaar-icon pbmit-solaar-icon-verified"></i>
					</div>
					<h3 style="color: var(--pbmit-white-color); font-size: 22px; margin-bottom: 10px;">Experience</h3>
					<p style="color: rgba(255,255,255,0.9); margin: 0;">Years of proven expertise in the solar industry
					</p>
				</div>
				<div class="col-md-4">
					<div style="font-size: 44px; margin-bottom: 10px;">
						<i class="pbmit-solaar-icon pbmit-solaar-icon-eco-friendly"></i>
					</div>
					<h3 style="color: var(--pbmit-white-color); font-size: 22px; margin-bottom: 10px;">Expert Team</h3>
					<p style="color: rgba(255,255,255,0.9); margin: 0;">Engineers, technicians, and consultants
						committed to excellence</p>
				</div>
				<div class="col-md-4">
					<div style="font-size: 44px; margin-bottom: 10px;">
						<i class="pbmit-solaar-icon pbmit-solaar-icon-call"></i>
					</div>
					<h3 style="color: var(--pbmit-white-color); font-size: 22px; margin-bottom: 10px;">Comprehensive
						Support</h3>
					<p style="color: rgba(255,255,255,0.9); margin: 0;">Full warranty, after-sales maintenance, and
						project design services</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Team Start -->
	<!-- <section class="team-section-two pbmit-bg-color-secondary" data-aos="fade-up" data-aos-duration="800">
		<div class="container-fluid p-0">
			<div class="container">
				<div class="pbmit-tween-text pbmit-tween-effect-style-1">
					<div class="pbmit-tween-effect" data-x-start="-3" data-x-end="4" data-y-start="" data-y-end=""
						data-scale-x-start="1" data-scale-x-end=" 1" data-skew-x-start=" 0deg" data-skew-x-end="0deg"
						data-skew-y-start=" 0deg" data-skew-y-end=" 0deg" data-rotate-x-start="0" data-rotate-x-end="0">
						<h3 class="pbmit-element-title">Meet Our Experts</h3>
					</div>
				</div>
			</div>
			<div class="team-main-img">
				<img src="images/homepage-2/team-main-img.png" class="img-fluid" alt="">
			</div>
			<div class="container">
				<div class="team-style-area">
					<p class="pbmit-team-desc">The goal of our clinic is to provide friendly, caring dentistry and the
						highest level of general, cosmetic and specialist dental treatments With dental practice
						throughout the world. Once the plan is finalized, we’ll proceed with your treatment. Our expert
						team will guide you.</p>
					<div class="pbmit-element-team-style-4">
						<div class="row">
							<article class="pbmit-team-style-4 col-md-6 col-lg-4 col-xl-3">
								<div class="pbminfotech-post-item">
									<div class="pbminfotech-box-content">
										<div class="pbminfotech-box-content-inner">
											<div class="pbmit-featured-wrapper pbmit-hover-img">
												<div class="pbmit-featured-img-wrapper">
													<div class="pbmit-featured-wrapper">
														<img src="images/homepage-2/team/team-img-01.jpg"
															class="img-fluid" alt="">
													</div>
												</div>
											</div>
											<h3 class="pbmit-team-title">
												<a href="team-member-detail.html">Andrea Luies</a>
											</h3>
											<div class="pbminfotech-box-team-position">Cheif Officer</div>
										</div>
									</div>
								</div>
							</article>
							<article class="pbmit-team-style-4 col-md-6 col-lg-4 col-xl-3">
								<div class="pbminfotech-post-item">
									<div class="pbminfotech-box-content">
										<div class="pbminfotech-box-content-inner">
											<div class="pbmit-featured-wrapper pbmit-hover-img">
												<div class="pbmit-featured-img-wrapper">
													<div class="pbmit-featured-wrapper">
														<img src="images/homepage-2/team/team-img-02.jpg"
															class="img-fluid" alt="">
													</div>
												</div>
											</div>
											<h3 class="pbmit-team-title">
												<a href="team-member-detail.html">Alex Mitchell</a>
											</h3>
											<div class="pbminfotech-box-team-position">Project Manager</div>
										</div>
									</div>
								</div>
							</article>
							<article class="pbmit-team-style-4 col-md-6 col-lg-4 col-xl-3">
								<div class="pbminfotech-post-item">
									<div class="pbminfotech-box-content">
										<div class="pbminfotech-box-content-inner">
											<div class="pbmit-featured-wrapper pbmit-hover-img">
												<div class="pbmit-featured-img-wrapper">
													<div class="pbmit-featured-wrapper">
														<img src="images/homepage-2/team/team-img-03.jpg"
															class="img-fluid" alt="">
													</div>
												</div>
											</div>
											<h3 class="pbmit-team-title">
												<a href="team-member-detail.html">John Harris</a>
											</h3>
											<div class="pbminfotech-box-team-position">Social Leader</div>
										</div>
									</div>
								</div>
							</article>
							<article class="pbmit-team-style-4 col-md-6 col-lg-4 col-xl-3">
								<div class="pbminfotech-post-item">
									<div class="pbminfotech-box-content">
										<div class="pbminfotech-box-content-inner">
											<div class="pbmit-featured-wrapper pbmit-hover-img">
												<div class="pbmit-featured-img-wrapper">
													<div class="pbmit-featured-wrapper">
														<img src="images/homepage-2/team/team-img-04.jpg"
															class="img-fluid" alt="">
													</div>
												</div>
											</div>
											<h3 class="pbmit-team-title">
												<a href="team-member-detail.html">David Handson</a>
											</h3>
											<div class="pbminfotech-box-team-position">Program Manager</div>
										</div>
									</div>
								</div>
							</article>
							<article class="pbmit-team-style-4 col-md-6 col-lg-4 col-xl-3">
								<div class="pbminfotech-post-item">
									<div class="pbminfotech-box-content">
										<div class="pbminfotech-box-content-inner">
											<div class="pbmit-featured-wrapper pbmit-hover-img">
												<div class="pbmit-featured-img-wrapper">
													<div class="pbmit-featured-wrapper">
														<img src="images/homepage-2/team/team-img-05.jpg"
															class="img-fluid" alt="">
													</div>
												</div>
											</div>
											<h3 class="pbmit-team-title">
												<a href="team-member-detail.html">Micheal Wagou</a>
											</h3>
											<div class="pbminfotech-box-team-position">General Manager</div>
										</div>
									</div>
								</div>
							</article>
							<article class="pbmit-team-style-4 col-md-6 col-lg-4 col-xl-3">
								<div class="pbminfotech-post-item">
									<div class="pbminfotech-box-content">
										<div class="pbminfotech-box-content-inner">
											<div class="pbmit-featured-wrapper pbmit-hover-img">
												<div class="pbmit-featured-img-wrapper">
													<div class="pbmit-featured-wrapper">
														<img src="images/homepage-2/team/team-img-06.jpg"
															class="img-fluid" alt="">
													</div>
												</div>
											</div>
											<h3 class="pbmit-team-title">
												<a href="team-member-detail.html">Michael Grey</a>
											</h3>
											<div class="pbminfotech-box-team-position">Program Manager</div>
										</div>
									</div>
								</div>
							</article>
							<article class="pbmit-team-style-4 col-md-6 col-lg-4 col-xl-3">
								<div class="pbminfotech-post-item">
									<div class="pbminfotech-box-content">
										<div class="pbminfotech-box-content-inner">
											<div class="pbmit-featured-wrapper pbmit-hover-img">
												<div class="pbmit-featured-img-wrapper">
													<div class="pbmit-featured-wrapper">
														<img src="images/homepage-2/team/team-img-07.html"
															class="img-fluid" alt="">
													</div>
												</div>
											</div>
											<h3 class="pbmit-team-title">
												<a href="team-member-detail.html">John Martin</a>
											</h3>
											<div class="pbminfotech-box-team-position">Delivery Manager</div>
										</div>
									</div>
								</div>
							</article>
							<article class="pbmit-team-style-4 col-md-6 col-lg-4 col-xl-3">
								<div class="pbminfotech-post-item">
									<div class="pbminfotech-box-content">
										<div class="pbminfotech-box-content-inner">
											<div class="pbmit-featured-wrapper pbmit-hover-img">
												<div class="pbmit-featured-img-wrapper">
													<div class="pbmit-featured-wrapper">
														<img src="images/homepage-2/team/team-img-08.jpg"
															class="img-fluid" alt="">
													</div>
												</div>
											</div>
											<h3 class="pbmit-team-title">
												<a href="team-member-detail.html">Dan Wilkinson</a>
											</h3>
											<div class="pbminfotech-box-team-position">General Manager</div>
										</div>
									</div>
								</div>
							</article>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section> -->
	<!-- Team End -->

	<!-- Our Process Start -->
	<section class="process-section-two pbmit-element-miconheading-style-14" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h4 class="pbmit-subtitle">Our Process</h4>
				<h2 class="pbmit-title">Wind Solar Energy work<br> Project Planning</h2>
			</div>
			<div class="row pbminfotech-gap-50px">
				<article class="pbmit-miconheading-style-14 col-md-6 col-lg-4 col-xl-3">
					<div class="pbmit-ihbox-style-14">
						<div class="pbmit-ihbox-box">
							<div class="pbmit-ihbox-icon">
								<div class="pbmit-ihbox-icon-wrapper pbmit-ihbox-icon-type-image">
									<img src="images/homepage-2/ihbox/image-01.jpg" alt="System Design">
								</div>
							</div>
							<div class="pbmit-ihbox-box-number-wrapper">
								<div class="pbmit-ihbox-box-number">01</div>
							</div>
							<h2 class="pbmit-element-title">
								System Design
							</h2>
							<div class="pbmit-heading-desc">Tailoring efficient and sustainable solar energy systems to
								meet your specific needs.</div>
						</div>
					</div>
				</article>
				<article class="pbmit-miconheading-style-14 col-md-6 col-lg-4 col-xl-3">
					<div class="pbmit-ihbox-style-14">
						<div class="pbmit-ihbox-box">
							<div class="pbmit-ihbox-icon">
								<div class="pbmit-ihbox-icon-wrapper pbmit-ihbox-icon-type-image">
									<img src="images/homepage-2/ihbox/image-02.jpg" alt="Panel Installation">
								</div>
							</div>
							<div class="pbmit-ihbox-box-number-wrapper">
								<div class="pbmit-ihbox-box-number">02</div>
							</div>
							<h2 class="pbmit-element-title">
								Panel Installation
							</h2>
							<div class="pbmit-heading-desc">Expert installation of high-quality solar panels for maximum
								energy capture.</div>
						</div>
					</div>
				</article>
				<article class="pbmit-miconheading-style-14 col-md-6 col-lg-4 col-xl-3">
					<div class="pbmit-ihbox-style-14">
						<div class="pbmit-ihbox-box">
							<div class="pbmit-ihbox-icon">
								<div class="pbmit-ihbox-icon-wrapper pbmit-ihbox-icon-type-image">
									<img src="images/homepage-2/ihbox/image-03.png" alt="Inverter Integration">
								</div>
							</div>
							<div class="pbmit-ihbox-box-number-wrapper">
								<div class="pbmit-ihbox-box-number">03</div>
							</div>
							<h2 class="pbmit-element-title">
								Inverter Integration
							</h2>
							<div class="pbmit-heading-desc">Seamlessly converting solar energy into usable electricity
								with advanced inverters.</div>
						</div>
					</div>
				</article>
				<article class="pbmit-miconheading-style-14 col-md-6 col-lg-4 col-xl-3">
					<div class="pbmit-ihbox-style-14">
						<div class="pbmit-ihbox-box">
							<div class="pbmit-ihbox-icon">
								<div class="pbmit-ihbox-icon-wrapper pbmit-ihbox-icon-type-image">
									<img src="images/homepage-2/ihbox/image-04.jpg" alt="Battery Solutions">
								</div>
							</div>
							<div class="pbmit-ihbox-box-number-wrapper">
								<div class="pbmit-ihbox-box-number">04</div>
							</div>
							<h2 class="pbmit-element-title">
								Battery Solutions
							</h2>
							<div class="pbmit-heading-desc">Panel installation involves the professional installation
								expert panel maintenance.</div>
						</div>
					</div>
				</article>
			</div>
		</div>
	</section>
	<!-- Our Process Start -->

	<!-- Marquee Start -->
	<section class="section-lgb" data-aos="fade-up" data-aos-duration="800">
		<div class="container-fluid p-0">
			<div class="swiper-slider marquee">
				<div class="swiper-wrapper">
					<article class="pbmit-marquee-effect-style-2 swiper-slide">
						<div class="pbmit-tag-wrapper">
							<h2 class="pbmit-element-title" data-text="Sustainable">
								Sustainable
							</h2>
						</div>
					</article>
					<article class="pbmit-marquee-effect-style-2 swiper-slide">
						<div class="pbmit-tag-wrapper">
							<h2 class="pbmit-element-title" data-text="Smart solar">
								Smart solar
							</h2>
						</div>
					</article>
					<article class="pbmit-marquee-effect-style-2 swiper-slide">
						<div class="pbmit-tag-wrapper">
							<h2 class="pbmit-element-title" data-text="Turbine Technology">
								Turbine Technology
							</h2>
						</div>
					</article>
					<article class="pbmit-marquee-effect-style-2 swiper-slide">
						<div class="pbmit-tag-wrapper">
							<h2 class="pbmit-element-title" data-text="Electricity">
								Electricity
							</h2>
						</div>
					</article>
				</div>
			</div>
		</div>
	</section>
	<!-- Marquee End -->


	<section class="section-xl home-testimonials" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title">What Our Clients Say</h2>
			</div>
			<div class="row justify-content-center">
				<div class="col-lg-10 col-xl-9">
					<article class="pbmit-testimonial-style-2">
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
										<p>“Partnering with Shivanjali Renewables for our 900 kW solar project has been
											a transformative experience. Their expertise, professionalism, and
											commitment to quality ensured the successful completion of our project. We
											are delighted with the energy savings and sustainability impact we have
											achieved.”</p>
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
				</div>
			</div>
		</div>
	</section>

	<!-- Blog start -->
	<section class="blog-section-two" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="d-md-flex align-items-center justify-content-between">
				<div class="pbmit-heading-subheading">
					<h4 class="pbmit-subtitle">Latest News</h4>
					<h2 class="pbmit-title">Latest from the Blog</h2>
				</div>
				<div class="mb-5 pbmit-blog-btn">
					<a href="blog-grid-col-3.html" class="pbmit-btn outline">
						<span class="pbmit-button-text">View All Post</span>
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 col-lg-4">
					<article class="pbmit-blog-style-4">
						<div class="post-item">
							<div class="pbminfotech-box-content">
								<div class="pbmit-date-wraper d-flex align-items-center">
									<div class="pbmit-meta-category-wrapper pbmit-meta-line">
										<div class="pbmit-meta-category">
											<a href="blog-classic.html" rel="category tag">Electricity</a>
										</div>
									</div>
									<div class="pbmit-meta-date pbmit-meta-line">
										<span class="pbmit-post-date">27 Dec, 2024</span>
									</div>
								</div>
								<div class="pbmit-featured-container">
									<div class="pbmit-featured-img-wrapper">
										<div class="pbmit-featured-wrapper">
											<img src="images/homepage-2/blog/blog-img-01.jpg" class="img-fluid" alt="">
										</div>
									</div>
								</div>
								<div class="pbmit-content-wrapper">
									<h3 class="pbmit-post-title">
										<a href="blog-single-details.html">How does solar power affect the
											environment?</a>
									</h3>
									<div class="pbmit-blog-button">
										<a class="pbmit-button-inner" href="blog-single-details.html"
											title="How does solar power affect the environment?">
											<span class="pbmit-button-icon">Read More</span>
											<i class="demo-icon pbmit-base-icon-arrow-right"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
					</article>
				</div>
				<div class="col-md-6 col-lg-4">
					<article class="pbmit-blog-style-4">
						<div class="post-item">
							<div class="pbminfotech-box-content">
								<div class="pbmit-date-wraper d-flex align-items-center">
									<div class="pbmit-meta-category-wrapper pbmit-meta-line">
										<div class="pbmit-meta-category">
											<a href="blog-classic.html" rel="category tag">Hydrogenium</a>
										</div>
									</div>
									<div class="pbmit-meta-date pbmit-meta-line">
										<span class="pbmit-post-date">27 Dec, 2024</span>
									</div>
								</div>
								<div class="pbmit-featured-container">
									<div class="pbmit-featured-img-wrapper">
										<div class="pbmit-featured-wrapper">
											<img src="images/homepage-2/blog/blog-img-02.jpg" class="img-fluid" alt="">
										</div>
									</div>
								</div>
								<div class="pbmit-content-wrapper">
									<h3 class="pbmit-post-title">
										<a href="blog-single-details.html">Do Solar Panels Work on Commercial
											Buildings</a>
									</h3>
									<div class="pbmit-blog-button">
										<a class="pbmit-button-inner" href="blog-single-details.html"
											title="Do Solar Panels Work on Commercial Buildings">
											<span class="pbmit-button-icon">Read More</span>
											<i class="demo-icon pbmit-base-icon-arrow-right"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
					</article>
				</div>
				<div class="col-md-6 col-lg-4">
					<article class="pbmit-blog-style-4">
						<div class="post-item">
							<div class="pbminfotech-box-content">
								<div class="pbmit-date-wraper d-flex align-items-center">
									<div class="pbmit-meta-category-wrapper pbmit-meta-line">
										<div class="pbmit-meta-category">
											<a href="blog-classic.html" rel="category tag">Development</a>
										</div>
									</div>
									<div class="pbmit-meta-date pbmit-meta-line">
										<span class="pbmit-post-date">27 Dec, 2024</span>
									</div>
								</div>
								<div class="pbmit-featured-container">
									<div class="pbmit-featured-img-wrapper">
										<div class="pbmit-featured-wrapper">
											<img src="images/homepage-2/blog/blog-img-03.jpg" class="img-fluid" alt="">
										</div>
									</div>
								</div>
								<div class="pbmit-content-wrapper">
									<h3 class="pbmit-post-title">
										<a href="blog-single-details.html">Southeast Florida Regional Climate Change
											Compact</a>
									</h3>
									<div class="pbmit-blog-button">
										<a class="pbmit-button-inner" href="blog-single-details.html"
											title="Southeast Florida Regional Climate Change Compact">
											<span class="pbmit-button-icon">Read More</span>
											<i class="demo-icon pbmit-base-icon-arrow-right"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
					</article>
				</div>
			</div>
		</div>
	</section>
	<!-- Blog End -->
	<section class="pbmit-bg-color-global" style="padding: 70px 0;">
		<div class="container">
			<div class="row align-items-center g-4">
				<div class="col-lg-8">
					<h2
						style="color: var(--pbmit-white-color); font-size: 46px; line-height: 54px; margin-bottom: 12px;">
						Ready to Switch to Solar?</h2>
					<p style="color: rgba(255,255,255,0.92); margin: 0;">Get a free, no-obligation solar assessment from
						our experts. We will evaluate your energy needs and design the perfect system for you.</p>
				</div>
				<div class="col-lg-4 text-lg-end">
					<div class="d-flex flex-wrap gap-3 justify-content-lg-end cta-bar">
						<a href="/contact" class="pbmit-btn outline cta-secondary">
							<span class="pbmit-button-text">Get Free Quote</span>
						</a>
						<a href="tel:+918686313133" class="pbmit-btn outline cta-secondary">
							<span class="pbmit-button-text">Call Us Now</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<!-- Page Content End -->

<?php include 'includes/footer.php'; ?>
