<?php
http_response_code(404);
include 'includes/header.php';
?>
</header>
<!-- Header Main Area End Here -->

<div class="page-content">
	<section class="error-404-page">
		<div class="container">
			<div class="row align-items-center g-5">
				<div class="col-lg-6" data-aos="fade-up" data-aos-duration="800">
					<div class="error-404-illustration">
						<svg viewBox="0 0 620 520" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Solar panel illustration">
							<defs>
								<linearGradient id="e404g1" x1="0" y1="0" x2="1" y2="1">
									<stop offset="0" stop-color="rgb(var(--pbmit-global-color-rgb))" stop-opacity="1"></stop>
									<stop offset="1" stop-color="rgb(var(--pbmit-secondary-color-rgb))" stop-opacity="1"></stop>
								</linearGradient>
								<linearGradient id="e404g2" x1="1" y1="0" x2="0" y2="1">
									<stop offset="0" stop-color="rgba(255,255,255,0.95)"></stop>
									<stop offset="1" stop-color="rgba(255,255,255,0.12)"></stop>
								</linearGradient>
							</defs>
							<circle cx="470" cy="110" r="58" fill="url(#e404g1)" opacity="0.90"></circle>
							<circle cx="470" cy="110" r="86" fill="none" stroke="url(#e404g1)" stroke-width="4" opacity="0.35"></circle>
							<g opacity="0.85">
								<path d="M92 352 L438 278 L512 430 L166 504 Z" fill="rgba(var(--pbmit-secondary-color-rgb),0.10)" stroke="rgba(var(--pbmit-secondary-color-rgb),0.20)" stroke-width="2"></path>
								<path d="M128 352 L418 290 L468 410 L178 472 Z" fill="rgba(var(--pbmit-secondary-color-rgb),0.16)" stroke="rgba(var(--pbmit-secondary-color-rgb),0.24)" stroke-width="2"></path>
								<path d="M152 358 L406 300 L446 400 L192 456 Z" fill="url(#e404g2)" opacity="0.85"></path>
								<path d="M152 358 L406 300 L446 400 L192 456 Z" fill="none" stroke="rgba(var(--pbmit-secondary-color-rgb),0.22)" stroke-width="2"></path>
								<path d="M190 450 L176 496" stroke="rgba(var(--pbmit-secondary-color-rgb),0.30)" stroke-width="10" stroke-linecap="round"></path>
								<path d="M444 402 L460 456" stroke="rgba(var(--pbmit-secondary-color-rgb),0.30)" stroke-width="10" stroke-linecap="round"></path>
								<path d="M166 396 L430 336" stroke="rgba(var(--pbmit-secondary-color-rgb),0.22)" stroke-width="2"></path>
								<path d="M178 424 L442 364" stroke="rgba(var(--pbmit-secondary-color-rgb),0.22)" stroke-width="2"></path>
								<path d="M210 344 L228 462" stroke="rgba(var(--pbmit-secondary-color-rgb),0.18)" stroke-width="2"></path>
								<path d="M270 332 L290 450" stroke="rgba(var(--pbmit-secondary-color-rgb),0.18)" stroke-width="2"></path>
								<path d="M330 318 L356 436" stroke="rgba(var(--pbmit-secondary-color-rgb),0.18)" stroke-width="2"></path>
								<path d="M390 304 L420 420" stroke="rgba(var(--pbmit-secondary-color-rgb),0.18)" stroke-width="2"></path>
							</g>
							<g opacity="0.9">
								<path d="M116 184 C146 144, 186 124, 236 124 C286 124, 326 144, 356 184" fill="none" stroke="rgba(255,255,255,0.22)" stroke-width="18" stroke-linecap="round"></path>
								<path d="M116 184 C146 144, 186 124, 236 124 C286 124, 326 144, 356 184" fill="none" stroke="url(#e404g1)" stroke-width="6" stroke-linecap="round"></path>
							</g>
						</svg>
					</div>
				</div>
				<div class="col-lg-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="120">
					<div class="error-404-card">
						<div class="error-404-code">404</div>
						<h1 class="pbmit-title">Page Not Found</h1>
						<p class="error-404-text">The page you’re looking for doesn’t exist, may have been moved, or the link is incorrect.</p>
						<div class="d-flex flex-wrap gap-3">
							<a href="./" class="pbmit-btn">
								<span class="pbmit-button-text">Back to Home</span>
							</a>
							<a href="contact" class="pbmit-btn outline">
								<span class="pbmit-button-text">Contact Us</span>
							</a>
						</div>
						<div class="error-404-links">
							<a href="services">Services</a>
							<span class="error-404-dot">•</span>
							<a href="products">Products</a>
							<span class="error-404-dot">•</span>
							<a href="projects">Projects</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php include 'includes/footer.php'; ?>
