<?php include 'includes/header.php'; ?>
<?php
$sr_page = sr_cms_page_get('about');
$sr_hero_title = $sr_page && trim((string)$sr_page['hero_title']) !== '' ? (string)$sr_page['hero_title'] : 'Illuminating the Path to a Sustainable Future';
$sr_hero_subtitle = $sr_page && trim((string)$sr_page['hero_subtitle']) !== '' ? (string)$sr_page['hero_subtitle'] : 'Born in Nashik. Built for India. Driven by a clean-energy mission.';
$sr_banner_image = $sr_page && trim((string)($sr_page['banner_image'] ?? '')) !== '' ? (string)$sr_page['banner_image'] : '';
$sr_page_override = $sr_page && trim((string)($sr_page['content'] ?? '')) !== '' ? (string)$sr_page['content'] : '';

$sr_story_subtitle = sr_cms_setting_get('about_story_subtitle', 'About Us');
$sr_story_title = sr_cms_setting_get('about_story_title', 'Our Story');
$sr_story_p1 = sr_cms_setting_get('about_story_p1', "Shivanjali Renewables Pvt. Ltd. was founded with a singular vision: to accelerate India's transition to clean, renewable energy. Starting from Nashik — the heart of Maharashtra — we have grown into a full-service Solar EPC company trusted by homeowners, industries, and businesses across the region.");
$sr_story_p2 = sr_cms_setting_get('about_story_p2', 'Every project we undertake is powered by a commitment to quality, a passion for sustainability, and a drive to deliver real value to our customers. From a small rooftop installation in a residential colony to a 20 MW open-access solar park, we bring the same level of dedication and technical excellence to every assignment.');
$sr_story_p3 = sr_cms_setting_get('about_story_p3', 'Our name, Shivanjali, is a tribute to our roots — a blend of strength and dedication that defines our work ethic every single day.');
$sr_story_img1 = sr_cms_setting_get('about_story_img1', 'images/banner-slider-img/Slider01-2.jpg');
$sr_story_img2 = sr_cms_setting_get('about_story_img2', 'images/banner-slider-img/Slider02-3.jpg');

$sr_vm_title = sr_cms_setting_get('about_vm_title', 'OUR VISION & MISSION');
$sr_vision_desc = sr_cms_setting_get('about_vision_desc', 'To be a global leader in the solar energy sector, pioneering innovation and fostering the widespread adoption of sustainable renewable energy solutions.');
$sr_mission_desc = sr_cms_setting_get('about_mission_desc', 'To deliver affordable, efficient, and high-quality solar solutions, empowering individuals and organisations to transition to renewable energy while actively contributing to environmental sustainability.');

$sr_values_subtitle = sr_cms_setting_get('about_values_subtitle', 'Core Values');
$sr_values_title = sr_cms_setting_get('about_values_title', 'What We Stand For');
$sr_value1_title = sr_cms_setting_get('about_value1_title', 'Innovation');
$sr_value1_desc = sr_cms_setting_get('about_value1_desc', 'We pioneer the latest advancements in solar technology to deliver unmatched performance and future-ready energy systems.');
$sr_value2_title = sr_cms_setting_get('about_value2_title', 'Sustainability');
$sr_value2_desc = sr_cms_setting_get('about_value2_desc', 'We are committed to promoting renewable energy as the foundation of a cleaner, healthier planet for generations to come.');
$sr_value3_title = sr_cms_setting_get('about_value3_title', 'Energy Efficiency');
$sr_value3_desc = sr_cms_setting_get('about_value3_desc', 'Every system we design maximises energy yield while minimising waste, helping clients get the most from every ray of sunshine.');

$sr_leadership_subtitle = sr_cms_setting_get('about_leadership_subtitle', 'Leadership Team');
$sr_leadership_title = sr_cms_setting_get('about_leadership_title', 'Meet Our Leadership');
$sr_leader1_name = sr_cms_setting_get('about_leader1_name', 'Anjali Shivaji Chavanke');
$sr_leader1_role = sr_cms_setting_get('about_leader1_role', 'Managing Director (MD)');
$sr_leader1_photo = sr_cms_setting_get('about_leader1_photo', 'images/homepage-1/team/team-img-01.jpg');
$sr_leader2_name = sr_cms_setting_get('about_leader2_name', 'Abhijeet Shivaji Chavanke');
$sr_leader2_role = sr_cms_setting_get('about_leader2_role', 'Chief Executive Officer (CEO)');
$sr_leader2_photo = sr_cms_setting_get('about_leader2_photo', 'images/homepage-1/team/team-img-02.jpg');
$sr_founder_subtitle = sr_cms_setting_get('about_founder_subtitle', 'Founder / CEO Message');
$sr_founder_quote = sr_cms_setting_get('about_founder_quote', '"At Shivanjali Renewables, our goal is to lead the transition toward clean energy. We are dedicated to delivering cutting-edge solar solutions that empower communities and businesses to embrace sustainability. Together, we can build a brighter and greener future."');

