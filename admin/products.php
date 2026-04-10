<?php
require_once __DIR__ . '/db.php';
sr_admin_require_login();

$db = sr_cms_db_required();
sr_cms_migrate($db);

$msg = isset($_GET['msg']) ? (string)$_GET['msg'] : '';
$action = isset($_GET['action']) ? (string)$_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function sr_admin_unique_product_slug(mysqli $db, string $base, int $excludeId = 0): string
{
	$base = sr_cms_slugify($base);
	$slug = $base;
	$i = 2;
	while (true) {
		$sql = 'SELECT id FROM cms_products WHERE slug = ?' . ($excludeId > 0 ? ' AND id <> ?' : '') . ' LIMIT 1';
		$stmt = $db->prepare($sql);
		if (!$stmt) {
			return $slug;
		}
		if ($excludeId > 0) {
			$stmt->bind_param('si', $slug, $excludeId);
		} else {
			$stmt->bind_param('s', $slug);
		}
		$stmt->execute();
		$stmt->store_result();
		$exists = $stmt->num_rows > 0;
		$stmt->close();
		if (!$exists) {
			return $slug;
		}
		$slug = $base . '-' . $i;
		$i++;
	}
}

function sr_admin_upload_product_image(array $file): array
{
	$err = isset($file['error']) ? (int) $file['error'] : UPLOAD_ERR_NO_FILE;
	if ($err === UPLOAD_ERR_NO_FILE) {
		return ['ok' => false, 'path' => '', 'error' => ''];
	}
	if ($err !== UPLOAD_ERR_OK) {
		return ['ok' => false, 'path' => '', 'error' => 'Upload failed.'];
	}
	$tmp = (string) ($file['tmp_name'] ?? '');
	$size = (int) ($file['size'] ?? 0);
	if ($size <= 0 || $size > 5_000_000) {
		return ['ok' => false, 'path' => '', 'error' => 'Image must be under 5MB.'];
	}
	$info = @getimagesize($tmp);
	$mime = is_array($info) ? (string) ($info['mime'] ?? '') : '';
	$ext = '';
	if ($mime === 'image/jpeg') {
		$ext = 'jpg';
	} elseif ($mime === 'image/png') {
		$ext = 'png';
	} elseif ($mime === 'image/webp') {
		$ext = 'webp';
	}
	if ($ext === '') {
		return ['ok' => false, 'path' => '', 'error' => 'Only JPG, PNG, or WEBP images are allowed.'];
	}

	$absDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'products';
	$relDir = 'images/products';
	if (!is_dir($absDir)) {
		@mkdir($absDir, 0775, true);
	}
	$filename = 'product-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
	$dest = rtrim($absDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
	if (!@move_uploaded_file($tmp, $dest)) {
		return ['ok' => false, 'path' => '', 'error' => 'Unable to save uploaded image.'];
	}
	return ['ok' => true, 'path' => $relDir . '/' . $filename, 'error' => ''];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$op = isset($_POST['op']) ? (string)$_POST['op'] : '';

	if ($op === 'content_image_upload') {
		$csrf = isset($_POST['csrf']) ? (string)$_POST['csrf'] : null;
		if (!sr_admin_verify_csrf($csrf)) {
			header('Content-Type: application/json; charset=UTF-8');
			echo json_encode(['ok' => false, 'error' => 'Invalid session. Please refresh and try again.']);
			exit;
		}

		$up = isset($_FILES['content_image']) && is_array($_FILES['content_image']) ? sr_admin_upload_product_image($_FILES['content_image']) : ['ok' => false, 'path' => '', 'error' => 'Please select an image.'];
		if ($up['error'] !== '') {
			header('Content-Type: application/json; charset=UTF-8');
			echo json_encode(['ok' => false, 'error' => $up['error']]);
			exit;
		}

		$scheme = (!empty($_SERVER['HTTPS']) && (string) $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
		$host = trim((string) ($_SERVER['HTTP_HOST'] ?? ''));
		$host = $host !== '' ? $host : 'localhost';
		$adminBase = rtrim(str_replace('\\', '/', (string) dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
		$rootBase = rtrim(str_replace('\\', '/', (string) dirname($adminBase)), '/');
		$rootBase = ($rootBase === '' || $rootBase === '/') ? '' : $rootBase;
		$url = $scheme . '://' . $host . $rootBase . '/' . ltrim((string) $up['path'], '/');

		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode(['ok' => true, 'url' => $url, 'path' => (string) $up['path']]);
		exit;
	}

	$csrf = isset($_POST['csrf']) ? (string)$_POST['csrf'] : null;
	if (!sr_admin_verify_csrf($csrf)) {
		header('Location: products.php?msg=' . rawurlencode('Invalid session. Please try again.'));
		exit;
	}

	if ($op === 'save') {
		$editId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$title = trim((string)($_POST['title'] ?? ''));
		$slug = '';
		$anchor = trim((string)($_POST['category_anchor'] ?? ''));
		$badge = trim((string)($_POST['badge_label'] ?? ''));
		$range = trim((string)($_POST['range_label'] ?? ''));
		$short = trim((string)($_POST['short_desc'] ?? ''));
		$bullets = trim((string)($_POST['bullets'] ?? ''));
		$image = '';
		$content = (string)($_POST['content'] ?? '');
		$published = isset($_POST['published']) ? 1 : 0;
		$sortOrder = (int)($_POST['sort_order'] ?? 0);

		if ($title === '' || $short === '') {
			$target = $editId > 0 ? ('products.php?action=edit&id=' . $editId) : 'products.php?action=new';
			header('Location: ' . $target . '&msg=' . rawurlencode('Title and short description are required.'));
			exit;
		}

		if ($editId > 0) {
			$existingSlug = '';
			$existingImage = '';
			$stmtExisting = $db->prepare('SELECT slug, image FROM cms_products WHERE id=? LIMIT 1');
			if ($stmtExisting) {
				$stmtExisting->bind_param('i', $editId);
				$stmtExisting->execute();
				$stmtExisting->bind_result($rslug, $rimg);
				if ($stmtExisting->fetch()) {
					$existingSlug = (string) $rslug;
					$existingImage = (string) $rimg;
				}
				$stmtExisting->close();
			}

			$slug = trim($existingSlug) !== '' ? $existingSlug : ($title !== '' ? sr_admin_unique_product_slug($db, $title, $editId) : '');
			$image = $existingImage;

			if (isset($_FILES['image_file']) && is_array($_FILES['image_file'])) {
				$up = sr_admin_upload_product_image($_FILES['image_file']);
				if ($up['error'] !== '') {
					header('Location: products.php?action=edit&id=' . $editId . '&msg=' . rawurlencode($up['error']));
					exit;
				}
				if ($up['ok']) {
					$image = (string) $up['path'];
				}
			}

			$stmt = $db->prepare('UPDATE cms_products SET slug=?, category_anchor=?, badge_label=?, title=?, range_label=?, short_desc=?, bullets=?, image=?, content=?, published=?, sort_order=? WHERE id=?');
			if (!$stmt) {
				header('Location: products.php?msg=' . rawurlencode('Failed to save product.'));
				exit;
			}
			$stmt->bind_param('sssssssssiii', $slug, $anchor, $badge, $title, $range, $short, $bullets, $image, $content, $published, $sortOrder, $editId);
			$stmt->execute();
			$stmt->close();
			header('Location: products.php?action=edit&id=' . $editId . '&msg=' . rawurlencode('Saved.'));
			exit;
		}

		$slug = $title !== '' ? sr_admin_unique_product_slug($db, $title, 0) : '';
		if (isset($_FILES['image_file']) && is_array($_FILES['image_file'])) {
			$up = sr_admin_upload_product_image($_FILES['image_file']);
			if ($up['error'] !== '') {
				header('Location: products.php?action=new&msg=' . rawurlencode($up['error']));
				exit;
			}
			if ($up['ok']) {
				$image = (string) $up['path'];
			}
		}

		$stmt = $db->prepare('INSERT INTO cms_products (slug, category_anchor, badge_label, title, range_label, short_desc, bullets, image, content, published, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
		if (!$stmt) {
			header('Location: products.php?msg=' . rawurlencode('Failed to create product.'));
			exit;
		}
		$stmt->bind_param('sssssssssii', $slug, $anchor, $badge, $title, $range, $short, $bullets, $image, $content, $published, $sortOrder);
		$stmt->execute();
		$newId = (int)$stmt->insert_id;
		$stmt->close();
		header('Location: products.php?action=edit&id=' . $newId . '&msg=' . rawurlencode('Created.'));
		exit;
	}

	if ($op === 'delete') {
		$delId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($delId > 0) {
			$stmt = $db->prepare('DELETE FROM cms_products WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('i', $delId);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: products.php?msg=' . rawurlencode('Deleted.'));
		exit;
	}

	if ($op === 'toggle') {
		$tId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($tId > 0) {
			$stmt = $db->prepare('UPDATE cms_products SET published = IF(published=1,0,1) WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('i', $tId);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: products.php?msg=' . rawurlencode('Updated.'));
		exit;
	}
}

$editing = null;
if ($action === 'edit' && $id > 0) {
	$stmt = $db->prepare('SELECT id, slug, category_anchor, badge_label, title, range_label, short_desc, bullets, image, content, published, sort_order FROM cms_products WHERE id=? LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($rid, $rslug, $ranch, $rbadge, $rtitle, $rrange, $rshort, $rbullets, $rimg, $rcontent, $rpub, $rsort);
		if ($stmt->fetch()) {
			$editing = [
				'id' => (int)$rid,
				'slug' => (string)$rslug,
				'category_anchor' => (string)$ranch,
				'badge_label' => (string)$rbadge,
				'title' => (string)$rtitle,
				'range_label' => (string)$rrange,
				'short_desc' => (string)$rshort,
				'bullets' => (string)$rbullets,
				'image' => (string)$rimg,
				'content' => (string)$rcontent,
				'published' => (int)$rpub,
				'sort_order' => (int)$rsort,
			];
		}
		$stmt->close();
	}
	$action = $editing ? 'edit' : 'list';
}

if ($action === 'new') {
	$editing = [
		'id' => 0,
		'slug' => '',
		'category_anchor' => 'products',
		'badge_label' => '',
		'title' => '',
		'range_label' => '',
		'short_desc' => '',
		'bullets' => '',
		'image' => '',
		'content' => '',
		'published' => 1,
		'sort_order' => 0,
	];
}

$items = [];
$res = $db->query('SELECT id, slug, title, category_anchor, published, sort_order, updated_at FROM cms_products ORDER BY published DESC, sort_order ASC, updated_at DESC LIMIT 400');
if ($res) {
	while ($row = $res->fetch_assoc()) {
		$items[] = $row;
	}
	$res->free();
}

if (!$items) {
	$seed = [
		[
			'slug' => 'residential-solar-systems',
			'anchor' => 'residential',
			'badge' => 'FOR HOMES',
			'title' => 'Residential Solar Systems',
			'range' => '3 kW – 19 kW',
			'short' => 'Designed for Indian homes, custom-built to match your monthly electricity consumption, rooftop space, and budget.',
			'bullets' => "Reduce electricity bill by 70–100%\nEarn from net metering / excess export\n25-year panel performance warranty",
			'image' => 'images/homepage-1/service/service-img-01.jpg',
			'content' => '<p>Designed for Indian homes, our residential solar systems are custom-built to match your monthly electricity consumption, rooftop space, and budget. With net metering support, you can sell excess energy back to the grid — making your home a small power plant.</p><div class="sr-modal-section-title">Ideal for</div><ul class="sr-modal-list sr-icon-list"><li><i class="pbmit-base-icon-check-mark"></i>Independent houses and bungalows</li><li><i class="pbmit-base-icon-check-mark"></i>Row houses and villas</li><li><i class="pbmit-base-icon-check-mark"></i>Housing societies (common areas)</li></ul><div class="sr-modal-section-title">Key Benefits</div><ul class="sr-modal-list sr-icon-list"><li><i class="pbmit-base-icon-tick"></i>Reduce electricity bill by 70–100%</li><li><i class="pbmit-base-icon-tick"></i>Earn from net metering / excess export</li><li><i class="pbmit-base-icon-tick"></i>25-year panel performance warranty</li><li><i class="pbmit-base-icon-tick"></i>Government subsidy eligibility under PM Surya Ghar Muft Bijli Yojana</li></ul>',
			'published' => 1,
			'sort' => 1,
		],
		[
			'slug' => 'commercial-solar-systems',
			'anchor' => 'commercial',
			'badge' => 'FOR BUSINESSES',
			'title' => 'Commercial Solar Systems',
			'range' => '20 kW – 200 kW',
			'short' => 'Scalable solar systems for offices, hospitals, hotels, schools, and retail with maximum savings on commercial tariff.',
			'bullets' => "Reduce high commercial electricity tariff\nAccelerated depreciation benefit (Year 1)\nMonitoring portal for real-time tracking",
			'image' => 'images/homepage-1/service/service-img-02.jpg',
			'content' => '<p>Scalable, high-performance solar systems designed for commercial establishments including offices, hotels, hospitals, educational institutions, and retail businesses. Our commercial systems are engineered to align with your load profile and maximise savings on your commercial tariff.</p><div class="sr-modal-section-title">Ideal for</div><ul class="sr-modal-list sr-icon-list"><li><i class="pbmit-base-icon-check-mark"></i>Offices, IT parks, and coworking spaces</li><li><i class="pbmit-base-icon-check-mark"></i>Hospitals, hotels, and schools</li><li><i class="pbmit-base-icon-check-mark"></i>Shopping centres and malls</li><li><i class="pbmit-base-icon-check-mark"></i>Warehouses and cold storage facilities</li></ul><div class="sr-modal-section-title">Key Benefits</div><ul class="sr-modal-list sr-icon-list"><li><i class="pbmit-base-icon-tick"></i>Significant reduction in commercial electricity tariff</li><li><i class="pbmit-base-icon-tick"></i>Accelerated depreciation benefit (40% in Year 1) for businesses</li><li><i class="pbmit-base-icon-tick"></i>Scalable design — easy to expand as your load grows</li><li><i class="pbmit-base-icon-tick"></i>Monitoring portal for real-time generation tracking</li></ul>',
			'published' => 1,
			'sort' => 2,
		],
		[
			'slug' => 'ht-consumer-solar-projects',
			'anchor' => 'ht-consumer',
			'badge' => 'FOR INDUSTRY',
			'title' => 'HT Consumer Solar Projects',
			'range' => '200 kW – 990 kW',
			'short' => 'Industrial-grade systems for HT consumers to offset demand charges and deliver fast payback with captive consumption.',
			'bullets' => "Reduce HT tariff and demand charges\nCaptive consumption model\nDedicated project manager",
			'image' => 'images/homepage-1/service/service-img-03.jpg',
			'content' => '<p>High-tension electricity consumers — factories, large manufacturing plants, processing units — face the highest power costs. Our HT consumer projects are designed to offset a significant portion of your HT tariff with clean solar energy, delivering payback in as little as 3–5 years.</p><div class="sr-modal-section-title">Ideal for</div><ul class="sr-modal-list sr-icon-list"><li><i class="pbmit-base-icon-check-mark"></i>Factories and manufacturing units</li><li><i class="pbmit-base-icon-check-mark"></i>Food processing and agro-industrial plants</li><li><i class="pbmit-base-icon-check-mark"></i>Textile mills and engineering companies</li><li><i class="pbmit-base-icon-check-mark"></i>Large pumping stations and water treatment plants</li></ul><div class="sr-modal-section-title">Key Benefits</div><ul class="sr-modal-list sr-icon-list"><li><i class="pbmit-base-icon-tick"></i>Substantial reduction in HT tariff and demand charges</li><li><i class="pbmit-base-icon-tick"></i>Captive consumption model — no export dependency</li><li><i class="pbmit-base-icon-tick"></i>Robust industrial-grade equipment for harsh environments</li><li><i class="pbmit-base-icon-tick"></i>Dedicated project manager for seamless execution</li></ul>',
			'published' => 1,
			'sort' => 3,
		],
		[
			'slug' => 'open-access-solar-projects',
			'anchor' => 'open-access',
			'badge' => 'LARGE SCALE',
			'title' => 'Open Access Solar Projects',
			'range' => '1 MW – 20 MW',
			'short' => 'Large-scale clean energy through solar parks and direct PPAs with full EPC lifecycle and regulatory handling.',
			'bullets' => "Typically 30–50% savings vs grid\nLong-term fixed PPA rates\nEnd-to-end regulatory approvals",
			'image' => 'images/homepage-1/service/service-img-04.jpg',
			'content' => '<p>For organisations with high power requirements — or developers looking to build solar infrastructure — our Open Access solar projects deliver large-scale, cost-effective clean energy through solar parks and direct Power Purchase Agreements (PPAs). We manage the full EPC lifecycle and regulatory process.</p><div class="sr-modal-section-title">Ideal for</div><ul class="sr-modal-list sr-icon-list"><li><i class="pbmit-base-icon-check-mark"></i>Large industrial groups and conglomerates</li><li><i class="pbmit-base-icon-check-mark"></i>Solar park developers seeking land and infrastructure</li><li><i class="pbmit-base-icon-check-mark"></i>Government or institutional bulk energy buyers</li><li><i class="pbmit-base-icon-check-mark"></i>Real estate developers building green-rated projects</li></ul><div class="sr-modal-section-title">Key Benefits</div><ul class="sr-modal-list sr-icon-list"><li><i class="pbmit-base-icon-tick"></i>Competitive solar power tariff vs. grid — typically 30–50% savings</li><li><i class="pbmit-base-icon-tick"></i>Long-term energy price certainty through fixed PPA rates</li><li><i class="pbmit-base-icon-tick"></i>Full regulatory handling — open access approvals, wheeling charges, banking</li><li><i class="pbmit-base-icon-tick"></i>Shovel-ready infrastructure with grid connectivity</li></ul>',
			'published' => 1,
			'sort' => 4,
		],
	];

	$ins = $db->prepare('INSERT INTO cms_products (slug, category_anchor, badge_label, title, range_label, short_desc, bullets, image, content, published, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	if ($ins) {
		foreach ($seed as $p) {
			$slug = (string) $p['slug'];
			$anchor = (string) $p['anchor'];
			$badge = (string) $p['badge'];
			$title = (string) $p['title'];
			$range = (string) $p['range'];
			$short = (string) $p['short'];
			$bullets = (string) $p['bullets'];
			$image = (string) $p['image'];
			$content = (string) $p['content'];
			$pub = (int) $p['published'];
			$sort = (int) $p['sort'];
			$ins->bind_param('ssssssssssi', $slug, $anchor, $badge, $title, $range, $short, $bullets, $image, $content, $pub, $sort);
			$ins->execute();
		}
		$ins->close();
	}

	$items = [];
	$res = $db->query('SELECT id, slug, title, category_anchor, published, sort_order, updated_at FROM cms_products ORDER BY published DESC, sort_order ASC, updated_at DESC LIMIT 400');
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$items[] = $row;
		}
		$res->free();
	}
}
?>
<?php include 'header.php'; ?>
<div class="page-body-wrapper">
	<?php include 'sidebar.php'; ?>
	<div class="page-body">
		<div class="container-fluid">
			<div class="page-title">
				<div class="row">
					<div class="col-sm-6 col-12">
						<h2>Products</h2>
						<p class="mb-0 text-title-gray">Full CRUD for products and dynamic product detail pages.</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Products</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<?php if ($msg !== '') { ?>
				<div class="alert alert-info"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php } ?>

			<div class="row g-4">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
								<h4 class="mb-0"><?php echo $editing ? ($editing['id'] ? 'Edit Product' : 'New Product') : 'Product Items'; ?></h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="../products" target="_blank" rel="noopener">Open Products</a>
									<a class="btn btn-primary" href="products.php?action=new">Add New</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<?php if ($editing) { ?>
								<form method="post" enctype="multipart/form-data" action="products.php<?php echo $editing['id'] ? ('?action=edit&id=' . (int)$editing['id']) : '?action=new'; ?>">
									<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="op" value="save">
									<input type="hidden" name="id" value="<?php echo (int)$editing['id']; ?>">
									<link rel="stylesheet" href="./assets/css/vendors/quill.snow.css">
									<div class="row g-3">
										<div class="col-lg-8">
											<label class="form-label">Title</label>
											<input class="form-control" id="srProductTitle" name="title" required value="<?php echo htmlspecialchars($editing['title'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-4">
											<label class="form-label">Slug</label>
											<input class="form-control" id="srProductSlugPreview" value="<?php echo htmlspecialchars($editing['slug'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
										</div>
										<div class="col-lg-4">
											<label class="form-label">Section/Anchor</label>
											<input class="form-control" name="category_anchor" placeholder="products" value="<?php echo htmlspecialchars($editing['category_anchor'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-4">
											<label class="form-label">Badge label</label>
											<input class="form-control" name="badge_label" placeholder="FOR HOMES" value="<?php echo htmlspecialchars($editing['badge_label'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-4">
											<label class="form-label">Range label</label>
											<input class="form-control" name="range_label" placeholder="3 kW – 19 kW" value="<?php echo htmlspecialchars($editing['range_label'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-12">
											<label class="form-label">Short description</label>
											<textarea class="form-control" name="short_desc" rows="3" required><?php echo htmlspecialchars($editing['short_desc'], ENT_QUOTES, 'UTF-8'); ?></textarea>
										</div>
										<div class="col-12">
											<label class="form-label">Bullet points (one per line)</label>
											<textarea class="form-control" name="bullets" rows="4"><?php echo htmlspecialchars($editing['bullets'], ENT_QUOTES, 'UTF-8'); ?></textarea>
										</div>
										<div class="col-12">
											<label class="form-label">Product image</label>
											<div class="row g-3 align-items-center">
												<div class="col-lg-5">
													<input class="form-control" type="file" name="image_file" id="srProductImageFile" accept="image/png,image/jpeg,image/webp">
												</div>
												<div class="col-lg-7">
													<?php
													$sr_img_prev = trim((string) $editing['image']);
													if ($sr_img_prev === '') {
														$sr_img_prev = '../images/homepage-1/service/service-img-01.jpg';
													} elseif (preg_match('~^https?://~i', $sr_img_prev) !== 1) {
														$sr_img_prev = '../' . ltrim($sr_img_prev, '/');
													}
													?>
													<img id="srProductImagePreview" src="<?php echo htmlspecialchars($sr_img_prev, ENT_QUOTES, 'UTF-8'); ?>" alt="Product image" style="width: 100%; max-width: 360px; height: 160px; object-fit: cover; border-radius: 14px; border: 1px solid rgba(10,25,38,.12); background: #fff;">
												</div>
											</div>
										</div>
										<div class="col-12">
											<label class="form-label">Details content</label>
											<textarea class="form-control" name="content" id="srProductContent" rows="10" style="display:none"><?php echo htmlspecialchars($editing['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
											<div id="srProductEditor" style="background:#fff;border:1px solid rgba(10,25,38,.12);border-radius:14px;overflow:hidden;"></div>
											<style>
												#srProductEditor .ql-toolbar.ql-snow { border: 0; border-bottom: 1px solid rgba(10,25,38,.12); border-top-left-radius: 14px; border-top-right-radius: 14px; }
												#srProductEditor .ql-container.ql-snow { border: 0; height: 340px; overflow-y: auto; }
												#srProductEditor .ql-editor { padding: 16px; }
											</style>
										</div>
										<div class="col-12">
											<div class="row g-3 align-items-end" style="position: sticky; bottom: 10px; z-index: 20; background: #fff; border: 1px solid rgba(10,25,38,.12); border-radius: 14px; padding: 12px;">
												<div class="col-lg-4">
													<label class="form-label">Sort order</label>
													<input class="form-control" name="sort_order" type="number" value="<?php echo (int)$editing['sort_order']; ?>">
												</div>
												<div class="col-lg-8 d-flex align-items-end justify-content-between flex-wrap gap-2">
													<div class="form-check">
														<input class="form-check-input" type="checkbox" id="srProductPublished" name="published" <?php echo ((int)$editing['published'] === 1) ? 'checked' : ''; ?>>
														<label class="form-check-label" for="srProductPublished">Published</label>
													</div>
													<div class="d-flex flex-wrap gap-2">
														<a class="btn btn-outline-primary" href="products.php">Back to list</a>
														<?php if ($editing['id'] && $editing['slug'] !== '') { ?>
															<a class="btn btn-outline-primary" href="../products?open=<?php echo htmlspecialchars($editing['slug'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Preview</a>
														<?php } ?>
														<button type="submit" class="btn btn-primary">Save</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</form>
								<script src="./assets/js/editors/quill.js"></script>
								<script>
									(function () {
										function slugify(s) {
											s = (s || '').toString().toLowerCase().trim();
											s = s.replace(/[^a-z0-9\s-]/g, '');
											s = s.replace(/\s+/g, '-');
											s = s.replace(/-+/g, '-');
											return s;
										}

										var form = document.querySelector('form[action^="products.php"]');
										var titleEl = document.getElementById('srProductTitle');
										var slugEl = document.getElementById('srProductSlugPreview');
										var isNew = <?php echo (int) ($editing['id'] ?? 0) === 0 ? 'true' : 'false'; ?>;
										if (titleEl && slugEl && isNew) {
											var syncSlug = function () {
												slugEl.value = slugify(titleEl.value);
											};
											titleEl.addEventListener('input', syncSlug);
											syncSlug();
										}

										var fileEl = document.getElementById('srProductImageFile');
										var imgEl = document.getElementById('srProductImagePreview');
										if (fileEl && imgEl) {
											fileEl.addEventListener('change', function () {
												var f = fileEl.files && fileEl.files[0] ? fileEl.files[0] : null;
												if (!f) return;
												var url = URL.createObjectURL(f);
												imgEl.src = url;
											});
										}

										var contentEl = document.getElementById('srProductContent');
										var editorEl = document.getElementById('srProductEditor');
										if (contentEl && editorEl && window.Quill) {
											var quill = new Quill(editorEl, {
												theme: 'snow',
												modules: {
													toolbar: [
														[{ header: [1, 2, 3, false] }],
														['bold', 'italic', 'underline', 'strike'],
														[{ list: 'ordered' }, { list: 'bullet' }],
														[{ align: [] }],
														['link', 'image', 'blockquote', 'code-block'],
														['clean']
													]
												}
											});
											quill.root.innerHTML = contentEl.value || '';
											var csrfInput = form ? form.querySelector('input[name="csrf"]') : null;
											var csrf = csrfInput ? csrfInput.value : '';
											var toolbar = quill.getModule('toolbar');
											if (toolbar) {
												toolbar.addHandler('image', function () {
													var saved = quill.getSelection(true);
													var savedIndex = saved && typeof saved.index === 'number' ? saved.index : quill.getLength();
													var picker = document.createElement('input');
													picker.type = 'file';
													picker.accept = 'image/png,image/jpeg,image/webp';
													picker.click();
													picker.addEventListener('change', function () {
														var file = picker.files && picker.files[0] ? picker.files[0] : null;
														if (!file) return;

														var fd = new FormData();
														fd.append('op', 'content_image_upload');
														fd.append('csrf', csrf);
														fd.append('content_image', file);

														fetch('products.php', { method: 'POST', body: fd, credentials: 'same-origin' })
															.then(function (r) { return r.json(); })
															.then(function (data) {
																if (!data || !data.ok || !data.url) {
																	throw new Error((data && data.error) ? data.error : 'Image upload failed.');
																}
																quill.focus();
																var index = savedIndex;
																if (index < 0) index = 0;
																if (index > quill.getLength()) index = quill.getLength();
																quill.insertEmbed(index, 'image', data.url, 'user');
																quill.setSelection(index + 1, 0, 'silent');
															})
															.catch(function (e) {
																alert((e && e.message) ? e.message : 'Image upload failed.');
															});
													});
												});
											}
											if (form) {
												form.addEventListener('submit', function () {
													contentEl.value = quill.root.innerHTML;
												});
											}
										}
									})();
								</script>
							<?php } else { ?>
								<div class="table-responsive">
									<table class="table table-striped mb-0">
										<thead>
											<tr>
												<th>Title</th>
												<th>Anchor</th>
												<th>Status</th>
												<th>Order</th>
												<th class="text-end">Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!$items) { ?>
												<tr>
													<td colspan="5" class="text-center text-title-gray py-4">No products yet.</td>
												</tr>
											<?php } ?>
											<?php foreach ($items as $p) { ?>
												<tr>
													<td>
														<div class="fw-bold"><?php echo htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8'); ?></div>
														<div class="text-title-gray"><?php echo htmlspecialchars((string)$p['slug'], ENT_QUOTES, 'UTF-8'); ?></div>
													</td>
													<td><?php echo htmlspecialchars((string)$p['category_anchor'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td><?php echo ((int)$p['published'] === 1) ? '<span class="badge bg-success">Published</span>' : '<span class="badge bg-secondary">Draft</span>'; ?></td>
													<td><?php echo (int)$p['sort_order']; ?></td>
													<td class="text-end">
														<div class="d-inline-flex gap-2">
															<?php if (!empty($p['slug'])) { ?>
																<a class="btn btn-sm btn-outline-primary" href="../products?open=<?php echo htmlspecialchars((string)$p['slug'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">View</a>
															<?php } ?>
															<a class="btn btn-sm btn-primary" href="products.php?action=edit&id=<?php echo (int)$p['id']; ?>">Edit</a>
															<form method="post" action="products.php" class="d-inline">
																<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
																<input type="hidden" name="op" value="toggle">
																<input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
																<button type="submit" class="btn btn-sm btn-outline-primary">Toggle</button>
															</form>
															<form method="post" action="products.php" class="d-inline" onsubmit="return confirm('Delete this product?');">
																<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
																<input type="hidden" name="op" value="delete">
																<input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
																<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
															</form>
														</div>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include 'footer.php'; ?>
