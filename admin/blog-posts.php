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

function sr_admin_upload_blog_image(array $file): array
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

	$absDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'blog';
	$relDir = 'images/blog';
	if (!is_dir($absDir)) {
		@mkdir($absDir, 0775, true);
	}
	$filename = 'blog-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
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

		$up = isset($_FILES['content_image']) && is_array($_FILES['content_image']) ? sr_admin_upload_blog_image($_FILES['content_image']) : ['ok' => false, 'path' => '', 'error' => 'Please select an image.'];
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
		header('Location: blog-posts.php?msg=' . rawurlencode('Invalid session. Please try again.'));
		exit;
	}

	if ($op === 'save') {
		$editId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$title = trim((string)($_POST['title'] ?? ''));
		$category = trim((string)($_POST['category'] ?? ''));
		$dateLabel = trim((string)($_POST['date_label'] ?? ''));
		$readTime = trim((string)($_POST['read_time'] ?? ''));
		$coverImage = '';
		$excerpt = trim((string)($_POST['excerpt'] ?? ''));
		$content = (string)($_POST['content'] ?? '');
		$published = isset($_POST['published']) ? 1 : 0;
		$slug = '';

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
			$existingSlug = '';
			$existingCover = '';
			$stmtExisting = $db->prepare('SELECT slug, cover_image FROM cms_blog_posts WHERE id=? LIMIT 1');
			if ($stmtExisting) {
				$stmtExisting->bind_param('i', $editId);
				$stmtExisting->execute();
				$stmtExisting->bind_result($rslug, $rimg);
				if ($stmtExisting->fetch()) {
					$existingSlug = (string) $rslug;
					$existingCover = (string) $rimg;
				}
				$stmtExisting->close();
			}

			$slug = trim($existingSlug) !== '' ? $existingSlug : sr_admin_unique_blog_slug($db, $title, $editId);
			$coverImage = $existingCover;

			if (isset($_FILES['cover_image_file']) && is_array($_FILES['cover_image_file'])) {
				$up = sr_admin_upload_blog_image($_FILES['cover_image_file']);
				if ($up['error'] !== '') {
					header('Location: blog-posts.php?action=edit&id=' . $editId . '&msg=' . rawurlencode($up['error']));
					exit;
				}
				if ($up['ok']) {
					$coverImage = (string) $up['path'];
				}
			}

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

		$slug = sr_admin_unique_blog_slug($db, $title, 0);
		if (isset($_FILES['cover_image_file']) && is_array($_FILES['cover_image_file'])) {
			$up = sr_admin_upload_blog_image($_FILES['cover_image_file']);
			if ($up['error'] !== '') {
				header('Location: blog-posts.php?action=new&msg=' . rawurlencode($up['error']));
				exit;
			}
			if ($up['ok']) {
				$coverImage = (string) $up['path'];
			}
		} else {
			$coverImage = trim((string)($_POST['cover_image'] ?? ''));
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

if (!$posts) {
	$seed = [
		[
			'slug' => 'rooftop-solar-nashik-savings',
			'category' => 'Solar Basics',
			'date' => 'Apr 2026',
			'read' => '8 min read',
			'image' => 'images/blog/blog-01.jpg',
			'title' => 'How Much Can You Really Save with Rooftop Solar in Nashik?',
			'excerpt' => 'A practical breakdown of system sizing, monthly bills, subsidy impact, and typical payback for Nashik homes.',
			'content' => '<p>A rooftop solar system can cut your bill dramatically — but the exact savings depend on your monthly units, roof orientation, and system size. This guide helps you estimate savings realistically for Nashik.</p><h2>What drives savings?</h2><ul><li>Current tariff and monthly units</li><li>System size vs. daytime consumption</li><li>Net metering export credits</li><li>Subsidy eligibility and installation quality</li></ul><p>For an accurate quote, share your last 3 electricity bills and roof photos with our team.</p>',
			'published' => 1,
		],
		[
			'slug' => 'pm-surya-ghar-yojana-2024-guide',
			'category' => 'Government Schemes',
			'date' => 'Apr 2026',
			'read' => '10 min read',
			'image' => 'images/blog/blog-02.jpg',
			'title' => 'PM Surya Ghar Yojana 2024: Who Qualifies and How to Apply',
			'excerpt' => 'Eligibility, documents, subsidy flow, and a step-by-step checklist to apply without confusion.',
			'content' => '<p>Government subsidy programs can reduce your initial investment. Here is a clear checklist for eligibility, documents, and the end-to-end process.</p><h2>Checklist</h2><ul><li>Valid electricity connection</li><li>Suitable rooftop space</li><li>KYC and bill copies</li><li>Vendor selection and inspection</li></ul><p>We can guide you through the process and system sizing.</p>',
			'published' => 1,
		],
		[
			'slug' => 'open-access-solar-guide-maharashtra',
			'category' => 'Case Studies',
			'date' => 'Apr 2026',
			'read' => '12 min read',
			'image' => 'images/blog/blog-03.jpg',
			'title' => 'Open Access Solar for Industries: A Complete Guide for Maharashtra',
			'excerpt' => 'Learn when Open Access makes sense, how billing works, key approvals, and common project risks.',
			'content' => '<p>Open Access can deliver significant savings for high-consumption industries, but requires proper feasibility checks and approvals.</p><h2>Key points</h2><ul><li>Eligibility criteria</li><li>Wheeling, banking, and scheduling</li><li>PPA structure and risk management</li></ul><p>Talk to our team for a feasibility assessment.</p>',
			'published' => 1,
		],
		[
			'slug' => 'mistakes-choosing-solar-epc',
			'category' => 'FAQs',
			'date' => 'Apr 2026',
			'read' => '8 min read',
			'image' => 'images/blog/blog-04.jpg',
			'title' => '5 Mistakes to Avoid When Choosing a Solar EPC Company',
			'excerpt' => 'From Tier-1 components to warranty clarity—use this checklist to select the right EPC partner.',
			'content' => '<p>Choosing the right EPC partner decides performance for the next 25 years. Here are common mistakes and how to avoid them.</p><ol><li>Ignoring component quality</li><li>Unclear warranty terms</li><li>No monitoring and O&amp;M plan</li><li>Weak engineering and documentation</li><li>Not checking past installations</li></ol>',
			'published' => 1,
		],
		[
			'slug' => 'net-metering-maharashtra-explained',
			'category' => 'Solar Basics',
			'date' => 'Apr 2026',
			'read' => '7 min read',
			'image' => 'images/blog/blog-05.jpg',
			'title' => 'Net Metering in Maharashtra: How to Earn from Your Solar System',
			'excerpt' => 'Understand approvals, net meter installation, export credits, and how billing is calculated.',
			'content' => '<p>Net metering lets you export surplus solar energy and receive credits on your bill. Here is how approvals and billing typically work.</p><h2>Process</h2><ul><li>Application and feasibility</li><li>Installation and inspection</li><li>Net meter commissioning</li><li>Monthly billing credits</li></ul>',
			'published' => 1,
		],
		[
			'slug' => 'solar-ppa-explained-business',
			'category' => 'Industry News',
			'date' => 'Apr 2026',
			'read' => '9 min read',
			'image' => 'images/blog/blog-06.jpg',
			'title' => 'What is a Solar PPA and Is It Right for Your Business?',
			'excerpt' => 'A clear explanation of PPA models, pricing, contract terms, and when it outperforms CAPEX.',
			'content' => '<p>A PPA model allows you to adopt solar with low upfront cost. It can be ideal for businesses focused on cashflow and predictable tariffs.</p><h2>When PPA makes sense</h2><ul><li>High day-time consumption</li><li>Long-term site availability</li><li>Preference for OPEX model</li></ul>',
			'published' => 1,
		],
	];

	$ins = $db->prepare('INSERT INTO cms_blog_posts (slug, title, category, date_label, read_time, cover_image, excerpt, content, published, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	if ($ins) {
		$now = date('Y-m-d H:i:s');
		foreach ($seed as $p) {
			$slug = (string) $p['slug'];
			$title = (string) $p['title'];
			$category = (string) $p['category'];
			$date = (string) $p['date'];
			$read = (string) $p['read'];
			$image = (string) $p['image'];
			$excerpt = (string) $p['excerpt'];
			$content = (string) $p['content'];
			$pub = (int) $p['published'];
			$publishedAt = $pub === 1 ? $now : null;
			$ins->bind_param('ssssssssis', $slug, $title, $category, $date, $read, $image, $excerpt, $content, $pub, $publishedAt);
			$ins->execute();
		}
		$ins->close();
	}

	$posts = [];
	$res = $db->query('SELECT id, slug, title, category, published, COALESCE(published_at, updated_at) AS dt FROM cms_blog_posts ORDER BY published DESC, published_at DESC, updated_at DESC LIMIT 200');
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$posts[] = $row;
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
								<form method="post" enctype="multipart/form-data" action="blog-posts.php<?php echo $editing['id'] ? ('?action=edit&id=' . (int)$editing['id']) : '?action=new'; ?>">
									<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="op" value="save">
									<input type="hidden" name="id" value="<?php echo (int)$editing['id']; ?>">
									<link rel="stylesheet" href="./assets/css/vendors/quill.snow.css">

									<div class="row g-3">
										<div class="col-lg-8">
											<label class="form-label">Title</label>
											<input class="form-control" id="srPostTitle" name="title" required value="<?php echo htmlspecialchars($editing['title'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-lg-4">
											<label class="form-label">Slug</label>
											<input class="form-control" id="srPostSlugPreview" value="<?php echo htmlspecialchars($editing['slug'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
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
											<label class="form-label">Cover image</label>
											<div class="row g-3 align-items-center">
												<div class="col-lg-5">
													<input class="form-control" type="file" name="cover_image_file" id="srPostCoverFile" accept="image/png,image/jpeg,image/webp">
													<input type="hidden" name="cover_image" value="<?php echo htmlspecialchars($editing['cover_image'], ENT_QUOTES, 'UTF-8'); ?>">
												</div>
												<div class="col-lg-7">
													<?php
													$sr_img_prev = trim((string) $editing['cover_image']);
													if ($sr_img_prev === '') {
														$sr_img_prev = '../images/blog/blog-01.jpg';
													} elseif (preg_match('~^https?://~i', $sr_img_prev) !== 1) {
														$sr_img_prev = '../' . ltrim($sr_img_prev, '/');
													}
													?>
													<img id="srPostCoverPreview" src="<?php echo htmlspecialchars($sr_img_prev, ENT_QUOTES, 'UTF-8'); ?>" alt="Cover image" style="width: 100%; max-width: 360px; height: 160px; object-fit: cover; border-radius: 14px; border: 1px solid rgba(10,25,38,.12); background: #fff;">
												</div>
											</div>
										</div>
										<div class="col-12">
											<label class="form-label">Excerpt</label>
											<textarea class="form-control" name="excerpt" rows="3" required><?php echo htmlspecialchars($editing['excerpt'], ENT_QUOTES, 'UTF-8'); ?></textarea>
										</div>
										<div class="col-12">
											<label class="form-label">Content</label>
											<textarea class="form-control" name="content" id="srPostContent" rows="12" required style="display:none"><?php echo htmlspecialchars($editing['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
											<div id="srPostEditor" style="background:#fff;border:1px solid rgba(10,25,38,.12);border-radius:14px;overflow:hidden;"></div>
											<style>
												#srPostEditor .ql-toolbar.ql-snow { border: 0; border-bottom: 1px solid rgba(10,25,38,.12); border-top-left-radius: 14px; border-top-right-radius: 14px; }
												#srPostEditor .ql-container.ql-snow { border: 0; height: 360px; overflow-y: auto; }
												#srPostEditor .ql-editor { padding: 16px; }
											</style>
										</div>
										<div class="col-12">
											<div class="d-flex align-items-center justify-content-between flex-wrap gap-2" style="position: sticky; bottom: 10px; z-index: 20; background: #fff; border: 1px solid rgba(10,25,38,.12); border-radius: 14px; padding: 12px;">
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

										var form = document.querySelector('form[action^="blog-posts.php"]');
										var titleEl = document.getElementById('srPostTitle');
										var slugEl = document.getElementById('srPostSlugPreview');
										var isNew = <?php echo (int) ($editing['id'] ?? 0) === 0 ? 'true' : 'false'; ?>;
										if (titleEl && slugEl && isNew) {
											var syncSlug = function () {
												slugEl.value = slugify(titleEl.value);
											};
											titleEl.addEventListener('input', syncSlug);
											syncSlug();
										}

										var fileEl = document.getElementById('srPostCoverFile');
										var imgEl = document.getElementById('srPostCoverPreview');
										if (fileEl && imgEl) {
											fileEl.addEventListener('change', function () {
												var f = fileEl.files && fileEl.files[0] ? fileEl.files[0] : null;
												if (!f) return;
												var url = URL.createObjectURL(f);
												imgEl.src = url;
											});
										}

										var contentEl = document.getElementById('srPostContent');
										var editorEl = document.getElementById('srPostEditor');
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

														fetch('blog-posts.php', { method: 'POST', body: fd, credentials: 'same-origin' })
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
