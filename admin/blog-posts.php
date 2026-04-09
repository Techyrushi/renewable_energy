<?php
require_once __DIR__ . '/db.php';
sr_admin_require_login();

$db = sr_cms_db_required();
sr_cms_migrate($db);

$msg = isset($_GET['msg']) ? (string)$_GET['msg'] : '';
$action = isset($_GET['action']) ? (string)$_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function sr_admin_unique_blog_slug(mysqli $db, string $base, int $excludeId = 0): string
{
	$base = sr_cms_slugify($base);
	$slug = $base;
	$i = 2;
	while (true) {
		$sql = 'SELECT id FROM cms_blog_posts WHERE slug = ?' . ($excludeId > 0 ? ' AND id <> ?' : '') . ' LIMIT 1';
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
		header('Location: blog-posts.php?msg=' . rawurlencode('Invalid session. Please try again.'));
		exit;
	}

	$op = isset($_POST['op']) ? (string)$_POST['op'] : '';

	if ($op === 'save') {
		$editId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$title = trim((string)($_POST['title'] ?? ''));
		$category = trim((string)($_POST['category'] ?? ''));
		$dateLabel = trim((string)($_POST['date_label'] ?? ''));
		$readTime = trim((string)($_POST['read_time'] ?? ''));
		$coverImage = trim((string)($_POST['cover_image'] ?? ''));
		$excerpt = trim((string)($_POST['excerpt'] ?? ''));
		$content = (string)($_POST['content'] ?? '');
		$published = isset($_POST['published']) ? 1 : 0;
		$slugInput = trim((string)($_POST['slug'] ?? ''));
		$slugBase = $slugInput !== '' ? $slugInput : $title;
		$slug = sr_admin_unique_blog_slug($db, $slugBase, $editId);

		if ($title === '' || $excerpt === '' || trim($content) === '') {
			$target = $editId > 0 ? ('blog-posts.php?action=edit&id=' . $editId) : 'blog-posts.php?action=new';
			header('Location: ' . $target . '&msg=' . rawurlencode('Title, excerpt, and content are required.'));
			exit;
		}

		$publishedAt = null;
		if ($published === 1) {
			$publishedAt = date('Y-m-d H:i:s');
		}

		if ($editId > 0) {
			$stmt = $db->prepare('UPDATE cms_blog_posts SET slug=?, title=?, category=?, date_label=?, read_time=?, cover_image=?, excerpt=?, content=?, published=?, published_at=IF(?=1, COALESCE(published_at, ?), NULL) WHERE id=?');
			if (!$stmt) {
				header('Location: blog-posts.php?msg=' . rawurlencode('Failed to save post.'));
				exit;
			}
			$stmt->bind_param('ssssssssisisi', $slug, $title, $category, $dateLabel, $readTime, $coverImage, $excerpt, $content, $published, $published, $publishedAt, $editId);
			$stmt->execute();
			$stmt->close();
			header('Location: blog-posts.php?action=edit&id=' . $editId . '&msg=' . rawurlencode('Saved.'));
			exit;
		}

		$stmt = $db->prepare('INSERT INTO cms_blog_posts (slug, title, category, date_label, read_time, cover_image, excerpt, content, published, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
		if (!$stmt) {
			header('Location: blog-posts.php?msg=' . rawurlencode('Failed to create post.'));
			exit;
		}
		$stmt->bind_param('ssssssssis', $slug, $title, $category, $dateLabel, $readTime, $coverImage, $excerpt, $content, $published, $publishedAt);
		$stmt->execute();
		$newId = (int)$stmt->insert_id;
		$stmt->close();
		header('Location: blog-posts.php?action=edit&id=' . $newId . '&msg=' . rawurlencode('Created.'));
		exit;
	}

	if ($op === 'delete') {
		$delId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($delId > 0) {
			$stmt = $db->prepare('DELETE FROM cms_blog_posts WHERE id = ?');
			if ($stmt) {
				$stmt->bind_param('i', $delId);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: blog-posts.php?msg=' . rawurlencode('Deleted.'));
		exit;
	}

	if ($op === 'toggle') {
		$tId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($tId > 0) {
			$stmt = $db->prepare('UPDATE cms_blog_posts SET published = IF(published=1,0,1), published_at = IF(published=1, published_at, COALESCE(published_at, NOW())) WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('i', $tId);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: blog-posts.php?msg=' . rawurlencode('Updated.'));
		exit;
	}
}

$editing = null;
if ($action === 'edit' && $id > 0) {
	$stmt = $db->prepare('SELECT id, slug, title, category, date_label, read_time, cover_image, excerpt, content, published FROM cms_blog_posts WHERE id=? LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($rid, $rslug, $rtitle, $rcat, $rdate, $rread, $rimg, $rex, $rcontent, $rpub);
		if ($stmt->fetch()) {
			$editing = [
				'id' => (int)$rid,
				'slug' => (string)$rslug,
				'title' => (string)$rtitle,
				'category' => (string)$rcat,
				'date_label' => (string)$rdate,
				'read_time' => (string)$rread,
				'cover_image' => (string)$rimg,
				'excerpt' => (string)$rex,
				'content' => (string)$rcontent,
				'published' => (int)$rpub,
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
		'category' => '',
		'date_label' => '',
		'read_time' => '',
		'cover_image' => '',
		'excerpt' => '',
		'content' => '',
		'published' => 1,
	];
}

$posts = [];
$res = $db->query('SELECT id, slug, title, category, published, COALESCE(published_at, updated_at) AS dt FROM cms_blog_posts ORDER BY published DESC, published_at DESC, updated_at DESC LIMIT 200');
if ($res) {
	while ($row = $res->fetch_assoc()) {
		$posts[] = $row;
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
						<h2>Blog / Resources</h2>
						<p class="mb-0 text-title-gray">Create and manage blog posts used on the frontend.</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Blog</li>
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
								<h4 class="mb-0"><?php echo $editing ? ($editing['id'] ? 'Edit Post' : 'New Post') : 'Posts'; ?></h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="../blog" target="_blank" rel="noopener">Open Blog</a>
									<a class="btn btn-primary" href="blog-posts.php?action=new">Add New</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<?php if ($editing) { ?>
								<form method="post" action="blog-posts.php<?php echo $editing['id'] ? ('?action=edit&id=' . (int)$editing['id']) : '?action=new'; ?>">
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
											<label class="form-label">Category</label>
											<input class="form-control" name="category" value="<?php echo htmlspecialchars($editing['category'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-4">
											<label class="form-label">Date label</label>
											<input class="form-control" name="date_label" placeholder="Apr 2026" value="<?php echo htmlspecialchars($editing['date_label'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-4">
											<label class="form-label">Read time</label>
											<input class="form-control" name="read_time" placeholder="8 min read" value="<?php echo htmlspecialchars($editing['read_time'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-12">
											<label class="form-label">Cover image path</label>
											<input class="form-control" name="cover_image" placeholder="images/blog/blog-01.jpg" value="<?php echo htmlspecialchars($editing['cover_image'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-12">
											<label class="form-label">Excerpt</label>
											<textarea class="form-control" name="excerpt" rows="3" required><?php echo htmlspecialchars($editing['excerpt'], ENT_QUOTES, 'UTF-8'); ?></textarea>
										</div>
										<div class="col-12">
											<label class="form-label">Content (HTML allowed)</label>
											<textarea class="form-control" name="content" rows="12" required><?php echo htmlspecialchars($editing['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
										</div>
										<div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-2">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" id="srPostPublished" name="published" <?php echo ((int)$editing['published'] === 1) ? 'checked' : ''; ?>>
												<label class="form-check-label" for="srPostPublished">Published</label>
											</div>
											<div class="d-flex flex-wrap gap-2">
												<a class="btn btn-outline-primary" href="blog-posts.php">Back to list</a>
												<?php if ($editing['id']) { ?>
													<a class="btn btn-outline-primary" href="../blog/<?php echo htmlspecialchars($editing['slug'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Preview</a>
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
												<th>Category</th>
												<th>Status</th>
												<th>Updated</th>
												<th class="text-end">Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!$posts) { ?>
												<tr>
													<td colspan="5" class="text-center text-title-gray py-4">No posts yet.</td>
												</tr>
											<?php } ?>
											<?php foreach ($posts as $p) { ?>
												<tr>
													<td>
														<div class="fw-bold"><?php echo htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8'); ?></div>
														<div class="text-title-gray"><?php echo htmlspecialchars((string)$p['slug'], ENT_QUOTES, 'UTF-8'); ?></div>
													</td>
													<td><?php echo htmlspecialchars((string)$p['category'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td>
														<?php if ((int)$p['published'] === 1) { ?>
															<span class="badge bg-success">Published</span>
														<?php } else { ?>
															<span class="badge bg-secondary">Draft</span>
														<?php } ?>
													</td>
													<td class="text-title-gray"><?php echo htmlspecialchars((string)$p['dt'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td class="text-end">
														<div class="d-inline-flex gap-2">
															<a class="btn btn-sm btn-outline-primary" href="../blog/<?php echo htmlspecialchars((string)$p['slug'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">View</a>
															<a class="btn btn-sm btn-primary" href="blog-posts.php?action=edit&id=<?php echo (int)$p['id']; ?>">Edit</a>
															<form method="post" action="blog-posts.php" class="d-inline">
																<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
																<input type="hidden" name="op" value="toggle">
																<input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
																<button type="submit" class="btn btn-sm btn-outline-primary">Toggle</button>
															</form>
															<form method="post" action="blog-posts.php" class="d-inline" onsubmit="return confirm('Delete this post?');">
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

