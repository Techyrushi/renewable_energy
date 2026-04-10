<?php
require_once __DIR__ . '/db.php';
sr_admin_require_login();

$db = sr_cms_db_required();
sr_cms_migrate($db);

$msg = isset($_GET['msg']) ? (string)$_GET['msg'] : '';
$action = isset($_GET['action']) ? (string)$_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function sr_admin_project_category_label(string $cat): string
{
	$cat = strtolower(trim($cat));
	if ($cat === 'openaccess') return 'Open Access';
	if ($cat === 'parks') return 'Solar Parks';
	return 'Rooftop';
}

function sr_admin_unique_project_slug(mysqli $db, string $base, int $excludeId = 0): string
{
	$base = sr_cms_slugify($base);
	$slug = $base;
	$i = 2;
	while (true) {
		$sql = 'SELECT id FROM cms_projects WHERE slug = ?' . ($excludeId > 0 ? ' AND id <> ?' : '') . ' LIMIT 1';
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

function sr_admin_upload_project_image(array $file): array
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

	$absDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'projects';
	$relDir = 'images/projects';
	if (!is_dir($absDir)) {
		@mkdir($absDir, 0775, true);
	}
	$filename = 'project-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
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

		$up = isset($_FILES['content_image']) && is_array($_FILES['content_image']) ? sr_admin_upload_project_image($_FILES['content_image']) : ['ok' => false, 'path' => '', 'error' => 'Please select an image.'];
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
		header('Location: projects.php?msg=' . rawurlencode('Invalid session. Please try again.'));
		exit;
	}

	if ($op === 'save') {
		$editId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$category = trim((string)($_POST['category'] ?? 'rooftop'));
		$category = strtolower($category);
		if (!in_array($category, ['rooftop', 'openaccess', 'parks'], true)) {
			$category = 'rooftop';
		}
		$categoryLabel = sr_admin_project_category_label($category);
		$title = trim((string)($_POST['title'] ?? ''));
		$slug = '';
		$location = trim((string)($_POST['location_label'] ?? ''));
		$capacity = trim((string)($_POST['capacity_label'] ?? ''));
		$savings = trim((string)($_POST['savings_label'] ?? ''));
		$outcome = trim((string)($_POST['outcome_label'] ?? ''));
		$image = '';
		$content = (string)($_POST['content'] ?? '');
		$featured = isset($_POST['featured']) ? 1 : 0;
		$sortOrder = (int)($_POST['sort_order'] ?? 0);

		if ($title === '') {
			$target = $editId > 0 ? ('projects.php?action=edit&id=' . $editId) : 'projects.php?action=new';
			header('Location: ' . $target . '&msg=' . rawurlencode('Title is required.'));
			exit;
		}

		if ($editId > 0) {
			$existingSlug = '';
			$existingImage = '';
			$stmtExisting = $db->prepare('SELECT slug, image FROM cms_projects WHERE id=? LIMIT 1');
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

			$slug = trim($existingSlug) !== '' ? $existingSlug : ($title !== '' ? sr_admin_unique_project_slug($db, $title, $editId) : '');
			$image = $existingImage;

			if (isset($_FILES['image_file']) && is_array($_FILES['image_file'])) {
				$up = sr_admin_upload_project_image($_FILES['image_file']);
				if ($up['error'] !== '') {
					header('Location: projects.php?action=edit&id=' . $editId . '&msg=' . rawurlencode($up['error']));
					exit;
				}
				if ($up['ok']) {
					$image = (string) $up['path'];
				}
			}

			$stmt = $db->prepare('UPDATE cms_projects SET slug=?, category=?, category_label=?, title=?, location_label=?, capacity_label=?, savings_label=?, outcome_label=?, image=?, content=?, featured=?, sort_order=? WHERE id=?');
			if (!$stmt) {
				header('Location: projects.php?msg=' . rawurlencode('Failed to save project.'));
				exit;
			}
			$stmt->bind_param('ssssssssssiii', $slug, $category, $categoryLabel, $title, $location, $capacity, $savings, $outcome, $image, $content, $featured, $sortOrder, $editId);
			$stmt->execute();
			$stmt->close();
			header('Location: projects.php?action=edit&id=' . $editId . '&msg=' . rawurlencode('Saved.'));
			exit;
		}

		$slug = $title !== '' ? sr_admin_unique_project_slug($db, $title, 0) : '';
		if (isset($_FILES['image_file']) && is_array($_FILES['image_file'])) {
			$up = sr_admin_upload_project_image($_FILES['image_file']);
			if ($up['error'] !== '') {
				header('Location: projects.php?action=new&msg=' . rawurlencode($up['error']));
				exit;
			}
			if ($up['ok']) {
				$image = (string) $up['path'];
			}
		}

		$stmt = $db->prepare('INSERT INTO cms_projects (slug, category, category_label, title, location_label, capacity_label, savings_label, outcome_label, image, content, featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
		if (!$stmt) {
			header('Location: projects.php?msg=' . rawurlencode('Failed to create project.'));
			exit;
		}
		$stmt->bind_param('ssssssssssii', $slug, $category, $categoryLabel, $title, $location, $capacity, $savings, $outcome, $image, $content, $featured, $sortOrder);
		$stmt->execute();
		$newId = (int)$stmt->insert_id;
		$stmt->close();
		header('Location: projects.php?action=edit&id=' . $newId . '&msg=' . rawurlencode('Created.'));
		exit;
	}

	if ($op === 'delete') {
		$delId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($delId > 0) {
			$stmt = $db->prepare('DELETE FROM cms_projects WHERE id = ?');
			if ($stmt) {
				$stmt->bind_param('i', $delId);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: projects.php?msg=' . rawurlencode('Deleted.'));
		exit;
	}

	if ($op === 'toggle') {
		$tId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($tId > 0) {
			$stmt = $db->prepare('UPDATE cms_projects SET featured = IF(featured=1,0,1) WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('i', $tId);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: projects.php?msg=' . rawurlencode('Updated.'));
		exit;
	}
}

$editing = null;
if ($action === 'edit' && $id > 0) {
	$stmt = $db->prepare('SELECT id, slug, category, title, location_label, capacity_label, savings_label, outcome_label, image, content, featured, sort_order FROM cms_projects WHERE id=? LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($rid, $rslug, $rcat, $rtitle, $rloc, $rcap, $rsav, $rout, $rimg, $rcontent, $rfeat, $rsort);
		if ($stmt->fetch()) {
			$editing = [
				'id' => (int)$rid,
				'slug' => (string)$rslug,
				'category' => (string)$rcat,
				'title' => (string)$rtitle,
				'location_label' => (string)$rloc,
				'capacity_label' => (string)$rcap,
				'savings_label' => (string)$rsav,
				'outcome_label' => (string)$rout,
				'image' => (string)$rimg,
				'content' => (string)$rcontent,
				'featured' => (int)$rfeat,
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
		'category' => 'rooftop',
		'title' => '',
		'location_label' => '',
		'capacity_label' => '',
		'savings_label' => '',
		'outcome_label' => '',
		'image' => '',
		'content' => '',
		'featured' => 1,
		'sort_order' => 0,
	];
}

$projects = [];
$res = $db->query('SELECT id, slug, category, title, featured, sort_order, updated_at FROM cms_projects ORDER BY featured DESC, sort_order ASC, updated_at DESC LIMIT 300');
if ($res) {
	while ($row = $res->fetch_assoc()) {
		$projects[] = $row;
	}
	$res->free();
}

if (!$projects) {
	$seed = [
		[
			'category' => 'rooftop',
			'title' => 'Commercial Warehouse — Nashik',
			'location' => 'Nashik',
			'capacity' => '100 kW',
			'savings' => '~₹8 lakh/year',
			'outcome' => 'Reduced electricity bill and improved sustainability',
			'image' => 'images/portfolio/portfolio-01.jpg',
			'content' => '<p>This rooftop solar installation was designed for high daytime consumption and a stable load profile. The system delivers strong annual generation and reduces dependence on grid power.</p><div class="sr-modal-section-title">Highlights</div><ul class="sr-modal-list sr-icon-list"><li><i class="pbmit-base-icon-tick"></i>Turnkey design, procurement, and commissioning</li><li><i class="pbmit-base-icon-tick"></i>Optimized layout for maximum roof utilization</li><li><i class="pbmit-base-icon-tick"></i>Monitoring enabled for performance tracking</li></ul>',
			'featured' => 1,
			'sort' => 1,
		],
		[
			'category' => 'rooftop',
			'title' => 'Educational Institution — Nashik',
			'location' => 'Nashik',
			'capacity' => '50 kW',
			'savings' => '~₹4 lakh/year',
			'outcome' => 'Lower operating costs and green campus initiative',
			'image' => 'images/portfolio/portfolio-02.jpg',
			'content' => '<p>A clean-energy upgrade for an educational campus with predictable daytime usage. The installation supports long-term savings and sustainability goals.</p>',
			'featured' => 1,
			'sort' => 2,
		],
		[
			'category' => 'rooftop',
			'title' => 'Hotel — Maharashtra',
			'location' => 'Maharashtra',
			'capacity' => '80 kW',
			'savings' => '~₹6.5 lakh/year',
			'outcome' => 'Reduced energy costs for hospitality operations',
			'image' => 'images/portfolio/portfolio-03.jpg',
			'content' => '<p>Rooftop solar system engineered for hospitality load patterns, ensuring reliable daytime generation and improved cost efficiency.</p>',
			'featured' => 1,
			'sort' => 3,
		],
		[
			'category' => 'openaccess',
			'title' => 'Varun Agro Food Processing Pvt. Ltd.',
			'location' => 'Maharashtra',
			'capacity' => '900 kW',
			'savings' => 'Significant tariff savings',
			'outcome' => 'Transformative results in energy savings and sustainability',
			'image' => 'images/portfolio/portfolio-04.jpg',
			'content' => '<p>Open Access captive project planned to deliver large-scale clean energy, with end-to-end coordination for feasibility, execution, and compliance.</p>',
			'featured' => 1,
			'sort' => 4,
		],
		[
			'category' => 'parks',
			'title' => 'Utility Solar Park — Maharashtra',
			'location' => 'Maharashtra',
			'capacity' => '5 MW',
			'savings' => '',
			'outcome' => 'End-to-end EPC & O&M support',
			'image' => 'images/portfolio/portfolio-05.jpg',
			'content' => '<p>Utility-scale solar park delivery support including engineering, execution planning, and operations readiness.</p>',
			'featured' => 1,
			'sort' => 5,
		],
	];

	$ins = $db->prepare('INSERT INTO cms_projects (slug, category, category_label, title, location_label, capacity_label, savings_label, outcome_label, image, content, featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	if ($ins) {
		foreach ($seed as $p) {
			$category = (string) $p['category'];
			$catLabel = sr_admin_project_category_label($category);
			$title = (string) $p['title'];
			$slug = sr_admin_unique_project_slug($db, $title, 0);
			$location = (string) $p['location'];
			$capacity = (string) $p['capacity'];
			$savings = (string) $p['savings'];
			$outcome = (string) $p['outcome'];
			$image = (string) $p['image'];
			$content = (string) $p['content'];
			$featured = (int) $p['featured'];
			$sort = (int) $p['sort'];
			$ins->bind_param('ssssssssssii', $slug, $category, $catLabel, $title, $location, $capacity, $savings, $outcome, $image, $content, $featured, $sort);
			$ins->execute();
		}
		$ins->close();
	}

	$projects = [];
	$res = $db->query('SELECT id, slug, category, title, featured, sort_order, updated_at FROM cms_projects ORDER BY featured DESC, sort_order ASC, updated_at DESC LIMIT 300');
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$projects[] = $row;
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
						<h2>Projects</h2>
						<p class="mb-0 text-title-gray">Manage featured projects for the frontend gallery.</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Projects</li>
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
								<h4 class="mb-0"><?php echo $editing ? ($editing['id'] ? 'Edit Project' : 'New Project') : 'Project Items'; ?></h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="../projects#gallery" target="_blank" rel="noopener">Open Gallery</a>
									<a class="btn btn-primary" href="projects.php?action=new">Add New</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<?php if ($editing) { ?>
								<form method="post" enctype="multipart/form-data" action="projects.php<?php echo $editing['id'] ? ('?action=edit&id=' . (int)$editing['id']) : '?action=new'; ?>">
									<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="op" value="save">
									<input type="hidden" name="id" value="<?php echo (int)$editing['id']; ?>">
									<link rel="stylesheet" href="./assets/css/vendors/quill.snow.css">

									<div class="row g-3">
										<div class="col-lg-4">
											<label class="form-label">Category</label>
											<select class="form-select" name="category">
												<option value="rooftop" <?php echo ($editing['category'] === 'rooftop') ? 'selected' : ''; ?>>Rooftop</option>
												<option value="openaccess" <?php echo ($editing['category'] === 'openaccess') ? 'selected' : ''; ?>>Open Access</option>
												<option value="parks" <?php echo ($editing['category'] === 'parks') ? 'selected' : ''; ?>>Solar Parks</option>
											</select>
										</div>
										<div class="col-lg-8">
											<label class="form-label">Title</label>
											<input class="form-control" id="srProjectTitle" name="title" required value="<?php echo htmlspecialchars($editing['title'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-12">
											<label class="form-label">Slug</label>
											<input class="form-control" id="srProjectSlugPreview" value="<?php echo htmlspecialchars($editing['slug'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
										</div>
										<div class="col-lg-4">
											<label class="form-label">Location</label>
											<input class="form-control" name="location_label" placeholder="Nashik" value="<?php echo htmlspecialchars($editing['location_label'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-4">
											<label class="form-label">Capacity</label>
											<input class="form-control" name="capacity_label" placeholder="100 kW" value="<?php echo htmlspecialchars($editing['capacity_label'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-4">
											<label class="form-label">Savings</label>
											<input class="form-control" name="savings_label" placeholder="~₹8 lakh/year" value="<?php echo htmlspecialchars($editing['savings_label'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-12">
											<label class="form-label">Outcome (optional)</label>
											<input class="form-control" name="outcome_label" placeholder="Transformative savings & sustainability" value="<?php echo htmlspecialchars($editing['outcome_label'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-12">
											<label class="form-label">Project image</label>
											<div class="row g-3 align-items-center">
												<div class="col-lg-5">
													<input class="form-control" type="file" name="image_file" id="srProjectImageFile" accept="image/png,image/jpeg,image/webp">
												</div>
												<div class="col-lg-7">
													<?php
													$sr_img_prev = trim((string) $editing['image']);
													if ($sr_img_prev === '') {
														$sr_img_prev = '../images/portfolio/portfolio-01.jpg';
													} elseif (preg_match('~^https?://~i', $sr_img_prev) !== 1) {
														$sr_img_prev = '../' . ltrim($sr_img_prev, '/');
													}
													?>
													<img id="srProjectImagePreview" src="<?php echo htmlspecialchars($sr_img_prev, ENT_QUOTES, 'UTF-8'); ?>" alt="Project image" style="width: 100%; max-width: 360px; height: 160px; object-fit: cover; border-radius: 14px; border: 1px solid rgba(10,25,38,.12); background: #fff;">
												</div>
											</div>
										</div>
										<div class="col-12">
											<label class="form-label">Details content (HTML allowed)</label>
											<textarea class="form-control" name="content" id="srProjectContent" rows="10" style="display:none"><?php echo htmlspecialchars($editing['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
											<div id="srProjectEditor" style="background:#fff;border:1px solid rgba(10,25,38,.12);border-radius:14px;overflow:hidden;"></div>
											<style>
												#srProjectEditor .ql-toolbar.ql-snow { border: 0; border-bottom: 1px solid rgba(10,25,38,.12); border-top-left-radius: 14px; border-top-right-radius: 14px; }
												#srProjectEditor .ql-container.ql-snow { border: 0; height: 340px; overflow-y: auto; }
												#srProjectEditor .ql-editor { padding: 16px; }
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
														<input class="form-check-input" type="checkbox" id="srProjectFeatured" name="featured" <?php echo ((int)$editing['featured'] === 1) ? 'checked' : ''; ?>>
														<label class="form-check-label" for="srProjectFeatured">Show in gallery</label>
													</div>
													<div class="d-flex flex-wrap gap-2">
														<a class="btn btn-outline-primary" href="projects.php">Back to list</a>
														<?php if ($editing['id'] && $editing['slug'] !== '') { ?>
															<a class="btn btn-outline-primary" href="../projects/<?php echo htmlspecialchars($editing['slug'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Preview</a>
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

										var form = document.querySelector('form[action^="projects.php"]');
										var titleEl = document.getElementById('srProjectTitle');
										var slugEl = document.getElementById('srProjectSlugPreview');
										var isNew = <?php echo (int) ($editing['id'] ?? 0) === 0 ? 'true' : 'false'; ?>;
										if (titleEl && slugEl && isNew) {
											var syncSlug = function () {
												slugEl.value = slugify(titleEl.value);
											};
											titleEl.addEventListener('input', syncSlug);
											syncSlug();
										}

										var fileEl = document.getElementById('srProjectImageFile');
										var imgEl = document.getElementById('srProjectImagePreview');
										if (fileEl && imgEl) {
											fileEl.addEventListener('change', function () {
												var f = fileEl.files && fileEl.files[0] ? fileEl.files[0] : null;
												if (!f) return;
												var url = URL.createObjectURL(f);
												imgEl.src = url;
											});
										}

										var contentEl = document.getElementById('srProjectContent');
										var editorEl = document.getElementById('srProjectEditor');
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

														fetch('projects.php', { method: 'POST', body: fd, credentials: 'same-origin' })
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
												<th>Category</th>
												<th>Featured</th>
												<th>Order</th>
												<th class="text-end">Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!$projects) { ?>
												<tr>
													<td colspan="5" class="text-center text-title-gray py-4">No projects yet.</td>
												</tr>
											<?php } ?>
											<?php foreach ($projects as $p) { ?>
												<tr>
													<td>
														<div class="fw-bold"><?php echo htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8'); ?></div>
														<?php if (!empty($p['slug'])) { ?>
															<div class="text-title-gray"><?php echo htmlspecialchars((string)$p['slug'], ENT_QUOTES, 'UTF-8'); ?></div>
														<?php } ?>
													</td>
													<td><?php echo htmlspecialchars(sr_admin_project_category_label((string)$p['category']), ENT_QUOTES, 'UTF-8'); ?></td>
													<td>
														<?php if ((int)$p['featured'] === 1) { ?>
															<span class="badge bg-success">Yes</span>
														<?php } else { ?>
															<span class="badge bg-secondary">No</span>
														<?php } ?>
													</td>
													<td><?php echo (int)$p['sort_order']; ?></td>
													<td class="text-end">
														<div class="d-inline-flex gap-2">
															<?php if (!empty($p['slug'])) { ?>
																<a class="btn btn-sm btn-outline-primary" href="../projects/<?php echo htmlspecialchars((string)$p['slug'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">View</a>
															<?php } ?>
															<a class="btn btn-sm btn-primary" href="projects.php?action=edit&id=<?php echo (int)$p['id']; ?>">Edit</a>
															<form method="post" action="projects.php" class="d-inline">
																<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
																<input type="hidden" name="op" value="toggle">
																<input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
																<button type="submit" class="btn btn-sm btn-outline-primary">Toggle</button>
															</form>
															<form method="post" action="projects.php" class="d-inline" onsubmit="return confirm('Delete this project?');">
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
