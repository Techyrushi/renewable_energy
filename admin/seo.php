<?php
require_once __DIR__ . '/db.php';
sr_admin_require_login();

$db = sr_cms_db_required();
sr_cms_migrate($db);

$msg = isset($_GET['msg']) ? (string)$_GET['msg'] : '';
$routeParam = isset($_GET['route']) ? (string)$_GET['route'] : '';
$routeParam = trim($routeParam);

function sr_admin_normalize_route(string $route): string
{
	$route = trim($route);
	if ($route === '') {
		return '';
	}
	if ($route[0] !== '/') {
		$route = '/' . $route;
	}
	if ($route !== '/') {
		$route = rtrim($route, '/');
	}
	return $route;
}

$routeParam = sr_admin_normalize_route($routeParam);

$knownRoutes = [
	['route' => '/', 'label' => 'Home', 'open' => '../'],
	['route' => '/about', 'label' => 'About Us', 'open' => '../about'],
	['route' => '/services', 'label' => 'Services', 'open' => '../services'],
	['route' => '/products', 'label' => 'Products', 'open' => '../products'],
	['route' => '/projects', 'label' => 'Projects', 'open' => '../projects'],
	['route' => '/why-us', 'label' => 'Why Us', 'open' => '../why-us'],
	['route' => '/blog', 'label' => 'Blog', 'open' => '../blog'],
	['route' => '/contact', 'label' => 'Contact', 'open' => '../contact'],
	['route' => '/privacy-policy', 'label' => 'Privacy Policy', 'open' => '../privacy-policy'],
	['route' => '/terms-of-use', 'label' => 'Terms of Use', 'open' => '../terms-of-use'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$csrf = isset($_POST['csrf']) ? (string)$_POST['csrf'] : null;
	if (!sr_admin_verify_csrf($csrf)) {
		header('Location: seo.php?msg=' . rawurlencode('Invalid session. Please refresh and try again.'));
		exit;
	}

	$op = isset($_POST['op']) ? (string)$_POST['op'] : '';
	if ($op === 'save') {
		$route = sr_admin_normalize_route((string)($_POST['route'] ?? ''));
		$title = trim((string)($_POST['title'] ?? ''));
		$description = trim((string)($_POST['description'] ?? ''));
		$keywords = trim((string)($_POST['keywords'] ?? ''));
		$ogImage = trim((string)($_POST['og_image'] ?? ''));
		$noindex = isset($_POST['noindex']) ? 1 : 0;

		if ($route === '' || strlen($route) > 190 || preg_match('/^\\/[a-z0-9\\/-]*$/', $route) !== 1) {
			header('Location: seo.php?msg=' . rawurlencode('Invalid route.'));
			exit;
		}

		$stmt = $db->prepare('INSERT INTO cms_seo_routes (route, title, description, keywords, og_image, noindex) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), keywords=VALUES(keywords), og_image=VALUES(og_image), noindex=VALUES(noindex)');
		if (!$stmt) {
			header('Location: seo.php?route=' . rawurlencode($route) . '&msg=' . rawurlencode('Unable to save SEO settings.'));
			exit;
		}
		$stmt->bind_param('sssssi', $route, $title, $description, $keywords, $ogImage, $noindex);
		$stmt->execute();
		$stmt->close();
		header('Location: seo.php?route=' . rawurlencode($route) . '&msg=' . rawurlencode('SEO saved.'));
		exit;
	}
}

$editing = null;
if ($routeParam !== '') {
	$row = sr_cms_seo_route_get($routeParam);
	$editing = [
		'route' => $routeParam,
		'title' => (string)($row['title'] ?? ''),
		'description' => (string)($row['description'] ?? ''),
		'keywords' => (string)($row['keywords'] ?? ''),
		'og_image' => (string)($row['og_image'] ?? ''),
		'noindex' => (int)($row['noindex'] ?? 0),
	];
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
						<h2>SEO Settings</h2>
						<p class="mb-0 text-title-gray">Update SEO per route (title, description, OG image, and indexing).</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">SEO</li>
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
								<h4 class="mb-0"><?php echo $editing ? ('Edit SEO: ' . htmlspecialchars($editing['route'], ENT_QUOTES, 'UTF-8')) : 'Routes'; ?></h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="seo.php">All Routes</a>
									<a class="btn btn-outline-primary" href="../" target="_blank" rel="noopener">Open Website</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<?php if ($editing) { ?>
								<form method="post" action="seo.php?route=<?php echo rawurlencode($editing['route']); ?>">
									<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="op" value="save">
									<input type="hidden" name="route" value="<?php echo htmlspecialchars($editing['route'], ENT_QUOTES, 'UTF-8'); ?>">

									<div class="row g-3">
										<div class="col-lg-6">
											<label class="form-label">SEO title</label>
											<input class="form-control" name="title" placeholder="Example: About Shivanjali Renewables" value="<?php echo htmlspecialchars($editing['title'], ENT_QUOTES, 'UTF-8'); ?>">
											<div class="form-text">Recommended: 45–60 characters.</div>
										</div>
										<div class="col-lg-6">
											<label class="form-label">OG image URL (optional)</label>
											<input class="form-control" name="og_image" placeholder="images/og/cover.jpg" value="<?php echo htmlspecialchars($editing['og_image'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-12">
											<label class="form-label">Meta description</label>
											<textarea class="form-control" name="description" rows="3" placeholder="Short summary shown in Google results"><?php echo htmlspecialchars($editing['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
											<div class="form-text">Recommended: 120–160 characters.</div>
										</div>
										<div class="col-12">
											<label class="form-label">Keywords (optional)</label>
											<input class="form-control" name="keywords" placeholder="solar, rooftop solar, solar EPC" value="<?php echo htmlspecialchars($editing['keywords'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
										<div class="col-12">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" id="srNoindex" name="noindex" <?php echo ((int)$editing['noindex'] === 1) ? 'checked' : ''; ?>>
												<label class="form-check-label" for="srNoindex">Noindex this route (do not show on Google)</label>
											</div>
										</div>
										<div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-2">
											<a class="btn btn-outline-primary" href="seo.php">Back</a>
											<div class="d-flex gap-2 flex-wrap">
												<a class="btn btn-outline-primary" href="<?php echo htmlspecialchars('../' . ltrim($editing['route'], '/'), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Preview</a>
												<button type="submit" class="btn btn-primary">Save SEO</button>
											</div>
										</div>
									</div>
								</form>
							<?php } else { ?>
								<div class="table-responsive">
									<table class="table table-striped mb-0">
										<thead>
											<tr>
												<th>Route</th>
												<th>Page</th>
												<th class="text-end">Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($knownRoutes as $r) { ?>
												<tr>
													<td class="fw-bold"><?php echo htmlspecialchars($r['route'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td><?php echo htmlspecialchars($r['label'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td class="text-end">
														<a class="btn btn-sm btn-outline-primary" href="<?php echo htmlspecialchars($r['open'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Open</a>
														<a class="btn btn-sm btn-primary" href="seo.php?route=<?php echo rawurlencode($r['route']); ?>">Edit SEO</a>
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

