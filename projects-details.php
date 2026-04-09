<?php
require_once __DIR__ . '/includes/cms.php';

$sr_slug = strtolower((string)($_GET['project'] ?? ''));
if (!preg_match('/^[a-z0-9-]+$/', $sr_slug)) {
	$sr_slug = '';
}

$sr_project = null;
$sr_db = sr_cms_db_try();
if ($sr_db instanceof mysqli && $sr_slug !== '') {
	$stmt = $sr_db->prepare('SELECT slug, category, category_label, title, location_label, capacity_label, savings_label, outcome_label, image, content FROM cms_projects WHERE slug=? LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('s', $sr_slug);
		$stmt->execute();
		$stmt->bind_result($rslug, $rcat, $rcatLabel, $rtitle, $rloc, $rcap, $rsav, $rout, $rimg, $rcontent);
		if ($stmt->fetch()) {
			$sr_project = [
				'slug' => (string)$rslug,
				'category' => (string)$rcat,
				'category_label' => (string)$rcatLabel,
				'title' => (string)$rtitle,
				'location' => (string)$rloc,
				'capacity' => (string)$rcap,
				'savings' => (string)$rsav,
				'outcome' => (string)$rout,
				'image' => (string)$rimg,
				'content' => (string)$rcontent,
			];
		}
		$stmt->close();
	}
}

if ($sr_project === null) {
	http_response_code(404);
}

$sr_title = $sr_project ? $sr_project['title'] : 'Project';
$sr_cat_label = '';
if ($sr_project) {
	$sr_cat_label = trim($sr_project['category_label']);
	if ($sr_cat_label === '') {
		$cat = strtolower(trim($sr_project['category']));
		$sr_cat_label = $cat === 'openaccess' ? 'Open Access' : ($cat === 'parks' ? 'Solar Parks' : 'Rooftop');
	}
}

$sr_image = $sr_project ? trim($sr_project['image']) : '';
if ($sr_image === '') {
	$sr_image = 'images/portfolio/portfolio-01.jpg';
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
						<?php if ($sr_project) { ?>
							<p class="pbmit-tbar-subtitle mb-0"><?php echo htmlspecialchars($sr_cat_label, ENT_QUOTES, 'UTF-8'); ?></p>
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
			<?php if (!$sr_project) { ?>
				<div class="alert alert-warning">Project not found.</div>
			<?php } else { ?>
				<div class="row g-4 align-items-start">
					<div class="col-lg-7">
						<div class="sr-legal-card">
							<img src="<?php echo htmlspecialchars($sr_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($sr_title, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" style="border-radius: 18px;">
							<?php if (trim($sr_project['content']) !== '') { ?>
								<div class="mt-4">
									<?php echo $sr_project['content']; ?>
								</div>
							<?php } ?>
						</div>
					</div>

					<div class="col-lg-5">
						<div class="sr-legal-toc" style="top: 90px;">
							<div class="sr-legal-toc-title">Project Summary</div>
							<ul class="sr-legal-list mb-0">
								<?php if (trim($sr_project['location']) !== '') { ?><li><strong>Location:</strong> <?php echo htmlspecialchars($sr_project['location'], ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
								<?php if (trim($sr_project['capacity']) !== '') { ?><li><strong>Capacity:</strong> <?php echo htmlspecialchars($sr_project['capacity'], ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
								<?php if (trim($sr_project['savings']) !== '') { ?><li><strong>Savings:</strong> <?php echo htmlspecialchars($sr_project['savings'], ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
								<?php if (trim($sr_project['outcome']) !== '') { ?><li><strong>Outcome:</strong> <?php echo htmlspecialchars($sr_project['outcome'], ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
								<?php if ($sr_cat_label !== '') { ?><li><strong>Category:</strong> <?php echo htmlspecialchars($sr_cat_label, ENT_QUOTES, 'UTF-8'); ?></li><?php } ?>
							</ul>
							<div class="sr-legal-toc-cta">
								<a href="contact" class="pbmit-btn"><span class="pbmit-button-text">Request Similar Project</span></a>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</section>
</div>

<?php include 'includes/footer.php'; ?>

