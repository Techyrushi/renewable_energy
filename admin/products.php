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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$csrf = isset($_POST['csrf']) ? (string)$_POST['csrf'] : null;
	if (!sr_admin_verify_csrf($csrf)) {
		header('Location: products.php?msg=' . rawurlencode('Invalid session. Please try again.'));
		exit;
	}

	$op = isset($_POST['op']) ? (string)$_POST['op'] : '';

	if ($op === 'save') {
		$editId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$title = trim((string)($_POST['title'] ?? ''));
		$slugInput = trim((string)($_POST['slug'] ?? ''));
		$slugBase = $slugInput !== '' ? $slugInput : $title;
		$slug = $slugBase !== '' ? sr_admin_unique_product_slug($db, $slugBase, $editId) : '';
		$anchor = trim((string)($_POST['category_anchor'] ?? ''));
		$badge = trim((string)($_POST['badge_label'] ?? ''));
		$range = trim((string)($_POST['range_label'] ?? ''));
		$short = trim((string)($_POST['short_desc'] ?? ''));
		$bullets = trim((string)($_POST['bullets'] ?? ''));
		$image = trim((string)($_POST['image'] ?? ''));
		$content = (string)($_POST['content'] ?? '');
		$published = isset($_POST['published']) ? 1 : 0;
		$sortOrder = (int)($_POST['sort_order'] ?? 0);

		if ($title === '' || $short === '') {
			$target = $editId > 0 ? ('products.php?action=edit&id=' . $editId) : 'products.php?action=new';
			header('Location: ' . $target . '&msg=' . rawurlencode('Title and short description are required.'));
			exit;
		}

		if ($editId > 0) {
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
								<form method="post" action="products.php<?php echo $editing['id'] ? ('?action=edit&id=' . (int)$editing['id']) : '?action=new'; ?>">
									<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="op" value="save">
									<input type="hidden" name="id" value="<?php echo (int)$editing['id']; ?>">
									<div class="row g-3">
										<div class="col-lg-8">
											<label class="form-label">Title</label>
											<input class="form-control" name="title" required value="<?php echo htmlspecialchars($editing['title'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-4">
											<label class="form-label">Slug (optional)</label>
											<input class="form-control" name="slug" value="<?php echo htmlspecialchars($editing['slug'], ENT_QUOTES, 'UTF-8'); ?>">
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
											<label class="form-label">Image path</label>
											<input class="form-control" name="image" placeholder="images/products/residential.jpg" value="<?php echo htmlspecialchars($editing['image'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-12">
											<label class="form-label">Details content (HTML allowed)</label>
											<textarea class="form-control" name="content" rows="12"><?php echo htmlspecialchars($editing['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
										</div>
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
													<a class="btn btn-outline-primary" href="../products/<?php echo htmlspecialchars($editing['slug'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Preview</a>
												<?php } ?>
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
																<a class="btn btn-sm btn-outline-primary" href="../products/<?php echo htmlspecialchars((string)$p['slug'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">View</a>
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
