<?php
require_once __DIR__ . '/db.php';
sr_admin_require_login();

$db = sr_cms_db_required();
sr_cms_migrate($db);

$msg = isset($_GET['msg']) ? (string)$_GET['msg'] : '';
$action = isset($_GET['action']) ? (string)$_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$statusFilter = isset($_GET['status']) ? (string)$_GET['status'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$csrf = isset($_POST['csrf']) ? (string)$_POST['csrf'] : null;
	if (!sr_admin_verify_csrf($csrf)) {
		header('Location: enquiries.php?msg=' . rawurlencode('Invalid session. Please try again.'));
		exit;
	}

	$op = isset($_POST['op']) ? (string)$_POST['op'] : '';
	if ($op === 'set_status') {
		$eid = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$newStatus = trim((string)($_POST['status'] ?? 'new'));
		if (!in_array($newStatus, ['new', 'in_progress', 'closed'], true)) {
			$newStatus = 'new';
		}
		if ($eid > 0) {
			$stmt = $db->prepare('UPDATE cms_enquiries SET status=? WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('si', $newStatus, $eid);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: enquiries.php?action=view&id=' . $eid . '&msg=' . rawurlencode('Updated.'));
		exit;
	}

	if ($op === 'delete') {
		$eid = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($eid > 0) {
			$stmt = $db->prepare('DELETE FROM cms_enquiries WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('i', $eid);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: enquiries.php?msg=' . rawurlencode('Deleted.'));
		exit;
	}
}

$enquiry = null;
if ($action === 'view' && $id > 0) {
	$stmt = $db->prepare('SELECT id, full_name, phone, email, city, customer_type, system_size, source, message, status, created_at FROM cms_enquiries WHERE id=? LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($rid, $rname, $rphone, $remail, $rcity, $rtype, $rsize, $rsource, $rmsg, $rstatus, $rcreated);
		if ($stmt->fetch()) {
			$enquiry = [
				'id' => (int)$rid,
				'full_name' => (string)$rname,
				'phone' => (string)$rphone,
				'email' => (string)$remail,
				'city' => (string)$rcity,
				'customer_type' => (string)$rtype,
				'system_size' => (string)$rsize,
				'source' => (string)$rsource,
				'message' => (string)$rmsg,
				'status' => (string)$rstatus,
				'created_at' => (string)$rcreated,
			];
		}
		$stmt->close();
	}
	$action = $enquiry ? 'view' : 'list';
}

$enquiries = [];
if ($action === 'list') {
	if ($statusFilter !== '' && in_array($statusFilter, ['new', 'in_progress', 'closed'], true)) {
		$stmt = $db->prepare('SELECT id, full_name, phone, email, city, customer_type, system_size, status, created_at FROM cms_enquiries WHERE status=? ORDER BY created_at DESC LIMIT 300');
		if ($stmt) {
			$stmt->bind_param('s', $statusFilter);
			$stmt->execute();
			$res = $stmt->get_result();
			if ($res) {
				while ($row = $res->fetch_assoc()) {
					$enquiries[] = $row;
				}
			}
			$stmt->close();
		}
	} else {
		$res = $db->query('SELECT id, full_name, phone, email, city, customer_type, system_size, status, created_at FROM cms_enquiries ORDER BY created_at DESC LIMIT 300');
		if ($res) {
			while ($row = $res->fetch_assoc()) {
				$enquiries[] = $row;
			}
			$res->free();
		}
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
						<h2>Enquiries</h2>
						<p class="mb-0 text-title-gray">Track incoming leads from the contact form.</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Enquiries</li>
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
								<h4 class="mb-0"><?php echo $action === 'view' ? 'Enquiry Details' : 'Inbox'; ?></h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="../contact" target="_blank" rel="noopener">Open Contact Form</a>
									<?php if ($action === 'view') { ?>
										<a class="btn btn-primary" href="enquiries.php">Back to list</a>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="card-body">
							<?php if ($action === 'view' && $enquiry) { ?>
								<div class="row g-3">
									<div class="col-lg-4">
										<div class="p-3 rounded-3 border bg-light h-100">
											<div class="text-title-gray">Date</div>
											<div class="fw-bold"><?php echo htmlspecialchars($enquiry['created_at'], ENT_QUOTES, 'UTF-8'); ?></div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="p-3 rounded-3 border bg-light h-100">
											<div class="text-title-gray">Name</div>
											<div class="fw-bold"><?php echo htmlspecialchars($enquiry['full_name'], ENT_QUOTES, 'UTF-8'); ?></div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="p-3 rounded-3 border bg-light h-100">
											<div class="text-title-gray">Status</div>
											<div class="fw-bold text-uppercase"><?php echo htmlspecialchars($enquiry['status'], ENT_QUOTES, 'UTF-8'); ?></div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="p-3 rounded-3 border bg-light h-100">
											<div class="text-title-gray">Phone</div>
											<div class="fw-bold"><a href="tel:<?php echo htmlspecialchars($enquiry['phone'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($enquiry['phone'], ENT_QUOTES, 'UTF-8'); ?></a></div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="p-3 rounded-3 border bg-light h-100">
											<div class="text-title-gray">Email</div>
											<div class="fw-bold"><a href="mailto:<?php echo htmlspecialchars($enquiry['email'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($enquiry['email'], ENT_QUOTES, 'UTF-8'); ?></a></div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="p-3 rounded-3 border bg-light h-100">
											<div class="text-title-gray">City</div>
											<div class="fw-bold"><?php echo htmlspecialchars($enquiry['city'], ENT_QUOTES, 'UTF-8'); ?></div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="p-3 rounded-3 border bg-light h-100">
											<div class="text-title-gray">Customer Type</div>
											<div class="fw-bold"><?php echo htmlspecialchars($enquiry['customer_type'], ENT_QUOTES, 'UTF-8'); ?></div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="p-3 rounded-3 border bg-light h-100">
											<div class="text-title-gray">System Size</div>
											<div class="fw-bold"><?php echo htmlspecialchars($enquiry['system_size'], ENT_QUOTES, 'UTF-8'); ?></div>
										</div>
									</div>
									<div class="col-12">
										<div class="p-3 rounded-3 border bg-light">
											<div class="text-title-gray">Source</div>
											<div class="fw-bold"><?php echo htmlspecialchars($enquiry['source'], ENT_QUOTES, 'UTF-8'); ?></div>
										</div>
									</div>
									<div class="col-12">
										<div class="p-3 rounded-3 border bg-light">
											<div class="text-title-gray mb-1">Message</div>
											<div class="fw-bold" style="white-space: pre-wrap;"><?php echo htmlspecialchars($enquiry['message'] !== '' ? $enquiry['message'] : '(none)', ENT_QUOTES, 'UTF-8'); ?></div>
										</div>
									</div>
									<div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-2">
										<form method="post" action="enquiries.php?action=view&id=<?php echo (int)$enquiry['id']; ?>" class="d-flex gap-2 flex-wrap align-items-center">
											<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
											<input type="hidden" name="op" value="set_status">
											<input type="hidden" name="id" value="<?php echo (int)$enquiry['id']; ?>">
											<select class="form-select" name="status" style="min-width: 200px;">
												<option value="new" <?php echo $enquiry['status'] === 'new' ? 'selected' : ''; ?>>New</option>
												<option value="in_progress" <?php echo $enquiry['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
												<option value="closed" <?php echo $enquiry['status'] === 'closed' ? 'selected' : ''; ?>>Closed</option>
											</select>
											<button type="submit" class="btn btn-primary">Update Status</button>
										</form>
										<form method="post" action="enquiries.php" onsubmit="return confirm('Delete this enquiry?');">
											<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
											<input type="hidden" name="op" value="delete">
											<input type="hidden" name="id" value="<?php echo (int)$enquiry['id']; ?>">
											<button type="submit" class="btn btn-outline-danger">Delete</button>
										</form>
									</div>
								</div>
							<?php } else { ?>
								<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
									<div class="d-flex gap-2 flex-wrap">
										<a class="btn btn-outline-primary <?php echo $statusFilter === '' ? 'active' : ''; ?>" href="enquiries.php">All</a>
										<a class="btn btn-outline-primary <?php echo $statusFilter === 'new' ? 'active' : ''; ?>" href="enquiries.php?status=new">New</a>
										<a class="btn btn-outline-primary <?php echo $statusFilter === 'in_progress' ? 'active' : ''; ?>" href="enquiries.php?status=in_progress">In Progress</a>
										<a class="btn btn-outline-primary <?php echo $statusFilter === 'closed' ? 'active' : ''; ?>" href="enquiries.php?status=closed">Closed</a>
									</div>
								</div>

								<div class="table-responsive">
									<table class="table table-striped mb-0">
										<thead>
											<tr>
												<th>Date</th>
												<th>Name</th>
												<th>Phone</th>
												<th>Email</th>
												<th>City</th>
												<th>Type</th>
												<th>System Size</th>
												<th>Status</th>
												<th class="text-end">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!$enquiries) { ?>
												<tr>
													<td colspan="9" class="text-center text-title-gray py-4">No enquiries yet.</td>
												</tr>
											<?php } ?>
											<?php foreach ($enquiries as $e) { ?>
												<tr>
													<td class="text-title-gray"><?php echo htmlspecialchars((string)$e['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td class="fw-bold"><?php echo htmlspecialchars((string)$e['full_name'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td><a href="tel:<?php echo htmlspecialchars((string)$e['phone'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)$e['phone'], ENT_QUOTES, 'UTF-8'); ?></a></td>
													<td><a href="mailto:<?php echo htmlspecialchars((string)$e['email'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)$e['email'], ENT_QUOTES, 'UTF-8'); ?></a></td>
													<td><?php echo htmlspecialchars((string)$e['city'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td><?php echo htmlspecialchars((string)$e['customer_type'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td><?php echo htmlspecialchars((string)$e['system_size'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td><span class="badge bg-<?php echo ((string)$e['status'] === 'new') ? 'primary' : (((string)$e['status'] === 'in_progress') ? 'warning text-dark' : 'secondary'); ?>"><?php echo htmlspecialchars((string)$e['status'], ENT_QUOTES, 'UTF-8'); ?></span></td>
													<td class="text-end"><a class="btn btn-sm btn-primary" href="enquiries.php?action=view&id=<?php echo (int)$e['id']; ?>">View</a></td>
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

