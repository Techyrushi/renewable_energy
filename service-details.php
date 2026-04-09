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
	<section class="section-xl">
		<div class="container">
			<?php if (!$sr_service) { ?>
				<div class="alert alert-warning">Service not found.</div>
			<?php } else { ?>
				<div class="row g-4 align-items-start">
					<div class="col-lg-7">
						<div class="sr-legal-card">
							<img src="<?php echo htmlspecialchars($sr_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($sr_title, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" style="border-radius: 18px;">
							<?php if (trim($sr_service['content']) !== '') { ?>
								<div class="mt-4">
									<?php echo $sr_service['content']; ?>
								</div>
							<?php } ?>
						</div>
					</div>

					<div class="col-lg-5">
						<div class="sr-legal-toc" style="top: 90px;">
							<div class="sr-legal-toc-title">Need this service?</div>
							<div class="text-title-gray mb-3">Get a free consultation and proposal for your site.</div>
							<div class="sr-legal-toc-cta">
								<a href="contact" class="pbmit-btn"><span class="pbmit-button-text">Request a Consultation</span></a>
							</div>
							<div class="mt-3 sr-legal-toc-cta">
								<a href="services" class="pbmit-btn outline"><span class="pbmit-button-text">Back to Services</span></a>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</section>
</div>

<?php include 'includes/footer.php'; ?>

