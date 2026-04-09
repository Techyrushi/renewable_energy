<?php include 'includes/header.php'; ?>
<?php
$sr_page = sr_cms_page_get('operations-maintenance');
$sr_title = $sr_page && trim((string)$sr_page['hero_title']) !== '' ? (string)$sr_page['hero_title'] : 'Operations &amp; Maintenance (O&amp;M)';
$sr_section_title = $sr_page && trim((string)$sr_page['title']) !== '' ? (string)$sr_page['title'] : 'Operations &amp; Maintenance';
$sr_subtitle = $sr_page && trim((string)$sr_page['hero_subtitle']) !== '' ? (string)$sr_page['hero_subtitle'] : 'A solar system delivers maximum returns only when it is maintained correctly. Our dedicated O&amp;M team provides comprehensive monitoring, preventive maintenance, and rapid-response troubleshooting to ensure your system operates at peak efficiency throughout its 25+ year lifespan.';
?>
</header>
<div class="pbmit-title-bar-wrapper">
	<div class="container">
		<div class="pbmit-title-bar-content">
			<div class="pbmit-title-bar-content-inner">
				<div class="pbmit-tbar">
					<div class="pbmit-tbar-inner container">
						<h1 class="pbmit-tbar-title"><?php echo $sr_title; ?></h1>
					</div>
				</div>
				<div class="pbmit-breadcrumb">
					<div class="pbmit-breadcrumb-inner">
						<span><a href="./" class="home"><span>Home</span></a></span>
						<i class="pbmit-base-icon-arrow-right-2"></i>
						<span><a href="services"><span>Services</span></a></span>
						<i class="pbmit-base-icon-arrow-right-2"></i>
						<span><span class="current-item">Operations &amp; Maintenance</span></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="page-content">
	<section class="section-xl service-details" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center mb-5">
				<h2 class="pbmit-title"><?php echo $sr_section_title; ?></h2>
				<?php if (trim($sr_subtitle) !== '') { ?>
					<p class="mb-0"><?php echo $sr_subtitle; ?></p>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-9 service-left-col" id="primary">
					<div class="pbmit-entry-content">
						<div class="pbmit-service-feature-image">
							<img src="images/homepage-2/service/service-img-02.jpg" alt="Operations & Maintenance">
						</div>
						<div class="pbmit-custom-heading">
							<h2 class="pbmit-title">What’s Included</h2>
						</div>
						<ul class="list-unstyled mb-4">
							<li>Remote monitoring through a dedicated solar performance dashboard</li>
							<li>Periodic cleaning and inspection of modules and mounts</li>
							<li>Inverter health checks and firmware updates</li>
							<li>Yield performance analysis and reporting</li>
							<li>Emergency fault detection and on-site repair services</li>
							<li>Annual performance audit reports</li>
						</ul>
						<div class="pbmit-custom-heading">
							<h2 class="pbmit-title">Service Approach</h2>
						</div>
						<p>Our O&amp;M ensures your system delivers maximum yield and uptime with proactive care and rapid response.</p>

						<div class="row align-items-center mt-3">
							<div class="col-md-6">
								<div class="pbmit-custom-heading">
									<h2 class="pbmit-title">Performance chart</h2>
								</div>
								<p>Monthly yield and uptime indicators help identify and resolve performance issues quickly.</p>
							</div>
							<div class="col-md-6">
								<div class="sr-chart-box">
									<img src="images/homepage-2/service/service-img-04.jpg" alt="Performance Chart">	
								</div>
							</div>
						</div>

						<div class="pbmit-custom-heading mt-4">
							<h2 class="pbmit-title">Benefits</h2>
						</div>
						<div class="sr-benefits">
							<div class="row">
								<div class="col-md-6">
									<ul class="list-unstyled mb-0">
										<li><i class="pbmit-base-icon-check-1"></i>Higher uptime with proactive checks</li>
										<li><i class="pbmit-base-icon-check-1"></i>Actionable monthly performance reports</li>
										<li><i class="pbmit-base-icon-check-1"></i>Faster fault resolution</li>
									</ul>
								</div>
								<div class="col-md-6">
									<ul class="list-unstyled mb-0">
										<li><i class="pbmit-base-icon-check-1"></i>Compliance and warranty support</li>
										<li><i class="pbmit-base-icon-check-1"></i>Expert technicians and trained staff</li>
										<li><i class="pbmit-base-icon-check-1"></i>Transparent service logs</li>
									</ul>
								</div>
							</div>
						</div>

						<div class="pbmit-custom-heading mt-4">
							<h2 class="pbmit-title">General ask</h2>
						</div>
						<div class="accordion" id="svcAccordionOM">
							<div class="accordion-item active">
								<h2 class="accordion-header" id="q1o">
									<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#a1o" aria-expanded="false" aria-controls="a1o">
										<span class="pbmit-accordion-title">How often do you inspect systems?</span>
										<span class="pbmit-accordion-icon pbmit-accordion-icon-right">
											<span class="pbmit-accordion-icon-closed"><i class="pbmit-solaar-icon pbmit-solaar-icon-top"></i></span>
											<span class="pbmit-accordion-icon-opened"><i class="pbmit-solaar-icon pbmit-solaar-icon-top"></i></span>
										</span>
									</button>
								</h2>
								<div id="a1o" class="accordion-collapse collapse show" aria-labelledby="q1o" data-bs-parent="#svcAccordionOM">
									<div class="accordion-body">
										<p>We schedule preventive checks monthly or quarterly, with cleaning cycles based on site conditions.</p>
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<h2 class="accordion-header" id="q2o">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a2o" aria-expanded="false" aria-controls="a2o">
										<span class="pbmit-accordion-title">Do you provide emergency support?</span>
										<span class="pbmit-accordion-icon pbmit-accordion-icon-right">
											<span class="pbmit-accordion-icon-closed"><i class="pbmit-solaar-icon pbmit-solaar-icon-top"></i></span>
											<span class="pbmit-accordion-icon-opened"><i class="pbmit-solaar-icon pbmit-solaar-icon-top"></i></span>
										</span>
									</button>
								</h2>
								<div id="a2o" class="accordion-collapse collapse" aria-labelledby="q2o" data-bs-parent="#svcAccordionOM">
									<div class="accordion-body">
										<p>Yes, our team can be dispatched for urgent issues with priority handling.</p>
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<h2 class="accordion-header" id="q3o">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a3o" aria-expanded="false" aria-controls="a3o">
										<span class="pbmit-accordion-title">What reports do we receive?</span>
										<span class="pbmit-accordion-icon pbmit-accordion-icon-right">
											<span class="pbmit-accordion-icon-closed"><i class="pbmit-solaar-icon pbmit-solaar-icon-top"></i></span>
											<span class="pbmit-accordion-icon-opened"><i class="pbmit-solaar-icon pbmit-solaar-icon-top"></i></span>
										</span>
									</button>
								</h2>
								<div id="a3o" class="accordion-collapse collapse" aria-labelledby="q3o" data-bs-parent="#svcAccordionOM">
									<div class="accordion-body">
										<p>Monthly yield, downtime, issues resolved, and recommendations to improve performance.</p>
									</div>
								</div>
							</div>
						</div>
						<p class="mt-3"><a href="contact">More questions? Get support now</a></p>
					</div>
				</div>
				<div class="col-md-3 service-right-col sidebar" id="secondary">
					<aside class="service-sidebar">
						<aside class="widget post-list">
							<h2 class="widget-title">Our Services</h2>
							<div class="all-post-list">
								<ul>
									<li><a href="services/solar-installation">Solar Module &amp; System Installation</a></li>
									<li><a class="active" href="services/operations-maintenance">Operations &amp; Maintenance</a></li>
									<li><a href="services/energy-consulting">Energy Efficiency Consulting</a></li>
									<li><a href="services/open-access-ppa">Open Access &amp; Power Purchase</a></li>
								</ul>
							</div>
						</aside>
						<aside class="widget sr-brochure">
							<h2 class="widget-title">Brochure</h2>
							<p class="sr-brochure-desc">Learn how our O&amp;M raises performance and uptime.</p>
							<a href="images/brochure.pdf" class="pbmit-btn"><span class="pbmit-button-text">Download pdf</span></a>
						</aside>
						<aside class="widget sr-contact-cta">
							<h2 class="widget-title"><i class="pbmit-base-icon-headphones"></i> Let’s talk</h2>
							<div class="sr-contact-lines">
								<div><a href="tel:+918686313133">(+91) 8686 313 133</a></div>
								<div><a href="mailto:info@shivanjalirenewables.com">info@shivanjalirenewables.com</a></div>
							</div>
							<a href="contact" class="pbmit-btn"><span class="pbmit-button-text">Get a call back</span></a>
						</aside>
					</aside>
				</div>
			</div>
		</div>
	</section>
	</div>
<?php include 'includes/footer.php'; ?>
