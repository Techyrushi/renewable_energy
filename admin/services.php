<?php
require_once __DIR__ . '/db.php';
sr_admin_require_login();

$db = sr_cms_db_required();
sr_cms_migrate($db);

$msg = isset($_GET['msg']) ? (string)$_GET['msg'] : '';
$action = isset($_GET['action']) ? (string)$_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function sr_admin_unique_service_slug(mysqli $db, string $base, int $excludeId = 0): string
{
	$base = sr_cms_slugify($base);
	$slug = $base;
	$i = 2;
	while (true) {
		$sql = 'SELECT id FROM cms_services WHERE slug = ?' . ($excludeId > 0 ? ' AND id <> ?' : '') . ' LIMIT 1';
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

$defaultIcon = '<svg viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M13 2L3 14h7l-1 8 12-14h-7l-1-6z"/></svg>';

function sr_admin_upload_service_image(array $file): array
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

	$absDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'services';
	$relDir = 'images/services';
	if (!is_dir($absDir)) {
		@mkdir($absDir, 0775, true);
	}
	$filename = 'service-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
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

		$up = isset($_FILES['content_image']) && is_array($_FILES['content_image']) ? sr_admin_upload_service_image($_FILES['content_image']) : ['ok' => false, 'path' => '', 'error' => 'Please select an image.'];
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
		header('Location: services.php?msg=' . rawurlencode('Invalid session. Please try again.'));
		exit;
	}

	if ($op === 'save') {
		$editId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$title = trim((string)($_POST['title'] ?? ''));
		$short = trim((string)($_POST['short_desc'] ?? ''));
		$content = (string)($_POST['content'] ?? '');
		$published = isset($_POST['published']) ? 1 : 0;
		$sortOrder = (int)($_POST['sort_order'] ?? 0);

		if ($title === '' || $short === '') {
			$target = $editId > 0 ? ('services.php?action=edit&id=' . $editId) : 'services.php?action=new';
			header('Location: ' . $target . '&msg=' . rawurlencode('Title and short description are required.'));
			exit;
		}

		$slug = '';
		$image = '';
		$iconSvg = $defaultIcon;

		if ($editId > 0) {
			$existingSlug = '';
			$existingImage = '';
			$existingIcon = '';
			$stmtExisting = $db->prepare('SELECT slug, image, icon_svg FROM cms_services WHERE id=? LIMIT 1');
			if ($stmtExisting) {
				$stmtExisting->bind_param('i', $editId);
				$stmtExisting->execute();
				$stmtExisting->bind_result($rslug, $rimg, $ricon);
				if ($stmtExisting->fetch()) {
					$existingSlug = (string) $rslug;
					$existingImage = (string) $rimg;
					$existingIcon = (string) $ricon;
				}
				$stmtExisting->close();
			}

			$slug = trim($existingSlug) !== '' ? $existingSlug : ($title !== '' ? sr_admin_unique_service_slug($db, $title, $editId) : '');
			$image = $existingImage;
			$iconSvg = trim($existingIcon) !== '' ? $existingIcon : $defaultIcon;

			if (isset($_FILES['image_file']) && is_array($_FILES['image_file'])) {
				$up = sr_admin_upload_service_image($_FILES['image_file']);
				if ($up['error'] !== '') {
					header('Location: services.php?action=edit&id=' . $editId . '&msg=' . rawurlencode($up['error']));
					exit;
				}
				if ($up['ok']) {
					$image = (string) $up['path'];
				}
			}

			$stmt = $db->prepare('UPDATE cms_services SET slug=?, title=?, short_desc=?, image=?, icon_svg=?, content=?, published=?, sort_order=? WHERE id=?');
			if (!$stmt) {
				header('Location: services.php?msg=' . rawurlencode('Failed to save service.'));
				exit;
			}
			$stmt->bind_param('ssssssiii', $slug, $title, $short, $image, $iconSvg, $content, $published, $sortOrder, $editId);
			$stmt->execute();
			$stmt->close();
			header('Location: services.php?action=edit&id=' . $editId . '&msg=' . rawurlencode('Saved.'));
			exit;
		}

		$slug = $title !== '' ? sr_admin_unique_service_slug($db, $title, 0) : '';
		if (isset($_FILES['image_file']) && is_array($_FILES['image_file'])) {
			$up = sr_admin_upload_service_image($_FILES['image_file']);
			if ($up['error'] !== '') {
				header('Location: services.php?action=new&msg=' . rawurlencode($up['error']));
				exit;
			}
			if ($up['ok']) {
				$image = (string) $up['path'];
			}
		}

		$stmt = $db->prepare('INSERT INTO cms_services (slug, title, short_desc, image, icon_svg, content, published, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
		if (!$stmt) {
			header('Location: services.php?msg=' . rawurlencode('Failed to create service.'));
			exit;
		}
		$stmt->bind_param('ssssssii', $slug, $title, $short, $image, $iconSvg, $content, $published, $sortOrder);
		$stmt->execute();
		$newId = (int)$stmt->insert_id;
		$stmt->close();
		header('Location: services.php?action=edit&id=' . $newId . '&msg=' . rawurlencode('Created.'));
		exit;
	}

	if ($op === 'delete') {
		$delId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($delId > 0) {
			$stmt = $db->prepare('DELETE FROM cms_services WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('i', $delId);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: services.php?msg=' . rawurlencode('Deleted.'));
		exit;
	}

	if ($op === 'toggle') {
		$tId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($tId > 0) {
			$stmt = $db->prepare('UPDATE cms_services SET published = IF(published=1,0,1) WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('i', $tId);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: services.php?msg=' . rawurlencode('Updated.'));
		exit;
	}
}

$editing = null;
if ($action === 'edit' && $id > 0) {
	$stmt = $db->prepare('SELECT id, slug, title, short_desc, image, icon_svg, content, published, sort_order FROM cms_services WHERE id=? LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($rid, $rslug, $rtitle, $rshort, $rimg, $ricon, $rcontent, $rpub, $rsort);
		if ($stmt->fetch()) {
			$editing = [
				'id' => (int)$rid,
				'slug' => (string)$rslug,
				'title' => (string)$rtitle,
				'short_desc' => (string)$rshort,
				'image' => (string)$rimg,
				'icon_svg' => (string)$ricon,
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
		'title' => '',
		'short_desc' => '',
		'image' => '',
		'icon_svg' => $defaultIcon,
		'content' => '',
		'published' => 1,
		'sort_order' => 0,
	];
}

$items = [];
$res = $db->query('SELECT id, slug, title, published, sort_order, updated_at FROM cms_services ORDER BY published DESC, sort_order ASC, updated_at DESC LIMIT 400');
if ($res) {
	while ($row = $res->fetch_assoc()) {
		$items[] = $row;
	}
	$res->free();
}

if (!$items) {
	$seed = [
		[
			'slug' => 'solar-installation',
			'title' => 'Solar Module & System Installation',
			'short_desc' => 'Turnkey EPC from survey to commissioning',
			'image' => 'images/homepage-2/service/service-img-01.jpg',
			'content' => '',
			'sort_order' => 1,
		],
		[
			'slug' => 'operations-maintenance',
			'title' => 'Operations & Maintenance (O&M)',
			'short_desc' => 'Monitoring, preventive care, rapid troubleshooting',
			'image' => 'images/homepage-2/service/service-img-02.jpg',
			'content' => '',
			'sort_order' => 2,
		],
		[
			'slug' => 'energy-consulting',
			'title' => 'Energy Efficiency Consulting',
			'short_desc' => 'Audits, load analysis, ROI planning',
			'image' => 'images/homepage-2/service/service-img-03.jpg',
			'content' => '',
			'sort_order' => 3,
		],
		[
			'slug' => 'open-access-ppa',
			'title' => 'Open Access & Power Purchase',
			'short_desc' => 'Solar parks and long-term PPA strategy',
			'image' => 'images/homepage-2/service/service-img-04.jpg',
			'content' => '',
			'sort_order' => 4,
		],
	];

	$ins = $db->prepare('INSERT INTO cms_services (slug, title, short_desc, image, icon_svg, content, published, sort_order) VALUES (?, ?, ?, ?, ?, ?, 1, ?)');
	if ($ins) {
		foreach ($seed as $s) {
			$slug = (string) $s['slug'];
			$title = (string) $s['title'];
			$short = (string) $s['short_desc'];
			$image = (string) $s['image'];
			$content = (string) $s['content'];
			$sort = (int) $s['sort_order'];
			$icon = $defaultIcon;
			$ins->bind_param('ssssssi', $slug, $title, $short, $image, $icon, $content, $sort);
			$ins->execute();
		}
		$ins->close();
	}

	$items = [];
	$res = $db->query('SELECT id, slug, title, published, sort_order, updated_at FROM cms_services ORDER BY published DESC, sort_order ASC, updated_at DESC LIMIT 400');
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
						<h2>Services</h2>
						<p class="mb-0 text-title-gray">Full CRUD for services and dynamic service detail pages.</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Services</li>
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
								<h4 class="mb-0"><?php echo $editing ? ($editing['id'] ? 'Edit Service' : 'New Service') : 'Service Items'; ?></h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="../services" target="_blank" rel="noopener">Open Services</a>
									<a class="btn btn-primary" href="services.php?action=new">Add New</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<?php if ($editing) { ?>
								<form method="post" enctype="multipart/form-data" action="services.php<?php echo $editing['id'] ? ('?action=edit&id=' . (int)$editing['id']) : '?action=new'; ?>">
									<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="op" value="save">
									<input type="hidden" name="id" value="<?php echo (int)$editing['id']; ?>">
									<link rel="stylesheet" href="./assets/css/vendors/quill.snow.css">
									<div class="row g-3">
										<div class="col-lg-8">
											<label class="form-label">Title</label>
											<input class="form-control" id="srServiceTitle" name="title" required value="<?php echo htmlspecialchars($editing['title'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-4">
											<label class="form-label">Slug</label>
											<input class="form-control" id="srServiceSlugPreview" value="<?php echo htmlspecialchars($editing['slug'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
										</div>
										<div class="col-12">
											<label class="form-label">Short description</label>
											<textarea class="form-control" name="short_desc" rows="3" required><?php echo htmlspecialchars($editing['short_desc'], ENT_QUOTES, 'UTF-8'); ?></textarea>
										</div>
										<div class="col-12">
											<label class="form-label">Service image</label>
											<div class="row g-3 align-items-center">
												<div class="col-lg-5">
													<input class="form-control" type="file" name="image_file" id="srServiceImageFile" accept="image/png,image/jpeg,image/webp">
												</div>
												<div class="col-lg-7">
													<?php
													$sr_img_prev = trim((string) $editing['image']);
													if ($sr_img_prev === '') {
														$sr_img_prev = '../images/homepage-2/service/service-img-01.jpg';
													} elseif (preg_match('~^https?://~i', $sr_img_prev) !== 1) {
														$sr_img_prev = '../' . ltrim($sr_img_prev, '/');
													}
													?>
													<img id="srServiceImagePreview" src="<?php echo htmlspecialchars($sr_img_prev, ENT_QUOTES, 'UTF-8'); ?>" alt="Service image" style="width: 100%; max-width: 360px; height: 160px; object-fit: cover; border-radius: 14px; border: 1px solid rgba(10,25,38,.12); background: #fff;">
												</div>
											</div>
										</div>
										<div class="col-12">
											<label class="form-label">Icon</label>
											<div class="p-3 rounded-3 border bg-light" style="display:flex;align-items:center;gap:12px;">
												<div style="width:44px;height:44px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:#fff;border:1px solid rgba(10,25,38,.10);">
													<?php echo trim((string) ($editing['icon_svg'] ?? '')) !== '' ? (string) $editing['icon_svg'] : $defaultIcon; ?>
												</div>
												<div class="text-title-gray">Icon is fixed for this service.</div>
											</div>
										</div>
										<div class="col-12 mb-5">
											<label class="form-label">Details content</label>
											<textarea class="form-control" name="content" id="srServiceContent" rows="10" style="display:none"><?php echo htmlspecialchars($editing['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
											<div id="srServiceEditor" style="background:#fff;border:1px solid rgba(10,25,38,.12);border-radius:14px;overflow:hidden;"></div>
											<style>
												#srServiceEditor .ql-toolbar.ql-snow { border: 0; border-bottom: 1px solid rgba(10,25,38,.12); border-top-left-radius: 14px; border-top-right-radius: 14px; }
												#srServiceEditor .ql-container.ql-snow { border: 0; height: 340px; overflow-y: auto; }
												#srServiceEditor .ql-editor { padding: 16px; }
											</style>
										</div>
										<div class="col-12 mt-5">
											<div class="row g-3 align-items-end" style="position: sticky; bottom: 10px; z-index: 20; background: #fff; border: 1px solid rgba(10,25,38,.12); border-radius: 14px; padding: 12px;">
												<div class="col-lg-4">
													<label class="form-label">Sort order</label>
													<input class="form-control" name="sort_order" type="number" value="<?php echo (int)$editing['sort_order']; ?>">
												</div>
												<div class="col-lg-8 d-flex align-items-end justify-content-between flex-wrap gap-2">
													<div class="form-check">
														<input class="form-check-input" type="checkbox" id="srServicePublished" name="published" <?php echo ((int)$editing['published'] === 1) ? 'checked' : ''; ?>>
														<label class="form-check-label" for="srServicePublished">Published</label>
													</div>
													<div class="d-flex flex-wrap gap-2">
														<a class="btn btn-outline-primary" href="services.php">Back to list</a>
														<?php if ($editing['id'] && $editing['slug'] !== '') { ?>
															<a class="btn btn-outline-primary" href="../services/<?php echo htmlspecialchars($editing['slug'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Preview</a>
														<?php } ?>
														<button type="submit" class="btn btn-primary">Save</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</form>
							<?php } else { ?>
								<div class="table-responsive">
									<table class="table table-striped mb-0">
										<thead>
											<tr>
												<th>Title</th>
												<th>Status</th>
												<th>Order</th>
												<th class="text-end">Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!$items) { ?>
												<tr>
													<td colspan="4" class="text-center text-title-gray py-4">No services yet.</td>
												</tr>
											<?php } ?>
											<?php foreach ($items as $s) { ?>
												<tr>
													<td>
														<div class="fw-bold"><?php echo htmlspecialchars((string)$s['title'], ENT_QUOTES, 'UTF-8'); ?></div>
														<div class="text-title-gray"><?php echo htmlspecialchars((string)$s['slug'], ENT_QUOTES, 'UTF-8'); ?></div>
													</td>
													<td><?php echo ((int)$s['published'] === 1) ? '<span class="badge bg-success">Published</span>' : '<span class="badge bg-secondary">Draft</span>'; ?></td>
													<td><?php echo (int)$s['sort_order']; ?></td>
													<td class="text-end">
														<div class="d-inline-flex gap-2">
															<?php if (!empty($s['slug'])) { ?>
																<a class="btn btn-sm btn-outline-primary" href="../services/<?php echo htmlspecialchars((string)$s['slug'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">View</a>
															<?php } ?>
															<a class="btn btn-sm btn-primary" href="services.php?action=edit&id=<?php echo (int)$s['id']; ?>">Edit</a>
															<form method="post" action="services.php" class="d-inline">
																<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
																<input type="hidden" name="op" value="toggle">
																<input type="hidden" name="id" value="<?php echo (int)$s['id']; ?>">
																<button type="submit" class="btn btn-sm btn-outline-primary">Toggle</button>
															</form>
															<form method="post" action="services.php" class="d-inline" onsubmit="return confirm('Delete this service?');">
																<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
																<input type="hidden" name="op" value="delete">
																<input type="hidden" name="id" value="<?php echo (int)$s['id']; ?>">
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
	<?php if ($editing) { ?>
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

				var form = document.querySelector('form[action^="services.php"]');
				var titleEl = document.getElementById('srServiceTitle');
				var slugEl = document.getElementById('srServiceSlugPreview');
				var isNew = <?php echo (int) ($editing['id'] ?? 0) === 0 ? 'true' : 'false'; ?>;
				if (titleEl && slugEl && isNew) {
					var syncSlug = function () {
						slugEl.value = slugify(titleEl.value);
					};
					titleEl.addEventListener('input', syncSlug);
					syncSlug();
				}

				var fileEl = document.getElementById('srServiceImageFile');
				var imgEl = document.getElementById('srServiceImagePreview');
				if (fileEl && imgEl) {
					fileEl.addEventListener('change', function () {
						var f = fileEl.files && fileEl.files[0] ? fileEl.files[0] : null;
						if (!f) return;
						var url = URL.createObjectURL(f);
						imgEl.src = url;
					});
				}

				var contentEl = document.getElementById('srServiceContent');
				var editorEl = document.getElementById('srServiceEditor');
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

								fetch('services.php', { method: 'POST', body: fd, credentials: 'same-origin' })
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
						form.setAttribute('enctype', 'multipart/form-data');
						form.addEventListener('submit', function () {
							contentEl.value = quill.root.innerHTML;
						});
					}
				}
			})();
		</script>
	<?php } ?>
	<?php include 'footer.php'; ?>
