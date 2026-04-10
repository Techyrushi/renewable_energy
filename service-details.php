<?php
require_once __DIR__ . '/includes/cms.php';

$sr_slug = strtolower((string)($_GET['service'] ?? ''));
if (!preg_match('/^[a-z0-9-]+$/', $sr_slug)) {
	$sr_slug = '';
}

$sr_service = null;
$sr_db = sr_cms_db_try();
if ($sr_db instanceof mysqli && $sr_slug !== '') {
	$stmt = $sr_db->prepare('SELECT slug, title, short_desc, image, content FROM cms_services WHERE slug=? AND published=1 LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('s', $sr_slug);
		$stmt->execute();
		$stmt->bind_result($rslug, $rtitle, $rshort, $rimg, $rcontent);
		if ($stmt->fetch()) {
			$sr_service = [
				'slug' => (string)$rslug,
				'title' => (string)$rtitle,
				'short_desc' => (string)$rshort,
				'image' => (string)$rimg,
				'content' => (string)$rcontent,
			];
		}
		$stmt->close();
	}
}

if ($sr_service === null) {
	http_response_code(404);
}

$sr_title = $sr_service ? $sr_service['title'] : 'Service';
$sr_image = $sr_service ? trim($sr_service['image']) : '';
if ($sr_image === '') {
	$sr_image = 'images/homepage-2/service/service-img-01.jpg';
}

include 'includes/header.php';
?>
</header>

<style>
	.sr-service-detail-section {
		position: relative;
		overflow: hidden;
		background: radial-gradient(900px 420px at 20% -10%, rgba(255, 195, 74, .22), rgba(255, 255, 255, 0) 60%),
			radial-gradient(900px 420px at 80% -10%, rgba(51, 168, 255, .18), rgba(255, 255, 255, 0) 60%),
			linear-gradient(180deg, rgba(246, 249, 252, 1), rgba(255, 255, 255, 1));
	}
	.sr-service-detail-section:before {
		content: '';
		position: absolute;
		inset: -180px -180px auto auto;
		width: 420px;
		height: 420px;
		border-radius: 50%;
		background: radial-gradient(circle at 30% 30%, rgba(255, 195, 74, .22), rgba(255, 195, 74, 0) 70%);
		filter: blur(2px);
		pointer-events: none;
	}
	.sr-service-detail-wrap {
		max-width: 1140px;
		margin: 0 auto;
	}
	.sr-service-detail-card {
		background: rgba(255, 255, 255, .92);
		border: 1px solid rgba(10, 25, 38, .10);
		border-radius: 22px;
		box-shadow: 0 22px 60px rgba(10, 25, 38, .08);
		backdrop-filter: blur(6px);
		overflow: hidden;
		transition: transform .25s ease, box-shadow .25s ease;
	}
	.sr-service-detail-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 26px 76px rgba(10, 25, 38, .12);
	}
	.sr-service-hero-image {
		width: 100%;
		height: 420px;
		object-fit: cover;
		border-bottom: 1px solid rgba(10, 25, 38, .10);
	}
	@media (max-width: 991px) {
		.sr-service-hero-image { height: 260px; }
	}
	.sr-service-detail-body {
		padding: 26px 26px 18px;
	}
	.sr-service-detail-lead {
		margin: 0 0 16px;
		padding: 14px 16px;
		border-radius: 16px;
		background: linear-gradient(90deg, rgba(255, 195, 74, .14), rgba(51, 168, 255, .10));
		border: 1px solid rgba(10, 25, 38, .10);
		color: rgba(10, 25, 38, .85);
		font-weight: 600;
	}
	.sr-service-detail-actions {
		display: flex;
		flex-wrap: wrap;
		gap: 12px;
		align-items: center;
		justify-content: space-between;
		padding: 18px 26px 26px;
	}
	.sr-service-action-card {
		flex: 1 1 360px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		padding: 16px 18px;
		border-radius: 18px;
		border: 1px solid rgba(10, 25, 38, .10);
		background: linear-gradient(90deg, rgba(10, 25, 38, .92), rgba(10, 25, 38, .84));
		color: #fff;
	}
	.sr-service-action-title {
		font-weight: 800;
		margin: 0;
		line-height: 1.25;
	}
	.sr-service-action-desc {
		margin: 2px 0 0;
		opacity: .82;
	}
	.sr-service-content img {
		max-width: 100%;
		height: auto;
		display: block;
		margin: 18px 0;
		border-radius: 18px;
		box-shadow: 0 18px 40px rgba(0, 0, 0, .10);
		border: 1px solid rgba(10, 25, 38, .10);
		background: #fff;
	}
	.sr-service-content figure {
		margin: 18px 0;
	}
	.sr-service-content figcaption {
		margin-top: 10px;
		font-weight: 600;
		color: rgba(10, 25, 38, .70);
	}
</style>

<div class="pbmit-title-bar-wrapper sr-why-hero">
	<div class="container">
		<div class="pbmit-title-bar-content">
			<div class="pbmit-title-bar-content-inner">
				<div class="pbmit-tbar">
					<div class="pbmit-tbar-inner container">
						<h1 class="pbmit-tbar-title"><?php echo htmlspecialchars($sr_title, ENT_QUOTES, 'UTF-8'); ?></h1>
						<?php if ($sr_service && trim($sr_service['short_desc']) !== '') { ?>
							<p class="pbmit-tbar-subtitle mb-0"><?php echo htmlspecialchars($sr_service['short_desc'], ENT_QUOTES, 'UTF-8'); ?></p>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="page-content">
	<section class="section-xl sr-service-detail-section" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<?php if (!$sr_service) { ?>
				<div class="alert alert-warning">Service not found.</div>
			<?php } else { ?>
				<div class="sr-service-detail-wrap">
					<div class="sr-service-detail-card" data-aos="fade-up" data-aos-duration="800">
						<img src="<?php echo htmlspecialchars($sr_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($sr_title, ENT_QUOTES, 'UTF-8'); ?>" class="sr-service-hero-image">
						<div class="sr-service-detail-body">
							<?php if (trim((string)$sr_service['short_desc']) !== '') { ?>
								<div class="sr-service-detail-lead"><?php echo htmlspecialchars((string)$sr_service['short_desc'], ENT_QUOTES, 'UTF-8'); ?></div>
							<?php } ?>
							<?php if (trim($sr_service['content']) !== '') { ?>
								<div class="sr-service-content">
									<?php echo $sr_service['content']; ?>
								</div>
							<?php } ?>
						</div>
						<div class="sr-service-detail-actions">
							<div class="sr-service-action-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="60">
								<div>
									<div class="sr-service-action-title">Request a free consultation</div>
									<div class="sr-service-action-desc">Get a proposal tailored to your site requirements.</div>
								</div>
								<a href="contact" class="pbmit-btn"><span class="pbmit-button-text">Request Now</span></a>
							</div>
							<a href="services" class="pbmit-btn outline" data-aos="fade-up" data-aos-duration="800" data-aos-delay="120"><span class="pbmit-button-text">Back to Services</span></a>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</section>
</div>

<?php include 'includes/footer.php'; ?>
