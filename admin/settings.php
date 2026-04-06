<?php
require_once __DIR__ . '/db.php';
$sr_user = sr_admin_current_user();
$sr_username = htmlspecialchars((string)($sr_user['username'] ?? ''), ENT_QUOTES, 'UTF-8');

$hashOut = '';
$hashErr = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$csrf = isset($_POST['csrf']) ? (string)$_POST['csrf'] : null;
	if (!sr_admin_verify_csrf($csrf)) {
		$hashErr = 'Something went wrong. Please refresh and try again.';
	} else {
		$plain = isset($_POST['new_password']) ? (string)$_POST['new_password'] : '';
		$plain = trim($plain);
		if ($plain === '' || strlen($plain) < 10) {
			$hashErr = 'Password should be at least 10 characters.';
		} else {
			$hashOut = password_hash($plain, PASSWORD_DEFAULT);
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
						<h2>Settings</h2>
						<p class="mb-0 text-title-gray">Security and admin configuration.</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Settings</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row g-4">
				<div class="col-lg-6">
					<div class="card">
						<div class="card-header">
							<h4 class="mb-0">Admin Account</h4>
						</div>
						<div class="card-body">
							<div class="mb-2"><span class="text-title-gray">Logged in as:</span> <strong><?php echo $sr_username; ?></strong></div>
							<div class="text-title-gray">This admin uses server environment variables for production-ready credentials.</div>
							<div class="mt-3 p-3 rounded-3 border bg-light">
								<div class="fw-bold mb-1">Environment Variables</div>
								<div class="text-title-gray">SR_ADMIN_USERNAME</div>
								<div class="text-title-gray">SR_ADMIN_PASSWORD_HASH</div>
							</div>
							<div class="mt-3 alert alert-warning mb-0" role="alert">
								<strong>Important:</strong> Do not keep default credentials on production.
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="card">
						<div class="card-header">
							<h4 class="mb-0">Password Hash Generator</h4>
						</div>
						<div class="card-body">
							<?php if ($hashErr !== '') { ?>
								<div class="alert alert-danger"><?php echo htmlspecialchars($hashErr, ENT_QUOTES, 'UTF-8'); ?></div>
							<?php } ?>
							<form method="post" action="settings.php">
								<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
								<div class="mb-3">
									<label class="form-label" for="srNewPassword">New password</label>
									<input class="form-control" id="srNewPassword" name="new_password" type="password" autocomplete="new-password" placeholder="Enter new password (min 10 chars)" required>
								</div>
								<button type="submit" class="btn btn-primary">Generate Hash</button>
							</form>
							<?php if ($hashOut !== '') { ?>
								<div class="mt-3">
									<label class="form-label">Password hash (set as SR_ADMIN_PASSWORD_HASH)</label>
									<textarea class="form-control" rows="3" readonly><?php echo htmlspecialchars($hashOut, ENT_QUOTES, 'UTF-8'); ?></textarea>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include 'footer.php'; ?>