$sr_history_subtitle = sr_cms_setting_get('about_history_subtitle', 'Achievements / Milestones');
$sr_history_title = sr_cms_setting_get('about_history_title', 'Milestones That Define Us');
$sr_h1_year = sr_cms_setting_get('about_history1_year', '01');
$sr_h1_title = sr_cms_setting_get('about_history1_title', 'Founded');
$sr_h1_desc = sr_cms_setting_get('about_history1_desc', 'Shivanjali Renewables established in Nashik, Maharashtra.');
$sr_h1_img = sr_cms_setting_get('about_history1_image', 'images/history/history-img-01.jpg');
$sr_h2_year = sr_cms_setting_get('about_history2_year', '02');
$sr_h2_title = sr_cms_setting_get('about_history2_title', 'First commercial project');
$sr_h2_desc = sr_cms_setting_get('about_history2_desc', '50 kW rooftop installation for an industrial client.');
$sr_h2_img = sr_cms_setting_get('about_history2_image', 'images/history/history-img-02.jpg');
$sr_h3_year = sr_cms_setting_get('about_history3_year', '03');
$sr_h3_title = sr_cms_setting_get('about_history3_title', 'Crossed 1 MW capacity');
$sr_h3_desc = sr_cms_setting_get('about_history3_desc', 'Crossed 1 MW cumulative installed capacity.');
$sr_h3_img = sr_cms_setting_get('about_history3_image', 'images/history/history-img-03.jpg');
$sr_h4_year = sr_cms_setting_get('about_history4_year', '04');
$sr_h4_title = sr_cms_setting_get('about_history4_title', 'Open Access Solar');
$sr_h4_desc = sr_cms_setting_get('about_history4_desc', 'Launched Open Access Solar division for large-scale industrial clients.');
$sr_h4_img = sr_cms_setting_get('about_history4_image', 'images/history/history-img-04.jpg');
$sr_h5_year = sr_cms_setting_get('about_history5_year', '05');
$sr_h5_title = sr_cms_setting_get('about_history5_title', '900 kW project completed');
$sr_h5_desc = sr_cms_setting_get('about_history5_desc', 'Completed 900 kW project for Varun Agro Food Processing Pvt. Ltd.');
$sr_h5_img = sr_cms_setting_get('about_history5_image', 'images/history/history-img-05.jpg');
$sr_h6_year = sr_cms_setting_get('about_history6_year', '06');
$sr_h6_title = sr_cms_setting_get('about_history6_title', '20 MW+ installed');
$sr_h6_desc = sr_cms_setting_get('about_history6_desc', 'Crossed 20 MW+ total installed solar capacity.');
$sr_h6_img = sr_cms_setting_get('about_history6_image', 'images/history/history-img-06.jpg');
?>
<!-- Title Bar -->
<div class="pbmit-title-bar-wrapper sr-projects-hero"<?php echo $sr_banner_image !== '' ? (' style="background-image:url(' . htmlspecialchars($sr_banner_image, ENT_QUOTES, 'UTF-8') . ');"') : ''; ?>>
	<div class="container">
		<div class="pbmit-title-bar-content">
			<div class="pbmit-title-bar-content-inner">
				<div class="pbmit-tbar">
					<div class="pbmit-tbar-inner container">
						<h1 class="pbmit-tbar-title"><?php echo htmlspecialchars($sr_hero_title, ENT_QUOTES, 'UTF-8'); ?></h1>
						<?php if (trim($sr_hero_subtitle) !== '') { ?>
							<p class="text-white mb-0 mt-2"><?php echo $sr_hero_subtitle; ?></p>
						<?php } ?>
					</div>
				</div>
				<div class="pbmit-breadcrumb">
					<div class="pbmit-breadcrumb-inner">
						<span><a title="" href="./" class="home"><span>Home</span></a></span>
						<i class="pbmit-base-icon-arrow-right-2"></i>
						<span><span class="post-root post post-post current-item"> About Us</span></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

</header>
<!-- Header Main Area End Here -->


