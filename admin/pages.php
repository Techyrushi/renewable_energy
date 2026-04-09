<?php
require_once __DIR__ . '/db.php';
sr_admin_require_login();

$db = sr_cms_db_required();
sr_cms_migrate($db);

$msg = isset($_GET['msg']) ? (string)$_GET['msg'] : '';
$action = isset($_GET['action']) ? (string)$_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$slugParam = isset($_GET['slug']) ? sr_cms_slugify((string)$_GET['slug']) : '';

if ($slugParam !== '' && $action === 'list' && $id === 0) {
	$stmt = $db->prepare('SELECT id FROM cms_pages WHERE slug=? LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('s', $slugParam);
		$stmt->execute();
		$stmt->bind_result($foundId);
		if ($stmt->fetch()) {
			$stmt->close();
			header('Location: pages.php?action=edit&id=' . (int)$foundId);
			exit;
		}
		$stmt->close();
	}
	$action = 'new';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$csrf = isset($_POST['csrf']) ? (string)$_POST['csrf'] : null;
	if (!sr_admin_verify_csrf($csrf)) {
		header('Location: pages.php?msg=' . rawurlencode('Invalid session. Please try again.'));
		exit;
	}

	$op = isset($_POST['op']) ? (string)$_POST['op'] : '';
	if ($op === 'save') {
		$editId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$slug = trim((string)($_POST['slug'] ?? ''));
		$slug = $slug !== '' ? sr_cms_slugify($slug) : '';
		$title = trim((string)($_POST['title'] ?? ''));
		$heroTitle = trim((string)($_POST['hero_title'] ?? ''));
		$heroSubtitle = trim((string)($_POST['hero_subtitle'] ?? ''));
		$bannerImage = trim((string)($_POST['banner_image'] ?? ''));
		$content = (string)($_POST['content'] ?? '');

		if ($slug === '') {
			$target = $editId > 0 ? ('pages.php?action=edit&id=' . $editId) : 'pages.php?action=new';
			header('Location: ' . $target . '&msg=' . rawurlencode('Slug is required.'));
			exit;
		}

		if (isset($_FILES['banner_image_file']) && is_array($_FILES['banner_image_file'])) {
			$f = $_FILES['banner_image_file'];
			$err = isset($f['error']) ? (int)$f['error'] : UPLOAD_ERR_NO_FILE;
			if ($err !== UPLOAD_ERR_NO_FILE) {
				if ($err !== UPLOAD_ERR_OK) {
					$target = $editId > 0 ? ('pages.php?action=edit&id=' . $editId) : 'pages.php?action=new';
					header('Location: ' . $target . '&msg=' . rawurlencode('Unable to upload banner image.'));
					exit;
				}
				$tmp = (string)($f['tmp_name'] ?? '');
				$size = (int)($f['size'] ?? 0);
				if ($size <= 0 || $size > 4_000_000) {
					$target = $editId > 0 ? ('pages.php?action=edit&id=' . $editId) : 'pages.php?action=new';
					header('Location: ' . $target . '&msg=' . rawurlencode('Banner image must be under 4MB.'));
					exit;
				}
				$info = @getimagesize($tmp);
				$mime = is_array($info) ? (string)($info['mime'] ?? '') : '';
				$ext = '';
				if ($mime === 'image/jpeg') {
					$ext = 'jpg';
				} elseif ($mime === 'image/png') {
					$ext = 'png';
				} elseif ($mime === 'image/webp') {
					$ext = 'webp';
				}
				if ($ext === '') {
					$target = $editId > 0 ? ('pages.php?action=edit&id=' . $editId) : 'pages.php?action=new';
					header('Location: ' . $target . '&msg=' . rawurlencode('Only JPG, PNG, or WEBP images are allowed.'));
					exit;
				}
				$filename = 'page-banner-' . $slug . '-' . time() . '.' . $ext;
				$dest = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $filename;
				if (!@move_uploaded_file($tmp, $dest)) {
					$target = $editId > 0 ? ('pages.php?action=edit&id=' . $editId) : 'pages.php?action=new';
					header('Location: ' . $target . '&msg=' . rawurlencode('Unable to save uploaded banner image.'));
					exit;
				}
				$bannerImage = 'images/' . $filename;
			}
		}

		if ($editId > 0) {
			$stmt = $db->prepare('UPDATE cms_pages SET slug=?, title=?, hero_title=?, hero_subtitle=?, banner_image=?, content=? WHERE id=?');
			if (!$stmt) {
				header('Location: pages.php?msg=' . rawurlencode('Failed to save page.'));
				exit;
			}
			$stmt->bind_param('ssssssi', $slug, $title, $heroTitle, $heroSubtitle, $bannerImage, $content, $editId);
			$stmt->execute();
			$stmt->close();
			header('Location: pages.php?action=edit&id=' . $editId . '&msg=' . rawurlencode('Saved.'));
			exit;
		}

		$stmt = $db->prepare('INSERT INTO cms_pages (slug, title, hero_title, hero_subtitle, banner_image, content) VALUES (?, ?, ?, ?, ?, ?)');
		if (!$stmt) {
			header('Location: pages.php?msg=' . rawurlencode('Failed to create page.'));
			exit;
		}
		$stmt->bind_param('ssssss', $slug, $title, $heroTitle, $heroSubtitle, $bannerImage, $content);
		$stmt->execute();
		$newId = (int)$stmt->insert_id;
		$stmt->close();
		header('Location: pages.php?action=edit&id=' . $newId . '&msg=' . rawurlencode('Created.'));
		exit;
	}

	if ($op === 'delete') {
		$delId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($delId > 0) {
			$stmt = $db->prepare('DELETE FROM cms_pages WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('i', $delId);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: pages.php?msg=' . rawurlencode('Deleted.'));
		exit;
	}
}

$editing = null;
if ($action === 'edit' && $id > 0) {
	$stmt = $db->prepare('SELECT id, slug, title, hero_title, hero_subtitle, banner_image, content FROM cms_pages WHERE id=? LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($rid, $rslug, $rtitle, $rhero, $rsub, $rbanner, $rcontent);
		if ($stmt->fetch()) {
			$editing = [
				'id' => (int)$rid,
				'slug' => (string)$rslug,
				'title' => (string)$rtitle,
				'hero_title' => (string)$rhero,
				'hero_subtitle' => (string)$rsub,
				'banner_image' => (string)$rbanner,
				'content' => (string)$rcontent,
			];
		}
		$stmt->close();
	}
	$action = $editing ? 'edit' : 'list';
}

if ($action === 'new') {
	$editing = [
		'id' => 0,
		'slug' => $slugParam !== '' ? $slugParam : '',
		'title' => '',
		'hero_title' => '',
		'hero_subtitle' => '',
		'banner_image' => '',
		'content' => '',
	];
}

$pages = [];
$res = $db->query('SELECT id, slug, title, updated_at FROM cms_pages ORDER BY updated_at DESC LIMIT 200');
if ($res) {
	while ($row = $res->fetch_assoc()) {
		$pages[] = $row;
	}
	$res->free();
}

$known = [
	['slug' => 'home', 'label' => 'Home', 'url' => '../'],
	['slug' => 'about', 'label' => 'About Us', 'url' => '../about'],
	['slug' => 'services', 'label' => 'Services', 'url' => '../services'],
	['slug' => 'products', 'label' => 'Products', 'url' => '../products'],
	['slug' => 'projects', 'label' => 'Projects', 'url' => '../projects'],
	['slug' => 'why-us', 'label' => 'Why Us', 'url' => '../why-us'],
	['slug' => 'blog', 'label' => 'Blog', 'url' => '../blog'],
	['slug' => 'contact', 'label' => 'Contact', 'url' => '../contact'],
	['slug' => 'privacy-policy', 'label' => 'Privacy Policy', 'url' => '../privacy-policy'],
	['slug' => 'terms-of-use', 'label' => 'Terms of Use', 'url' => '../terms-of-use'],
];
?>
<?php include 'header.php'; ?>
<div class="page-body-wrapper">
	<?php include 'sidebar.php'; ?>
	<div class="page-body">
		<div class="container-fluid">
			<div class="page-title">
				<div class="row">
					<div class="col-sm-6 col-12">
						<h2>Pages</h2>
						<p class="mb-0 text-title-gray">Edit hero titles/subtitles and page-level content blocks.</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Pages</li>
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
								<h4 class="mb-0"><?php echo $editing ? ($editing['id'] ? 'Edit Page' : 'New Page') : 'Pages'; ?></h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="../" target="_blank" rel="noopener">Open Website</a>
									<a class="btn btn-primary" href="pages.php?action=new">Add New</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<?php if ($editing) { ?>
								<form method="post" action="pages.php<?php echo $editing['id'] ? ('?action=edit&id=' . (int)$editing['id']) : '?action=new'; ?>" enctype="multipart/form-data">
									<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="op" value="save">
									<input type="hidden" name="id" value="<?php echo (int)$editing['id']; ?>">

									<ul class="nav nav-tabs border-bottom mb-3" role="tablist">
										<li class="nav-item" role="presentation">
											<button class="nav-link active" data-bs-toggle="tab" data-bs-target="#srPageTabContent" type="button" role="tab">Content</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link" data-bs-toggle="tab" data-bs-target="#srPageTabBanner" type="button" role="tab">Banner</button>
										</li>
										<?php if ($editing['slug'] === 'home') { ?>
											<li class="nav-item" role="presentation">
												<a class="nav-link" href="banners.php">Home Slider</a>
											</li>
										<?php } ?>
										<li class="nav-item ms-auto" role="presentation">
											<a class="nav-link" href="seo.php?route=<?php echo rawurlencode($editing['slug'] === 'home' ? '/' : ('/' . $editing['slug'])); ?>">SEO</a>
										</li>
									</ul>

									<div class="tab-content">
										<div class="tab-pane fade show active" id="srPageTabContent" role="tabpanel">
											<div class="row g-3">
												<div class="col-lg-4">
													<label class="form-label">Slug</label>
													<input class="form-control" name="slug" required value="<?php echo htmlspecialchars($editing['slug'], ENT_QUOTES, 'UTF-8'); ?>">
												</div>
												<div class="col-lg-8">
													<label class="form-label">Page title (optional)</label>
													<input class="form-control" name="title" value="<?php echo htmlspecialchars($editing['title'], ENT_QUOTES, 'UTF-8'); ?>">
												</div>
												<div class="col-12">
													<label class="form-label">Title</label>
													<input class="form-control" name="hero_title" value="<?php echo htmlspecialchars($editing['hero_title'], ENT_QUOTES, 'UTF-8'); ?>">
												</div>
												<div class="col-12">
													<label class="form-label">Description</label>
													<textarea class="form-control" name="hero_subtitle" rows="3"><?php echo htmlspecialchars($editing['hero_subtitle'], ENT_QUOTES, 'UTF-8'); ?></textarea>
												</div>
												<div class="col-12">
													<label class="form-label">Page content (optional, HTML allowed)</label>
													<textarea class="form-control" name="content" rows="10"><?php echo htmlspecialchars($editing['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
												</div>
											</div>
										</div>

										<div class="tab-pane fade" id="srPageTabBanner" role="tabpanel">
											<div class="row g-3">
												<div class="col-12">
													<label class="form-label">Banner image URL (recommended)</label>
													<input class="form-control" name="banner_image" placeholder="images/bg/titlebar-bg.jpg" value="<?php echo htmlspecialchars($editing['banner_image'], ENT_QUOTES, 'UTF-8'); ?>">
													<div class="form-text">This image is used in the page title bar background.</div>
												</div>
												<div class="col-12">
													<label class="form-label">Or upload a banner image</label>
													<input class="form-control" type="file" name="banner_image_file" accept="image/jpeg,image/png,image/webp">
												</div>
												<div class="col-12">
													<div class="p-3 rounded-3 border bg-light">
														<div class="fw-bold mb-1">Tip</div>
														<div class="text-title-gray">Best size: 1920×500 (or wider). Keep text off the edges.</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="mt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
										<a class="btn btn-outline-primary" href="pages.php">Back to list</a>
										<div class="d-flex flex-wrap gap-2">
											<button type="submit" class="btn btn-primary">Save</button>
											<button type="submit" class="btn btn-outline-danger" form="srDeletePageForm" onclick="return confirm('Delete this page entry?');">Delete</button>
										</div>
									</div>
								</form>
								<form id="srDeletePageForm" method="post" action="pages.php" style="display:none">
									<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="op" value="delete">
									<input type="hidden" name="id" value="<?php echo (int)$editing['id']; ?>">
								</form>
							<?php } else { ?>
								<div class="row g-3 mb-4">
									<?php foreach ($known as $k) { ?>
										<div class="col-md-6 col-lg-4">
											<div class="p-3 rounded-3 border bg-light h-100">
												<div class="fw-bold mb-1"><?php echo htmlspecialchars($k['label'], ENT_QUOTES, 'UTF-8'); ?></div>
												<div class="text-title-gray mb-2">Slug: <span class="fw-bold"><?php echo htmlspecialchars($k['slug'], ENT_QUOTES, 'UTF-8'); ?></span></div>
												<div class="d-flex gap-2 flex-wrap">
													<a class="btn btn-sm btn-outline-primary" href="<?php echo htmlspecialchars($k['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Open</a>
													<a class="btn btn-sm btn-primary" href="pages.php?action=new&msg=<?php echo rawurlencode('Create this slug: ' . $k['slug']); ?>">Create</a>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>

								<div class="table-responsive">
									<table class="table table-striped mb-0">
										<thead>
											<tr>
												<th>Slug</th>
												<th>Title</th>
												<th>Updated</th>
												<th class="text-end">Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!$pages) { ?>
												<tr>
													<td colspan="4" class="text-center text-title-gray py-4">No pages configured yet.</td>
												</tr>
											<?php } ?>
											<?php foreach ($pages as $p) { ?>
												<tr>
													<td class="fw-bold"><?php echo htmlspecialchars((string)$p['slug'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td><?php echo htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td class="text-title-gray"><?php echo htmlspecialchars((string)$p['updated_at'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td class="text-end">
														<a class="btn btn-sm btn-primary" href="pages.php?action=edit&id=<?php echo (int)$p['id']; ?>">Edit</a>
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
