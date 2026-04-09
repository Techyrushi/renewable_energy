<?php
require_once __DIR__ . '/db.php';
sr_admin_require_login();

$sr_db = sr_cms_db_try();
$sr_report = isset($_GET['report']) ? (string)$_GET['report'] : '';
$sr_report_csrf = isset($_GET['csrf']) ? (string)$_GET['csrf'] : '';

if ($sr_report !== '') {
	if (!sr_admin_verify_csrf($sr_report_csrf)) {
		http_response_code(403);
		echo 'Invalid session.';
		exit;
	}
	$db = sr_cms_db_required();
	sr_cms_migrate($db);

	$from = isset($_GET['from']) ? (string)$_GET['from'] : '';
	$to = isset($_GET['to']) ? (string)$_GET['to'] : '';
	$status = isset($_GET['status']) ? (string)$_GET['status'] : '';

	$from = preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) ? $from : date('Y-m-d', strtotime('-30 days'));
	$to = preg_match('/^\d{4}-\d{2}-\d{2}$/', $to) ? $to : date('Y-m-d');
	$status = in_array($status, ['new', 'in_progress', 'closed'], true) ? $status : '';

	$fromDt = $from . ' 00:00:00';
	$toDtExclusive = date('Y-m-d', strtotime($to . ' +1 day')) . ' 00:00:00';

	if ($sr_report === 'enquiries_csv') {
		$sql = "SELECT id, full_name, phone, email, city, customer_type, system_size, source, message, status, created_at
			FROM cms_enquiries
			WHERE created_at >= ? AND created_at < ?";
		$params = [$fromDt, $toDtExclusive];
		$types = 'ss';
		if ($status !== '') {
			$sql .= " AND status = ?";
			$params[] = $status;
			$types .= 's';
		}
		$sql .= " ORDER BY created_at DESC";

		$stmt = $db->prepare($sql);
		if (!$stmt) {
			http_response_code(500);
			echo 'Unable to generate report.';
			exit;
		}
		$stmt->bind_param($types, ...$params);
		$stmt->execute();
		$res = $stmt->get_result();

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="enquiries_' . $from . '_to_' . $to . ($status !== '' ? ('_' . $status) : '') . '.csv"');
		$out = fopen('php://output', 'w');
		fputcsv($out, ['ID', 'Full Name', 'Phone', 'Email', 'City', 'Customer Type', 'System Size', 'Source', 'Message', 'Status', 'Created At']);
		if ($res) {
			while ($row = $res->fetch_assoc()) {
				fputcsv($out, [
					(string)($row['id'] ?? ''),
					(string)($row['full_name'] ?? ''),
					(string)($row['phone'] ?? ''),
					(string)($row['email'] ?? ''),
					(string)($row['city'] ?? ''),
					(string)($row['customer_type'] ?? ''),
					(string)($row['system_size'] ?? ''),
					(string)($row['source'] ?? ''),
					(string)($row['message'] ?? ''),
					(string)($row['status'] ?? ''),
					(string)($row['created_at'] ?? ''),
				]);
			}
		}
		fclose($out);
		$stmt->close();
		exit;
	}

	if ($sr_report === 'summary_csv') {
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="summary_' . $from . '_to_' . $to . '.csv"');
		$out = fopen('php://output', 'w');
		fputcsv($out, ['Metric', 'Value']);

		$stmt = $db->prepare("SELECT
				SUM(status='new') AS c_new,
				SUM(status='in_progress') AS c_in_progress,
				SUM(status='closed') AS c_closed,
				COUNT(*) AS c_total
			FROM cms_enquiries
			WHERE created_at >= ? AND created_at < ?");
		if ($stmt) {
			$stmt->bind_param('ss', $fromDt, $toDtExclusive);
			$stmt->execute();
			$stmt->bind_result($cNew, $cProg, $cClosed, $cTotal);
			if ($stmt->fetch()) {
				fputcsv($out, ['Enquiries total', (string)$cTotal]);
				fputcsv($out, ['Enquiries new', (string)$cNew]);
				fputcsv($out, ['Enquiries in progress', (string)$cProg]);
				fputcsv($out, ['Enquiries closed', (string)$cClosed]);
			}
			$stmt->close();
		}

		$res = $db->query("SELECT
				(SELECT COUNT(*) FROM cms_projects) AS projects_total,
				(SELECT COUNT(*) FROM cms_products) AS products_total,
				(SELECT COUNT(*) FROM cms_services) AS services_total,
				(SELECT COUNT(*) FROM cms_blog_posts) AS blog_total,
				(SELECT COUNT(*) FROM cms_blog_posts WHERE published=1) AS blog_published,
				(SELECT COUNT(*) FROM cms_banners WHERE is_active=1) AS banners_active");
		if ($res && ($row = $res->fetch_assoc())) {
			fputcsv($out, ['Projects total', (string)($row['projects_total'] ?? '0')]);
			fputcsv($out, ['Products total', (string)($row['products_total'] ?? '0')]);
			fputcsv($out, ['Services total', (string)($row['services_total'] ?? '0')]);
			fputcsv($out, ['Blog posts total', (string)($row['blog_total'] ?? '0')]);
			fputcsv($out, ['Blog posts published', (string)($row['blog_published'] ?? '0')]);
			fputcsv($out, ['Active banners', (string)($row['banners_active'] ?? '0')]);
		}
		if ($res) {
			$res->free();
		}
		fclose($out);
		exit;
	}

	http_response_code(404);
	echo 'Unknown report.';
	exit;
}

$sr_enquiries_today = '—';
$sr_enquiries_7d = '—';
$sr_enquiries_30d = '—';
$sr_enquiries_open = '—';
$sr_enquiries_new_30d = 0;
$sr_enquiries_in_progress_30d = 0;
$sr_enquiries_closed_30d = 0;

$sr_projects_total = '—';
$sr_products_total = '—';
$sr_services_total = '—';
$sr_blog_total = '—';
$sr_blog_published = '—';
$sr_banners_active = '—';

$sr_recent_enquiries = [];
$sr_enquiries_trend_labels = [];
$sr_enquiries_trend_values = [];

if ($sr_db instanceof mysqli) {
	sr_cms_migrate($sr_db);

	$res = $sr_db->query("SELECT COUNT(*) AS c FROM cms_enquiries WHERE created_at >= (NOW() - INTERVAL 1 DAY)");
	if ($res && ($row = $res->fetch_assoc())) {
		$sr_enquiries_today = (string)($row['c'] ?? '0');
	}
	if ($res) $res->free();

	$res = $sr_db->query("SELECT COUNT(*) AS c FROM cms_enquiries WHERE created_at >= (NOW() - INTERVAL 7 DAY)");
	if ($res && ($row = $res->fetch_assoc())) {
		$sr_enquiries_7d = (string)($row['c'] ?? '0');
	}
	if ($res) $res->free();

	$res = $sr_db->query("SELECT COUNT(*) AS c FROM cms_enquiries WHERE created_at >= (NOW() - INTERVAL 30 DAY)");
	if ($res && ($row = $res->fetch_assoc())) {
		$sr_enquiries_30d = (string)($row['c'] ?? '0');
	}
	if ($res) $res->free();

	$res = $sr_db->query("SELECT COUNT(*) AS c FROM cms_enquiries WHERE status IN ('new','in_progress')");
	if ($res && ($row = $res->fetch_assoc())) {
		$sr_enquiries_open = (string)($row['c'] ?? '0');
	}
	if ($res) $res->free();

	$res = $sr_db->query("SELECT
			SUM(status='new') AS c_new,
			SUM(status='in_progress') AS c_in_progress,
			SUM(status='closed') AS c_closed
		FROM cms_enquiries
		WHERE created_at >= (NOW() - INTERVAL 30 DAY)");
	if ($res && ($row = $res->fetch_assoc())) {
		$sr_enquiries_new_30d = (int)($row['c_new'] ?? 0);
		$sr_enquiries_in_progress_30d = (int)($row['c_in_progress'] ?? 0);
		$sr_enquiries_closed_30d = (int)($row['c_closed'] ?? 0);
	}
	if ($res) $res->free();

	$res = $sr_db->query("SELECT
			(SELECT COUNT(*) FROM cms_projects) AS projects_total,
			(SELECT COUNT(*) FROM cms_products) AS products_total,
			(SELECT COUNT(*) FROM cms_services) AS services_total,
			(SELECT COUNT(*) FROM cms_blog_posts) AS blog_total,
			(SELECT COUNT(*) FROM cms_blog_posts WHERE published=1) AS blog_published,
			(SELECT COUNT(*) FROM cms_banners WHERE is_active=1) AS banners_active");
	if ($res && ($row = $res->fetch_assoc())) {
		$sr_projects_total = (string)($row['projects_total'] ?? '0');
		$sr_products_total = (string)($row['products_total'] ?? '0');
		$sr_services_total = (string)($row['services_total'] ?? '0');
		$sr_blog_total = (string)($row['blog_total'] ?? '0');
		$sr_blog_published = (string)($row['blog_published'] ?? '0');
		$sr_banners_active = (string)($row['banners_active'] ?? '0');
	}
	if ($res) $res->free();

	$res = $sr_db->query("SELECT id, full_name, phone, email, status, created_at
		FROM cms_enquiries
		ORDER BY created_at DESC
		LIMIT 8");
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$sr_recent_enquiries[] = $row;
		}
		$res->free();
	}

	$res = $sr_db->query("SELECT DATE(created_at) AS d, COUNT(*) AS c
		FROM cms_enquiries
		WHERE created_at >= (CURDATE() - INTERVAL 13 DAY)
		GROUP BY DATE(created_at)
		ORDER BY DATE(created_at) ASC");
	$trendMap = [];
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$d = (string)($row['d'] ?? '');
			$trendMap[$d] = (int)($row['c'] ?? 0);
		}
		$res->free();
	}
	for ($i = 13; $i >= 0; $i--) {
		$d = date('Y-m-d', strtotime('-' . $i . ' days'));
		$sr_enquiries_trend_labels[] = date('M j', strtotime($d));
		$sr_enquiries_trend_values[] = (int)($trendMap[$d] ?? 0);
	}
}

$sr_company_email = sr_cms_setting_get('company_email', 'info@shivanjalirenewables.com');
$sr_company_phone = sr_cms_setting_get('company_phone1', '+91 8686 313 133');
$sr_company_city = sr_cms_setting_get('company_city', 'Nashik, Maharashtra');
$sr_dash_csrf = sr_admin_csrf_token();
?>
<?php include 'header.php'; ?>
<div class="page-body-wrapper">
	<?php include 'sidebar.php'; ?>
	<div class="page-body">
		<div class="container-fluid">
			<div class="page-title">
				<div class="row">
					<div class="col-sm-6 col-12">
						<h2>Dashboard</h2>
						<p class="mb-0 text-title-gray">Overview for Shivanjali Renewables operations.</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Dashboard</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row g-4">
				<div class="col-lg-4 col-md-6">
					<div class="card">
						<div class="card-body">
							<div class="d-flex align-items-start justify-content-between">
								<div>
									<h5 class="mb-1">New Enquiries</h5>
									<p class="mb-0 text-title-gray">Today</p>
								</div>
								<div class="badge rounded-pill bg-light-primary text-primary">Live</div>
							</div>
							<div class="mt-3 d-flex align-items-end justify-content-between">
								<div>
									<h2 class="mb-0"><?php echo htmlspecialchars($sr_enquiries_today, ENT_QUOTES, 'UTF-8'); ?></h2>
									<p class="mb-0 text-title-gray">Last 24 hours</p>
								</div>
								<a class="btn btn-primary" href="enquiries.php">View</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4 col-md-6">
					<div class="card">
						<div class="card-body">
							<div class="d-flex align-items-start justify-content-between">
								<div>
									<h5 class="mb-1">Open Enquiries</h5>
									<p class="mb-0 text-title-gray">Needs follow-up</p>
								</div>
								<div class="badge rounded-pill bg-light-warning text-warning">Action</div>
							</div>
							<div class="mt-3 d-flex align-items-end justify-content-between">
								<div>
									<h2 class="mb-0"><?php echo htmlspecialchars($sr_enquiries_open, ENT_QUOTES, 'UTF-8'); ?></h2>
									<p class="mb-0 text-title-gray">New + in progress</p>
								</div>
								<a class="btn btn-primary" href="enquiries.php">Manage</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4 col-md-12">
					<div class="card">
						<div class="card-body">
							<div class="d-flex align-items-start justify-content-between">
								<div>
									<h5 class="mb-1">Published Content</h5>
									<p class="mb-0 text-title-gray">Blog posts</p>
								</div>
								<div class="badge rounded-pill bg-light-warning text-warning">Content</div>
							</div>
							<div class="mt-3 d-flex align-items-end justify-content-between">
								<div>
									<h2 class="mb-0"><?php echo htmlspecialchars($sr_blog_published, ENT_QUOTES, 'UTF-8'); ?></h2>
									<p class="mb-0 text-title-gray">Published posts</p>
								</div>
								<a class="btn btn-primary" href="blog-posts.php">View</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-3 col-md-6">
					<div class="card">
						<div class="card-body">
							<div class="d-flex align-items-start justify-content-between">
								<div>
									<h5 class="mb-1">Active Banners</h5>
									<p class="mb-0 text-title-gray">Homepage slider</p>
								</div>
								<div class="badge rounded-pill bg-light-success text-success">Website</div>
							</div>
							<div class="mt-3 d-flex align-items-end justify-content-between">
								<div>
									<h2 class="mb-0"><?php echo htmlspecialchars($sr_banners_active, ENT_QUOTES, 'UTF-8'); ?></h2>
									<p class="mb-0 text-title-gray">Visible slides</p>
								</div>
								<a class="btn btn-primary" href="banners.php">Manage</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-3 col-md-6">
					<div class="card">
						<div class="card-body">
							<div class="d-flex align-items-start justify-content-between">
								<div>
									<h5 class="mb-1">Products</h5>
									<p class="mb-0 text-title-gray">Total items</p>
								</div>
								<div class="badge rounded-pill bg-light-primary text-primary">Catalog</div>
							</div>
							<div class="mt-3 d-flex align-items-end justify-content-between">
								<div>
									<h2 class="mb-0"><?php echo htmlspecialchars($sr_products_total, ENT_QUOTES, 'UTF-8'); ?></h2>
									<p class="mb-0 text-title-gray">In CMS</p>
								</div>
								<a class="btn btn-primary" href="products.php">Open</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-3 col-md-6">
					<div class="card">
						<div class="card-body">
							<div class="d-flex align-items-start justify-content-between">
								<div>
									<h5 class="mb-1">Services</h5>
									<p class="mb-0 text-title-gray">Total items</p>
								</div>
								<div class="badge rounded-pill bg-light-primary text-primary">Offerings</div>
							</div>
							<div class="mt-3 d-flex align-items-end justify-content-between">
								<div>
									<h2 class="mb-0"><?php echo htmlspecialchars($sr_services_total, ENT_QUOTES, 'UTF-8'); ?></h2>
									<p class="mb-0 text-title-gray">In CMS</p>
								</div>
								<a class="btn btn-primary" href="services.php">Open</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-3 col-md-6">
					<div class="card">
						<div class="card-body">
							<div class="d-flex align-items-start justify-content-between">
								<div>
									<h5 class="mb-1">Projects</h5>
									<p class="mb-0 text-title-gray">Total items</p>
								</div>
								<div class="badge rounded-pill bg-light-success text-success">Portfolio</div>
							</div>
							<div class="mt-3 d-flex align-items-end justify-content-between">
								<div>
									<h2 class="mb-0"><?php echo htmlspecialchars($sr_projects_total, ENT_QUOTES, 'UTF-8'); ?></h2>
									<p class="mb-0 text-title-gray">In CMS</p>
								</div>
								<a class="btn btn-primary" href="projects.php">Open</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-8">
					<div class="card">
						<div class="card-header">
							<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
								<div>
									<h4 class="mb-0">Enquiries Trend</h4>
									<div class="text-title-gray">Last 14 days • Total last 7 days: <?php echo htmlspecialchars($sr_enquiries_7d, ENT_QUOTES, 'UTF-8'); ?> • Last 30 days: <?php echo htmlspecialchars($sr_enquiries_30d, ENT_QUOTES, 'UTF-8'); ?></div>
								</div>
								<div class="d-flex gap-2 flex-wrap">
									<a class="btn btn-outline-primary" href="enquiries.php">Open Enquiries</a>
									<a class="btn btn-primary" href="pages.php?slug=home">Edit Home</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<canvas id="srEnquiriesTrend" height="90"></canvas>
						</div>
					</div>
				</div>

				<div class="col-xl-4">
					<div class="card">
						<div class="card-header">
							<h4 class="mb-0">Enquiry Status</h4>
							<div class="text-title-gray">Last 30 days</div>
						</div>
						<div class="card-body">
							<canvas id="srEnquiriesStatus" height="210"></canvas>
							<div class="mt-3 d-flex justify-content-between flex-wrap gap-2">
								<div class="text-title-gray">New: <span class="fw-bold"><?php echo (int)$sr_enquiries_new_30d; ?></span></div>
								<div class="text-title-gray">In progress: <span class="fw-bold"><?php echo (int)$sr_enquiries_in_progress_30d; ?></span></div>
								<div class="text-title-gray">Closed: <span class="fw-bold"><?php echo (int)$sr_enquiries_closed_30d; ?></span></div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-7">
					<div class="card">
						<div class="card-header">
							<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
								<h4 class="mb-0">Recent Enquiries</h4>
								<a class="btn btn-outline-primary" href="enquiries.php">View All</a>
							</div>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-striped mb-0">
									<thead>
										<tr>
											<th>Name</th>
											<th>Phone</th>
											<th>Status</th>
											<th>Created</th>
											<th class="text-end">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php if (!$sr_recent_enquiries) { ?>
											<tr>
												<td colspan="5" class="text-center text-title-gray py-4">No enquiries yet.</td>
											</tr>
										<?php } ?>
										<?php foreach ($sr_recent_enquiries as $e) { ?>
											<?php
											$st = (string)($e['status'] ?? 'new');
											$badge = 'bg-light-primary text-primary';
											if ($st === 'in_progress') $badge = 'bg-light-warning text-warning';
											if ($st === 'closed') $badge = 'bg-light-success text-success';
											?>
											<tr>
												<td class="fw-bold"><?php echo htmlspecialchars((string)($e['full_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
												<td><?php echo htmlspecialchars((string)($e['phone'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
												<td><span class="badge rounded-pill <?php echo $badge; ?>"><?php echo htmlspecialchars($st, ENT_QUOTES, 'UTF-8'); ?></span></td>
												<td class="text-title-gray"><?php echo htmlspecialchars((string)($e['created_at'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
												<td class="text-end">
													<a class="btn btn-sm btn-primary" href="enquiries.php?action=view&id=<?php echo (int)($e['id'] ?? 0); ?>">Open</a>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-5">
					<div class="card">
						<div class="card-header">
							<h4 class="mb-0">Reports</h4>
							<div class="text-title-gray">Download client-ready CSV reports</div>
						</div>
						<div class="card-body">
							<form method="get" action="index.php" class="row g-3">
								<input type="hidden" name="report" value="enquiries_csv">
								<input type="hidden" name="csrf" value="<?php echo htmlspecialchars($sr_dash_csrf, ENT_QUOTES, 'UTF-8'); ?>">
								<div class="col-6">
									<label class="form-label">From</label>
									<input class="form-control" type="date" name="from" value="<?php echo htmlspecialchars(date('Y-m-d', strtotime('-30 days')), ENT_QUOTES, 'UTF-8'); ?>">
								</div>
								<div class="col-6">
									<label class="form-label">To</label>
									<input class="form-control" type="date" name="to" value="<?php echo htmlspecialchars(date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>">
								</div>
								<div class="col-12">
									<label class="form-label">Status</label>
									<select class="form-select" name="status">
										<option value="">All</option>
										<option value="new">New</option>
										<option value="in_progress">In progress</option>
										<option value="closed">Closed</option>
									</select>
								</div>
								<div class="col-12 d-flex gap-2 flex-wrap">
									<button class="btn btn-primary" type="submit"><i data-feather="download"></i><span class="ms-2">Download Enquiries CSV</span></button>
									<a class="btn btn-outline-primary" href="index.php?report=summary_csv&from=<?php echo rawurlencode(date('Y-m-d', strtotime('-30 days'))); ?>&to=<?php echo rawurlencode(date('Y-m-d')); ?>&csrf=<?php echo rawurlencode($sr_dash_csrf); ?>"><i data-feather="file-text"></i><span class="ms-2">Download Summary CSV</span></a>
								</div>
							</form>
							<div class="mt-3 p-3 rounded-3 border bg-light">
								<div class="fw-bold mb-1 text-dark">What’s inside</div>
								<div class="text-title-gray">Enquiries CSV includes full lead details. Summary CSV includes totals for enquiries, content, and banners.</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
								<h4 class="mb-0">Quick Actions</h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="../contact" target="_blank" rel="noopener">Open Contact Page</a>
									<a class="btn btn-outline-primary" href="../projects" target="_blank" rel="noopener">Open Projects Page</a>
									<a class="btn btn-outline-primary" href="../blog" target="_blank" rel="noopener">Open Blog Page</a>
									<a class="btn btn-primary" href="settings.php">Admin Settings</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="row g-3">
								<div class="col-lg-4">
									<div class="p-3 rounded-3 border bg-light">
										<div class="d-flex align-items-center gap-2 mb-2">
											<i data-feather="mail" class="text-primary"></i>
											<h6 class="mb-0 text-dark">Primary Email</h6>
										</div>
										<div class="fw-bold text-dark"><?php echo htmlspecialchars($sr_company_email, ENT_QUOTES, 'UTF-8'); ?></div>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="p-3 rounded-3 border bg-light">
										<div class="d-flex align-items-center gap-2 mb-2">
											<i data-feather="phone" class="text-primary"></i>
											<h6 class="mb-0 text-dark">Primary Phone</h6>
										</div>
										<div class="fw-bold text-dark"><?php echo htmlspecialchars($sr_company_phone, ENT_QUOTES, 'UTF-8'); ?></div>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="p-3 rounded-3 border bg-light">
										<div class="d-flex align-items-center gap-2 mb-2">
											<i data-feather="map-pin" class="text-primary"></i>
											<h6 class="mb-0 text-dark">Office</h6>
										</div>
										<div class="fw-bold text-dark"><?php echo htmlspecialchars($sr_company_city, ENT_QUOTES, 'UTF-8'); ?></div>	
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="./assets/js/chart/chartjs/chart.min.js"></script>
	<script>
		(function () {
			var ctxTrend = document.getElementById('srEnquiriesTrend');
			if (ctxTrend && window.Chart) {
				new Chart(ctxTrend.getContext('2d'), {
					type: 'line',
					data: {
						labels: <?php echo json_encode($sr_enquiries_trend_labels, JSON_UNESCAPED_SLASHES); ?>,
						datasets: [{
							label: 'Enquiries',
							data: <?php echo json_encode($sr_enquiries_trend_values, JSON_UNESCAPED_SLASHES); ?>,
							borderColor: 'rgba(16,168,84,1)',
							backgroundColor: 'rgba(16,168,84,.12)',
							pointRadius: 3,
							pointHoverRadius: 4,
							tension: 0.35,
							fill: true
						}]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						plugins: {
							legend: { display: false },
							tooltip: { mode: 'index', intersect: false }
						},
						scales: {
							x: { grid: { display: false } },
							y: { beginAtZero: true, ticks: { precision: 0 } }
						}
					}
				});
			}

			var ctxStatus = document.getElementById('srEnquiriesStatus');
			if (ctxStatus && window.Chart) {
				new Chart(ctxStatus.getContext('2d'), {
					type: 'doughnut',
					data: {
						labels: ['New', 'In progress', 'Closed'],
						datasets: [{
							data: [<?php echo (int)$sr_enquiries_new_30d; ?>, <?php echo (int)$sr_enquiries_in_progress_30d; ?>, <?php echo (int)$sr_enquiries_closed_30d; ?>],
							backgroundColor: ['rgba(0,123,255,.85)', 'rgba(255,193,7,.85)', 'rgba(16,168,84,.85)'],
							borderWidth: 0
						}]
					},
					options: {
						responsive: true,
						plugins: {
							legend: { position: 'bottom' }
						},
						cutout: '68%'
					}
				});
			}
		})();
	</script>
	<?php include 'footer.php'; ?>