<!-- Page Content -->
<div class="page-content about-us">
	<?php if ($sr_page_override !== '') { ?>
		<?php echo $sr_page_override; ?>
	<?php } else { ?>

	<!-- About Start -->
	<section class="section-xl sr-about" id="our-story">
		<div class="container">
			<div class="row align-items-center g-4 g-lg-5">
				<div class="col-lg-6" data-aos="fade-up" data-aos-duration="800">
					<div class="sr-about-media">
						<div class="sr-about-media-top">
							<img src="<?php echo htmlspecialchars($sr_story_img1, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid"
								alt="Solar project at sunset">
						</div>
						<div class="sr-about-media-bottom">
							<img src="<?php echo htmlspecialchars($sr_story_img2, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid"
								alt="Renewable energy landscape">
						</div>
					</div>
				</div>
				<div class="col-lg-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="150">
					<div class="sr-about-content">
						<div class="pbmit-heading-subheading">
							<h4 class="pbmit-subtitle"><?php echo htmlspecialchars($sr_story_subtitle, ENT_QUOTES, 'UTF-8'); ?></h4>
							<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_story_title, ENT_QUOTES, 'UTF-8'); ?></h2>
						</div>
						<p><?php echo nl2br(htmlspecialchars($sr_story_p1, ENT_QUOTES, 'UTF-8')); ?></p>
						<p><?php echo nl2br(htmlspecialchars($sr_story_p2, ENT_QUOTES, 'UTF-8')); ?></p>
						<p class="mb-0"><?php echo nl2br(htmlspecialchars($sr_story_p3, ENT_QUOTES, 'UTF-8')); ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- About Start -->

	<section class="section-xl sr-vision-mission" id="vision-mission" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="sr-vm-header text-center">
				<h2 class="sr-vm-title"><?php echo htmlspecialchars($sr_vm_title, ENT_QUOTES, 'UTF-8'); ?></h2>
			</div>
			<div class="sr-vm-decor d-none">
				<div class="sr-vm-icon sr-vm-icon-left" aria-hidden="true">
					<svg viewBox="0 0 64 64" role="presentation">
						<path
							d="M32 58c14.36 0 26-11.64 26-26S46.36 6 32 6 6 17.64 6 32s11.64 26 26 26Z"
							fill="none" stroke="currentColor" stroke-width="2" />
						<path d="M32 46c7.73 0 14-6.27 14-14s-6.27-14-14-14-14 6.27-14 14 6.27 14 14 14Z"
							fill="none" stroke="currentColor" stroke-width="2" />
						<path d="M32 32l10-10" fill="none" stroke="currentColor" stroke-width="2"
							stroke-linecap="round" />
						<path d="M44 22l2 8-8-2 6-6Z" fill="none" stroke="currentColor" stroke-width="2"
							stroke-linejoin="round" />
					</svg>
				</div>
				<div class="sr-vm-icon sr-vm-icon-center" aria-hidden="true">
					<svg viewBox="0 0 64 64" role="presentation">
						<path
							d="M32 6c-9.39 0-17 7.61-17 17 0 7.05 4.31 13.09 10.44 15.65V46h13.12v-7.35C44.69 36.09 49 30.05 49 23 49 13.61 41.39 6 32 6Z"
							fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
						<path d="M26 52h12M28 58h8" fill="none" stroke="currentColor" stroke-width="2"
							stroke-linecap="round" />
						<path d="M25 46h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
					</svg>
				</div>
				<div class="sr-vm-icon sr-vm-icon-right" aria-hidden="true">
					<svg viewBox="0 0 64 64" role="presentation">
						<path
							d="M32 58c14.36 0 26-11.64 26-26S46.36 6 32 6 6 17.64 6 32s11.64 26 26 26Z"
							fill="none" stroke="currentColor" stroke-width="2" />
						<path d="M20 40c4 4 9 6 12 6s8-2 12-6" fill="none" stroke="currentColor" stroke-width="2"
							stroke-linecap="round" />
						<path d="M22 24h20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
						<path d="M24 20h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
					</svg>
				</div>
			</div>
			<div class="row g-4 pt-4">
				<div class="col-lg-6">
					<div class="sr-vm-card sr-vm-card--vision">
						<h3 class="sr-vm-label sr-vm-label--vision">
							<span class="sr-vm-label-icon sr-vm-label-icon--vision" aria-hidden="true">
								<svg viewBox="0 0 64 64" role="presentation">
									<circle cx="32" cy="32" r="26" fill="none" stroke="currentColor" stroke-width="2" />
									<circle cx="32" cy="32" r="10" fill="none" stroke="currentColor" stroke-width="2" />
									<path d="M32 22v20M22 32h20" fill="none" stroke="currentColor" stroke-width="2" />
									<path d="M44 18l-4 10" fill="none" stroke="currentColor" stroke-width="2" />
								</svg>
							</span>
							<span class="sr-vm-label-text">VISION</span>
						</h3>
						<p class="mb-0"><?php echo nl2br(htmlspecialchars($sr_vision_desc, ENT_QUOTES, 'UTF-8')); ?></p>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="sr-vm-card sr-vm-card--mission">
						<h3 class="sr-vm-label sr-vm-label--mission">
							<span class="sr-vm-label-icon sr-vm-label-icon--mission" aria-hidden="true">
								<svg viewBox="0 0 64 64" role="presentation">
									<path d="M32 8c-9 0-16 7-16 16 0 6 4 11 9 13v5h14v-5c5-2 9-7 9-13 0-9-7-16-16-16Z"
										fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
									<path d="M26 50h12M28 56h8" fill="none" stroke="currentColor" stroke-width="2"
										stroke-linecap="round" />
								</svg>
							</span>
							<span class="sr-vm-label-text">MISSION</span>
						</h3>
						<p class="mb-0"><?php echo nl2br(htmlspecialchars($sr_mission_desc, ENT_QUOTES, 'UTF-8')); ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section-xl sr-core-values" id="core-values" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h4 class="pbmit-subtitle"><?php echo htmlspecialchars($sr_values_subtitle, ENT_QUOTES, 'UTF-8'); ?></h4>
				<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_values_title, ENT_QUOTES, 'UTF-8'); ?></h2>
			</div>
			<ul class="sr-values-list">
				<li class="sr-values-item">
					<div class="sr-value-dot" aria-hidden="true"></div>
					<div class="sr-value-content">
						<h3 class="sr-value-title"><?php echo htmlspecialchars($sr_value1_title, ENT_QUOTES, 'UTF-8'); ?></h3>
						<p class="mb-0"><?php echo nl2br(htmlspecialchars($sr_value1_desc, ENT_QUOTES, 'UTF-8')); ?></p>
					</div>
					<div class="sr-value-icon" aria-hidden="true">
						<svg viewBox="0 0 64 64" role="presentation">
							<path
								d="M32 6c-9.39 0-17 7.61-17 17 0 7.05 4.31 13.09 10.44 15.65V46h13.12v-7.35C44.69 36.09 49 30.05 49 23 49 13.61 41.39 6 32 6Z"
								fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
							<path d="M26 52h12M28 58h8" fill="none" stroke="currentColor" stroke-width="2"
								stroke-linecap="round" />
							<path d="M25 46h14" fill="none" stroke="currentColor" stroke-width="2"
								stroke-linecap="round" />
						</svg>
					</div>
				</li>
				<li class="sr-values-item">
					<div class="sr-value-dot" aria-hidden="true"></div>
					<div class="sr-value-content">
						<h3 class="sr-value-title"><?php echo htmlspecialchars($sr_value2_title, ENT_QUOTES, 'UTF-8'); ?></h3>
						<p class="mb-0"><?php echo nl2br(htmlspecialchars($sr_value2_desc, ENT_QUOTES, 'UTF-8')); ?></p>
					</div>
					<div class="sr-value-icon" aria-hidden="true">
						<svg viewBox="0 0 64 64" role="presentation">
							<path
								d="M12 36c0-14 12-26 26-26h14v14c0 14-12 26-26 26H12V36Z"
								fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
							<path d="M22 44c7-14 18-22 30-26" fill="none" stroke="currentColor" stroke-width="2"
								stroke-linecap="round" />
						</svg>
					</div>
				</li>
				<li class="sr-values-item">
					<div class="sr-value-dot" aria-hidden="true"></div>
					<div class="sr-value-content">
						<h3 class="sr-value-title"><?php echo htmlspecialchars($sr_value3_title, ENT_QUOTES, 'UTF-8'); ?></h3>
						<p class="mb-0"><?php echo nl2br(htmlspecialchars($sr_value3_desc, ENT_QUOTES, 'UTF-8')); ?></p>
					</div>
					<div class="sr-value-icon" aria-hidden="true">
						<svg viewBox="0 0 64 64" role="presentation">
							<path d="M32 6v10M32 48v10M6 32h10M48 32h10" fill="none" stroke="currentColor"
								stroke-width="2" stroke-linecap="round" />
							<path d="M24 58l8-18h-8l16-22-8 18h8L24 58Z" fill="none" stroke="currentColor"
								stroke-width="2" stroke-linejoin="round" />
						</svg>
					</div>
				</li>
			</ul>
		</div>
	</section>

	<section class="section-xl sr-leadership" id="leadership-team" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h4 class="pbmit-subtitle"><?php echo htmlspecialchars($sr_leadership_subtitle, ENT_QUOTES, 'UTF-8'); ?></h4>
				<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_leadership_title, ENT_QUOTES, 'UTF-8'); ?></h2>
			</div>
			<div class="sr-leaders">
				<div class="sr-leader">
					<div class="sr-leader-photo">
						<img src="<?php echo htmlspecialchars($sr_leader1_photo, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid"
							alt="<?php echo htmlspecialchars($sr_leader1_name, ENT_QUOTES, 'UTF-8'); ?>">
					</div>
					<div class="sr-leader-name"><?php echo htmlspecialchars($sr_leader1_name, ENT_QUOTES, 'UTF-8'); ?></div>
					<div class="sr-leader-role"><?php echo htmlspecialchars($sr_leader1_role, ENT_QUOTES, 'UTF-8'); ?></div>
				</div>
				<div class="sr-leader">
					<div class="sr-leader-photo">
						<img src="<?php echo htmlspecialchars($sr_leader2_photo, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid"
							alt="<?php echo htmlspecialchars($sr_leader2_name, ENT_QUOTES, 'UTF-8'); ?>">
					</div>
					<div class="sr-leader-name"><?php echo htmlspecialchars($sr_leader2_name, ENT_QUOTES, 'UTF-8'); ?></div>
					<div class="sr-leader-role"><?php echo htmlspecialchars($sr_leader2_role, ENT_QUOTES, 'UTF-8'); ?></div>
				</div>
			</div>
			<div class="pbmit-heading-subheading text-center">
				<h4 class="pbmit-subtitle"><?php echo htmlspecialchars($sr_founder_subtitle, ENT_QUOTES, 'UTF-8'); ?></h4>
			</div>
			<div class="sr-founder-message">
				<p class="mb-0"><?php echo nl2br(htmlspecialchars($sr_founder_quote, ENT_QUOTES, 'UTF-8')); ?></p>
			</div>
		</div>
	</section>

	<section class="our-history" id="milestones" data-aos="fade-up" data-aos-duration="800">
		<div class="container-fluid p-0">
			<div class="pbmit-heading-subheading text-center">
				<h4 class="pbmit-subtitle"><?php echo htmlspecialchars($sr_history_subtitle, ENT_QUOTES, 'UTF-8'); ?></h4>
				<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_history_title, ENT_QUOTES, 'UTF-8'); ?></h2>
			</div>
			<!-- <div class="sr-timeline-helper text-center" role="status" aria-live="polite">
				<div class="sr-timeline-helper-inner">
					<span class="sr-timeline-badge" aria-hidden="true">Auto</span>
					<span class="sr-timeline-text">Auto-advancing timeline • Swipe or use arrows</span>
				</div>
			</div> -->
			<div class="swiper-slider pbmit-timeline-style-1" data-autoplay="true" data-loop="false" data-dots="true"
				data-arrows="true" data-columns="3" data-margin="30" data-effect="slide">
				<div class="swiper-wrapper">
					<div class="pbmit-timeline-wrapper swiper-slide pbmit-slide-even">
						<div class="pbmit-same-height steps-media pbmit-feature-image">
							<img src="<?php echo htmlspecialchars($sr_h1_img, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($sr_h1_title, ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="steps-dot">
							<i class="steps-dot-line"></i>
							<span class="dot"></span>
						</div>
						<div class="pbmit-same-height steps-content_wrap">
							<p class="pbmit-timeline-year"><?php echo htmlspecialchars($sr_h1_year, ENT_QUOTES, 'UTF-8'); ?></p>
							<h3 class="pbmit-timeline-title"><?php echo htmlspecialchars($sr_h1_title, ENT_QUOTES, 'UTF-8'); ?></h3>
							<p class="pbmit-timeline-desc"><?php echo htmlspecialchars($sr_h1_desc, ENT_QUOTES, 'UTF-8'); ?></p>
						</div>
					</div>
					<div class="pbmit-timeline-wrapper swiper-slide">
						<div class="pbmit-same-height steps-media pbmit-feature-image">
							<img src="<?php echo htmlspecialchars($sr_h2_img, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($sr_h2_title, ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="steps-dot">
							<i class="steps-dot-line"></i>
							<span class="dot"></span>
						</div>
						<div class="pbmit-same-height steps-content_wrap">
							<p class="pbmit-timeline-year"><?php echo htmlspecialchars($sr_h2_year, ENT_QUOTES, 'UTF-8'); ?></p>
							<h3 class="pbmit-timeline-title"><?php echo htmlspecialchars($sr_h2_title, ENT_QUOTES, 'UTF-8'); ?></h3>
							<p class="pbmit-timeline-desc"><?php echo htmlspecialchars($sr_h2_desc, ENT_QUOTES, 'UTF-8'); ?></p>
						</div>
					</div>
					<div class="pbmit-timeline-wrapper swiper-slide pbmit-slide-even">
						<div class="pbmit-same-height steps-media pbmit-feature-image">
							<img src="<?php echo htmlspecialchars($sr_h3_img, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($sr_h3_title, ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="steps-dot">
							<i class="steps-dot-line"></i>
							<span class="dot"></span>
						</div>
						<div class="pbmit-same-height steps-content_wrap">
							<p class="pbmit-timeline-year"><?php echo htmlspecialchars($sr_h3_year, ENT_QUOTES, 'UTF-8'); ?></p>
							<h3 class="pbmit-timeline-title"><?php echo htmlspecialchars($sr_h3_title, ENT_QUOTES, 'UTF-8'); ?></h3>
							<p class="pbmit-timeline-desc"><?php echo htmlspecialchars($sr_h3_desc, ENT_QUOTES, 'UTF-8'); ?></p>
						</div>
					</div>
					<div class="pbmit-timeline-wrapper swiper-slide">
						<div class="pbmit-same-height steps-media pbmit-feature-image">
							<img src="<?php echo htmlspecialchars($sr_h4_img, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($sr_h4_title, ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="steps-dot">
							<i class="steps-dot-line"></i>
							<span class="dot"></span>
						</div>
						<div class="pbmit-same-height steps-content_wrap">
							<p class="pbmit-timeline-year"><?php echo htmlspecialchars($sr_h4_year, ENT_QUOTES, 'UTF-8'); ?></p>
							<h3 class="pbmit-timeline-title"><?php echo htmlspecialchars($sr_h4_title, ENT_QUOTES, 'UTF-8'); ?></h3>
							<p class="pbmit-timeline-desc"><?php echo htmlspecialchars($sr_h4_desc, ENT_QUOTES, 'UTF-8'); ?></p>
						</div>
					</div>
					<div class="pbmit-timeline-wrapper swiper-slide pbmit-slide-even">
						<div class="pbmit-same-height steps-media pbmit-feature-image">
							<img src="<?php echo htmlspecialchars($sr_h5_img, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($sr_h5_title, ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="steps-dot">
							<i class="steps-dot-line"></i>
							<span class="dot"></span>
						</div>
						<div class="pbmit-same-height steps-content_wrap">
							<p class="pbmit-timeline-year"><?php echo htmlspecialchars($sr_h5_year, ENT_QUOTES, 'UTF-8'); ?></p>
							<h3 class="pbmit-timeline-title"><?php echo htmlspecialchars($sr_h5_title, ENT_QUOTES, 'UTF-8'); ?></h3>
							<p class="pbmit-timeline-desc"><?php echo htmlspecialchars($sr_h5_desc, ENT_QUOTES, 'UTF-8'); ?></p>
						</div>
					</div>
					<div class="pbmit-timeline-wrapper swiper-slide">
						<div class="pbmit-same-height steps-media pbmit-feature-image">
							<img src="<?php echo htmlspecialchars($sr_h6_img, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($sr_h6_title, ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="steps-dot">
							<i class="steps-dot-line"></i>
							<span class="dot"></span>
						</div>
						<div class="pbmit-same-height steps-content_wrap">
							<p class="pbmit-timeline-year"><?php echo htmlspecialchars($sr_h6_year, ENT_QUOTES, 'UTF-8'); ?></p>
							<h3 class="pbmit-timeline-title"><?php echo htmlspecialchars($sr_h6_title, ENT_QUOTES, 'UTF-8'); ?></h3>
							<p class="pbmit-timeline-desc"><?php echo htmlspecialchars($sr_h6_desc, ENT_QUOTES, 'UTF-8'); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php } ?>
</div>
<!-- Page Content End -->
<?php include 'includes/footer.php'; ?>
