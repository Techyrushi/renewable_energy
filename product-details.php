<?php
require_once __DIR__ . '/includes/cms.php';

$sr_slug = strtolower((string)($_GET['product'] ?? ''));
if (!preg_match('/^[a-z0-9-]+$/', $sr_slug)) {
	$sr_slug = '';
}

$sr_product = null;
$sr_db = sr_cms_db_try();
if ($sr_db instanceof mysqli && $sr_slug !== '') {
	$stmt = $sr_db->prepare('SELECT slug, category_anchor, badge_label, title, range_label, short_desc, bullets, image, content FROM cms_products WHERE slug=? AND published=1 LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('s', $sr_slug);
		$stmt->execute();
		$stmt->bind_result($rslug, $ranchor, $rbadge, $rtitle, $rrange, $rshort, $rbullets, $rimg, $rcontent);
		if ($stmt->fetch()) {
			$sr_product = [
				'slug' => (string)$rslug,
				'category_anchor' => (string)$ranchor,
				'badge_label' => (string)$rbadge,
				'title' => (string)$rtitle,
				'range_label' => (string)$rrange,
				'short_desc' => (string)$rshort,
				'bullets' => (string)$rbullets,
				'image' => (string)$rimg,
				'content' => (string)$rcontent,
			];
		}
		$stmt->close();
	}
}

if ($sr_product === null) {
	http_response_code(404);
}

$sr_title = $sr_product ? $sr_product['title'] : 'Product';
$sr_image = $sr_product ? trim($sr_product['image']) : '';
if ($sr_image === '') {
	$sr_image = 'images/about-01.jpg';
}

include 'includes/header.php';
?>
</header>

<div class="pbmit-title-bar-wrapper sr-why-hero sr-blog-detail-hero">
	<div class="container">
		<div class="pbmit-title-bar-content">
			<div class="pbmit-title-bar-content-inner">
				<div class="pbmit-tbar">
					<div class="pbmit-tbar-inner container">
						<h1 class="pbmit-tbar-title"><?php echo htmlspecialchars($sr_title, ENT_QUOTES, 'UTF-8'); ?></h1>
						<?php if ($sr_product) { ?>
							<p class="pbmit-tbar-subtitle mb-0">
								<?php echo htmlspecialchars(trim($sr_product['badge_label']) !== '' ? $sr_product['badge_label'] : 'Product', ENT_QUOTES, 'UTF-8'); ?>
								<?php if (trim($sr_product['range_label']) !== '') { ?> • <?php echo htmlspecialchars($sr_product['range_label'], ENT_QUOTES, 'UTF-8'); ?><?php } ?>
							</p>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="page-content products">
	<section class="section-xl">
		<div class="container">
			<?php if (!$sr_product) { ?>
				<div class="alert alert-warning">Product not found.</div>
			<?php } else { ?>
				<div class="row g-4 align-items-start">
					<div class="col-lg-7">
						<div class="sr-legal-card">
							<img src="<?php echo htmlspecialchars($sr_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($sr_title, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" style="border-radius: 18px;">
							<?php if (trim($sr_product['short_desc']) !== '') { ?>
								<p class="mt-4 mb-0" style="font-size: 16px; line-height: 28px;"><?php echo htmlspecialchars($sr_product['short_desc'], ENT_QUOTES, 'UTF-8'); ?></p>
							<?php } ?>
							<?php if (trim($sr_product['content']) !== '') { ?>
								<div class="mt-4">
									<?php echo $sr_product['content']; ?>
								</div>
							<?php } ?>
						</div>
					</div>

					<div class="col-lg-5">
						<div class="sr-legal-toc" style="top: 90px;">
							<div class="sr-legal-toc-title">Highlights</div>
							<ul class="sr-legal-list mb-0">
								<?php
								$lines = preg_split('/\\r\\n|\\r|\\n/', (string)$sr_product['bullets']);
								$lines = array_values(array_filter(array_map('trim', $lines), function ($x) { return $x !== ''; }));
								?>
								<?php foreach ($lines as $line) { ?>
									<li><?php echo htmlspecialchars($line, ENT_QUOTES, 'UTF-8'); ?></li>
								<?php } ?>
								<?php if (!$lines) { ?>
									<li>Custom engineered for your site requirements</li>
									<li>Quality components and clean installation finish</li>
									<li>Designed for long-term performance</li>
								<?php } ?>
							</ul>
							<div class="sr-legal-toc-cta">
								<a href="contact" class="pbmit-btn"><span class="pbmit-button-text">Get Pricing &amp; Proposal</span></a>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</section>
</div>

<?php include 'includes/footer.php'; ?>

