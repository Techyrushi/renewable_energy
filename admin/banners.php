<?php
require_once __DIR__ . '/db.php';
sr_admin_require_login();

$db = sr_cms_db_required();
sr_cms_migrate($db);

$msg = isset($_GET['msg']) ? (string)$_GET['msg'] : '';
$action = isset($_GET['action']) ? (string)$_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$csrf = isset($_POST['csrf']) ? (string)$_POST['csrf'] : null;
	if (!sr_admin_verify_csrf($csrf)) {
		header('Location: banners.php?msg=' . rawurlencode('Invalid session. Please try again.'));
		exit;
	}

	$op = isset($_POST['op']) ? (string)$_POST['op'] : '';
	if ($op === 'save') {
		$editId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$image = trim((string)($_POST['image'] ?? ''));
		$kicker = trim((string)($_POST['kicker'] ?? ''));
		$title = trim((string)($_POST['title'] ?? ''));
		$subtitle = trim((string)($_POST['subtitle'] ?? ''));
		$primaryLabel = trim((string)($_POST['primary_label'] ?? ''));
		$primaryUrl = trim((string)($_POST['primary_url'] ?? ''));
		$secondaryLabel = trim((string)($_POST['secondary_label'] ?? ''));
		$secondaryUrl = trim((string)($_POST['secondary_url'] ?? ''));
		$sortOrder = (int)($_POST['sort_order'] ?? 0);
		$isActive = isset($_POST['is_active']) ? 1 : 0;

		if ($title === '') {
			$target = $editId > 0 ? ('banners.php?action=edit&id=' . $editId) : 'banners.php?action=new';
			header('Location: ' . $target . '&msg=' . rawurlencode('Title is required.'));
			exit;
		}

		if ($editId > 0) {
			$stmt = $db->prepare('UPDATE cms_banners SET image=?, kicker=?, title=?, subtitle=?, primary_label=?, primary_url=?, secondary_label=?, secondary_url=?, sort_order=?, is_active=? WHERE id=?');
			if (!$stmt) {
				header('Location: banners.php?msg=' . rawurlencode('Failed to save banner.'));
				exit;
			}
			$stmt->bind_param('ssssssssiii', $image, $kicker, $title, $subtitle, $primaryLabel, $primaryUrl, $secondaryLabel, $secondaryUrl, $sortOrder, $isActive, $editId);
			$stmt->execute();
			$stmt->close();
			header('Location: banners.php?action=edit&id=' . $editId . '&msg=' . rawurlencode('Saved.'));
			exit;
		}

		$stmt = $db->prepare('INSERT INTO cms_banners (image, kicker, title, subtitle, primary_label, primary_url, secondary_label, secondary_url, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
		if (!$stmt) {
			header('Location: banners.php?msg=' . rawurlencode('Failed to create banner.'));
			exit;
		}
		$stmt->bind_param('ssssssssii', $image, $kicker, $title, $subtitle, $primaryLabel, $primaryUrl, $secondaryLabel, $secondaryUrl, $sortOrder, $isActive);
		$stmt->execute();
		$newId = (int)$stmt->insert_id;
		$stmt->close();
		header('Location: banners.php?action=edit&id=' . $newId . '&msg=' . rawurlencode('Created.'));
		exit;
	}

	if ($op === 'delete') {
		$delId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($delId > 0) {
			$stmt = $db->prepare('DELETE FROM cms_banners WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('i', $delId);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: banners.php?msg=' . rawurlencode('Deleted.'));
		exit;
	}

	if ($op === 'toggle') {
		$tId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($tId > 0) {
			$stmt = $db->prepare('UPDATE cms_banners SET is_active = IF(is_active=1,0,1) WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('i', $tId);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: banners.php?msg=' . rawurlencode('Updated.'));
		exit;
	}
}

$editing = null;
if ($action === 'edit' && $id > 0) {
	$stmt = $db->prepare('SELECT id, image, kicker, title, subtitle, primary_label, primary_url, secondary_label, secondary_url, sort_order, is_active FROM cms_banners WHERE id=? LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($rid, $rimg, $rk, $rt, $rs, $rpl, $rpu, $rsl, $rsu, $rso, $ra);
		if ($stmt->fetch()) {
			$editing = [
				'id' => (int)$rid,
				'image' => (string)$rimg,
				'kicker' => (string)$rk,
				'title' => (string)$rt,
				'subtitle' => (string)$rs,
				'primary_label' => (string)$rpl,
				'primary_url' => (string)$rpu,
				'secondary_label' => (string)$rsl,
				'secondary_url' => (string)$rsu,
				'sort_order' => (int)$rso,
				'is_active' => (int)$ra,
			];
		}
		$stmt->close();
	}
	$action = $editing ? 'edit' : 'list';
}

if ($action === 'new') {
	$editing = [
		'id' => 0,
		'image' => '',
		'kicker' => '',
		'title' => '',
		'subtitle' => '',
		'primary_label' => 'Get a Free Quote',
		'primary_url' => 'contact',
		'secondary_label' => 'View Projects',
		'secondary_url' => 'projects',
		'sort_order' => 0,
		'is_active' => 1,
	];
}

$banners = [];
$res = $db->query('SELECT id, title, is_active, sort_order, updated_at FROM cms_banners ORDER BY is_active DESC, sort_order ASC, updated_at DESC LIMIT 200');
if ($res) {
	while ($row = $res->fetch_assoc()) {
		$banners[] = $row;
	}
	$res->free();
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
						<h2>Banners</h2>
						<p class="mb-0 text-title-gray">Manage homepage hero banners (slides).</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Banners</li>
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
								<h4 class="mb-0"><?php echo $editing ? ($editing['id'] ? 'Edit Banner' : 'New Banner') : 'All Banners'; ?></h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="../" target="_blank" rel="noopener">Preview Home</a>
									<a class="btn btn-primary" href="banners.php?action=new">Add New</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<?php if ($editing) { ?>
								<form method="post" action="banners.php<?php echo $editing['id'] ? ('?action=edit&id=' . (int)$editing['id']) : '?action=new'; ?>">
									<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="op" value="save">
									<input type="hidden" name="id" value="<?php echo (int)$editing['id']; ?>">
									<div class="row g-3">
										<div class="col-12">
											<label class="form-label">Background image path</label>
											<input class="form-control" name="image" placeholder="images/Slider/slider-1.jpg" value="<?php echo htmlspecialchars($editing['image'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-4">
											<label class="form-label">Kicker</label>
											<input class="form-control" name="kicker" value="<?php echo htmlspecialchars($editing['kicker'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-8">
											<label class="form-label">Title</label>
											<input class="form-control" name="title" required value="<?php echo htmlspecialchars($editing['title'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-12">
											<label class="form-label">Subtitle</label>
											<textarea class="form-control" name="subtitle" rows="3"><?php echo htmlspecialchars($editing['subtitle'], ENT_QUOTES, 'UTF-8'); ?></textarea>
										</div>
										<div class="col-lg-6">
											<label class="form-label">Primary button label</label>
											<input class="form-control" name="primary_label" value="<?php echo htmlspecialchars($editing['primary_label'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-6">
											<label class="form-label">Primary button URL</label>
											<input class="form-control" name="primary_url" value="<?php echo htmlspecialchars($editing['primary_url'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-6">
											<label class="form-label">Secondary button label</label>
											<input class="form-control" name="secondary_label" value="<?php echo htmlspecialchars($editing['secondary_label'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-6">
											<label class="form-label">Secondary button URL</label>
											<input class="form-control" name="secondary_url" value="<?php echo htmlspecialchars($editing['secondary_url'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-4">
											<label class="form-label">Sort order</label>
											<input class="form-control" name="sort_order" type="number" value="<?php echo (int)$editing['sort_order']; ?>">
										</div>
										<div class="col-lg-8 d-flex align-items-end justify-content-between flex-wrap gap-2">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" id="srBannerActive" name="is_active" <?php echo ((int)$editing['is_active'] === 1) ? 'checked' : ''; ?>>
												<label class="form-check-label" for="srBannerActive">Active</label>
											</div>
											<div class="d-flex flex-wrap gap-2">
												<a class="btn btn-outline-primary" href="banners.php">Back to list</a>
												<button type="submit" class="btn btn-primary">Save</button>
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
												<th>Active</th>
												<th>Order</th>
												<th class="text-end">Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!$banners) { ?>
												<tr>
													<td colspan="4" class="text-center text-title-gray py-4">No banners yet.</td>
												</tr>
											<?php } ?>
											<?php foreach ($banners as $b) { ?>
												<tr>
													<td class="fw-bold"><?php echo htmlspecialchars((string)$b['title'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td><?php echo ((int)$b['is_active'] === 1) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>'; ?></td>
													<td><?php echo (int)$b['sort_order']; ?></td>
													<td class="text-end">
														<div class="d-inline-flex gap-2">
															<a class="btn btn-sm btn-primary" href="banners.php?action=edit&id=<?php echo (int)$b['id']; ?>">Edit</a>
															<form method="post" action="banners.php" class="d-inline">
																<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
																<input type="hidden" name="op" value="toggle">
																<input type="hidden" name="id" value="<?php echo (int)$b['id']; ?>">
																<button type="submit" class="btn btn-sm btn-outline-primary">Toggle</button>
															</form>
															<form method="post" action="banners.php" class="d-inline" onsubmit="return confirm('Delete this banner?');">
																<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
																<input type="hidden" name="op" value="delete">
																<input type="hidden" name="id" value="<?php echo (int)$b['id']; ?>">
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

