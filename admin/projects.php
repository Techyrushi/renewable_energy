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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$csrf = isset($_POST['csrf']) ? (string)$_POST['csrf'] : null;
	if (!sr_admin_verify_csrf($csrf)) {
		header('Location: projects.php?msg=' . rawurlencode('Invalid session. Please try again.'));
		exit;
	}

	$op = isset($_POST['op']) ? (string)$_POST['op'] : '';

	if ($op === 'save') {
		$editId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$category = trim((string)($_POST['category'] ?? 'rooftop'));
		$category = strtolower($category);
		if (!in_array($category, ['rooftop', 'openaccess', 'parks'], true)) {
			$category = 'rooftop';
		}
		$categoryLabel = sr_admin_project_category_label($category);
		$title = trim((string)($_POST['title'] ?? ''));
		$slugInput = trim((string)($_POST['slug'] ?? ''));
		$slugBase = $slugInput !== '' ? $slugInput : $title;
		$slug = $slugBase !== '' ? sr_admin_unique_project_slug($db, $slugBase, $editId) : '';
		$location = trim((string)($_POST['location_label'] ?? ''));
		$capacity = trim((string)($_POST['capacity_label'] ?? ''));
		$savings = trim((string)($_POST['savings_label'] ?? ''));
		$outcome = trim((string)($_POST['outcome_label'] ?? ''));
		$image = trim((string)($_POST['image'] ?? ''));
		$content = (string)($_POST['content'] ?? '');
		$featured = isset($_POST['featured']) ? 1 : 0;
		$sortOrder = (int)($_POST['sort_order'] ?? 0);

		if ($title === '') {
			$target = $editId > 0 ? ('projects.php?action=edit&id=' . $editId) : 'projects.php?action=new';
			header('Location: ' . $target . '&msg=' . rawurlencode('Title is required.'));
			exit;
		}

		if ($editId > 0) {
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
								<form method="post" action="projects.php<?php echo $editing['id'] ? ('?action=edit&id=' . (int)$editing['id']) : '?action=new'; ?>">
									<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="op" value="save">
									<input type="hidden" name="id" value="<?php echo (int)$editing['id']; ?>">

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
											<input class="form-control" name="title" required value="<?php echo htmlspecialchars($editing['title'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-12">
											<label class="form-label">Slug</label>
											<input class="form-control" name="slug" placeholder="commercial-warehouse-nashik" value="<?php echo htmlspecialchars($editing['slug'], ENT_QUOTES, 'UTF-8'); ?>">
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
											<label class="form-label">Image path</label>
											<input class="form-control" name="image" placeholder="images/portfolio/portfolio-01.jpg" value="<?php echo htmlspecialchars($editing['image'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-12">
											<label class="form-label">Details content (HTML allowed)</label>
											<textarea class="form-control" name="content" rows="10"><?php echo htmlspecialchars($editing['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
										</div>
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
