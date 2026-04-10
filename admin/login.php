<?php
require_once __DIR__ . '/db.php';

$next = isset($_GET['next']) ? sr_admin_safe_next((string) $_GET['next']) : 'index';
if (sr_admin_is_logged_in()) {
	header('Location: ' . $next);
	exit;
}

$error = '';
$lockedUntil = sr_admin_login_locked_until();
if ($lockedUntil > time()) {
	$seconds = $lockedUntil - time();
	$error = 'Too many attempts. Try again in ' . $seconds . ' seconds.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$postedNext = isset($_POST['next']) ? sr_admin_safe_next((string) $_POST['next']) : 'index';
	$next = $postedNext;

	$csrf = isset($_POST['csrf']) ? (string) $_POST['csrf'] : null;
	if (!sr_admin_verify_csrf($csrf)) {
		$error = 'Something went wrong. Please refresh and try again.';
	} else {
		$username = isset($_POST['username']) ? (string) $_POST['username'] : '';
		$password = isset($_POST['password']) ? (string) $_POST['password'] : '';

		if (sr_admin_attempt_login($username, $password)) {
			header('Location: ' . $next);
			exit;
		}

		$lockedUntil = sr_admin_login_locked_until();
		if ($lockedUntil > time()) {
			$seconds = $lockedUntil - time();
			$error = 'Too many attempts. Try again in ' . $seconds . ' seconds.';
		} else {
			$error = 'Invalid username or password.';
			usleep(650000);
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Admin • Shivanjali Renewables</title>
	<link rel="icon" href="./assets/images/favicon.png" type="image/x-icon" />
	<link rel="shortcut icon" href="./assets/images/favicon.png" type="image/x-icon" />
	<link rel="preconnect" href="https://fonts.googleapis.com/" />
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
	<link
		href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200;6..12,300;6..12,400;6..12,500;6..12,600;6..12,700;6..12,800;6..12,900;6..12,1000&display=swap"
		rel="stylesheet" />
	<link rel="stylesheet" href="./assets/css/vendors/bootstrap.css" />
	<link rel="stylesheet" href="./assets/css/vendors/scrollbar.css" />
	<link rel="stylesheet" href="./assets/css/iconly-icon.css" />
	<link rel="stylesheet" href="./assets/css/bulk-style.css" />
	<link rel="stylesheet" href="./assets/css/themify.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/fontawesome.min.css" />
	<link id="color" rel="stylesheet" href="./assets/css/color-1.css" media="screen" />
	<link rel="stylesheet" href="./assets/css/style.css" />
	<style>
		body {
			min-height: 100vh;
			background: radial-gradient(1200px 700px at 10% 10%, rgba(16, 168, 84, .18), transparent 62%),
				radial-gradient(1000px 650px at 90% 18%, rgba(31, 44, 115, .16), transparent 58%),
				linear-gradient(180deg, rgba(236, 246, 255, .88) 0%, rgba(255, 255, 255, 1) 55%, rgba(242, 250, 246, .97) 100%);
			position: relative;
			overflow-x: hidden;
		}

		/* background glow */
		body:before {
			content: "";
			position: fixed;
			top: -200px;
			left: -200px;
			width: 500px;
			height: 500px;
			background: radial-gradient(circle, rgba(16, 168, 84, .25), transparent 70%);
			filter: blur(40px);
			animation: float1 12s ease-in-out infinite alternate, pulse1 7s ease-in-out infinite;
			z-index: 0;
		}

		body:after {
			content: "";
			position: fixed;
			bottom: -200px;
			right: -200px;
			width: 500px;
			height: 500px;
			background: radial-gradient(circle, rgba(31, 44, 115, .20), transparent 70%);
			filter: blur(40px);
			animation: float2 14s ease-in-out infinite alternate, pulse2 8s ease-in-out infinite;
			z-index: 0;
		}

		@keyframes float1 {
			from {
				transform: translateY(0px)
			}

			to {
				transform: translateY(40px)
			}
		}

		@keyframes float2 {
			from {
				transform: translateY(0px)
			}

			to {
				transform: translateY(-40px)
			}
		}

		@keyframes pulse1 {
			0% { opacity: .55; }
			50% { opacity: .95; }
			100% { opacity: .55; }
		}

		@keyframes pulse2 {
			0% { opacity: .55; }
			50% { opacity: .9; }
			100% { opacity: .55; }
		}

		.sr-login-wrap {
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 40px 20px;
			position: relative;
			z-index: 1;
		}

		.sr-login-card {
			width: min(1280px, 100%);
			border-radius: 24px;
			overflow: hidden;
			box-shadow: 0 45px 140px rgba(10, 25, 38, .18);
			border: 1px solid rgba(10, 25, 38, .08);
			background: rgba(255, 255, 255, .95);
			backdrop-filter: blur(10px);
			transition: all .4s ease;
		}

		.sr-login-card:hover {
			transform: translateY(-4px);
			box-shadow: 0 50px 140px rgba(10, 25, 38, .20);
		}

		.sr-login-grid {
			display: grid;
			grid-template-columns: 1.2fr .8fr;
			min-height: 610px;
		}

		/* LEFT PANEL */
		.sr-login-left {
			position: relative;
			display: flex;
			flex-direction: column;
			justify-content: center;
			padding: 48px 46px;
			background: linear-gradient(180deg, rgba(16, 168, 84, .12) 0%, rgba(255, 255, 255, 1) 55%);
			min-height: 420px;
		}

		.sr-login-left:before {
			content: "";
			position: absolute;
			inset: -140px -140px auto auto;
			width: 360px;
			height: 360px;
			border-radius: 999px;
			background: radial-gradient(circle, rgba(16, 168, 84, .25), rgba(255, 255, 255, 0) 70%);
			filter: blur(2px);
			pointer-events: none;
		}

		.sr-login-left:after {
			content: "";
			position: absolute;
			inset: auto -140px -160px -140px;
			height: 380px;
			border-radius: 26px;
			background:
				radial-gradient(circle at 20% 20%, rgba(31, 44, 115, .14), rgba(255, 255, 255, 0) 58%),
				radial-gradient(circle at 70% 60%, rgba(16, 168, 84, .14), rgba(255, 255, 255, 0) 60%);
			pointer-events: none;
		}

		/* brand */
		.sr-login-brand {
			position: relative;
			display: flex;
			align-items: center;
			gap: 12px;
			margin-bottom: 22px;
		}

		.sr-login-logo {
			width: 48px;
			height: 48px;
			border-radius: 12px;
			overflow: hidden;
			border: 1px solid rgba(10, 25, 38, .10);
			background: #fff;
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 8px 20px rgba(0, 0, 0, .05);
		}

		.sr-login-logo img {
			max-width: 100%;
			max-height: 100%;
		}

		.sr-login-brand h1 {
			font-size: 20px;
			line-height: 24px;
			margin: 0;
			font-weight: 900;
			color: #0a2d12;
		}

		.sr-login-brand p {
			margin: 0;
			font-size: 13px;
			line-height: 20px;
			color: rgba(10, 25, 38, .74);
			font-weight: 600;
		}

		/* hero */
		.sr-login-hero {
			position: relative;
			margin-top: 26px;
		}

		.sr-login-hero h2 {
			font-size: 32px;
			line-height: 40px;
			margin: 0 0 12px;
			font-weight: 900;
			letter-spacing: -.4px;
			color: rgba(10, 25, 38, .96);
		}

		.sr-login-hero p {
			margin: 0;
			font-size: 15px;
			line-height: 26px;
			color: rgba(10, 25, 38, .76);
			max-width: 46ch;
		}

		/* badges */
		.sr-login-badges {
			position: relative;
			margin-top: 20px;
			display: flex;
			flex-wrap: wrap;
			gap: 12px;
		}

		.sr-login-badge {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			border-radius: 999px;
			padding: 10px 14px;
			background: rgba(255, 255, 255, .95);
			border: 1px solid rgba(10, 25, 38, .10);
			box-shadow: 0 14px 34px rgba(10, 25, 38, .08);
			font-weight: 800;
			font-size: 12px;
			color: rgba(10, 25, 38, .86);
			transition: .3s;
		}

		.sr-login-badge:hover {
			transform: translateY(-2px);
			box-shadow: 0 20px 40px rgba(10, 25, 38, .12);
		}

		.sr-login-badge svg {
			width: 18px;
			height: 18px;
			color: rgba(16, 168, 84, .95);
		}

		/* right side */
		.sr-login-right {
			padding: 50px 46px;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}

		.sr-login-title {
			font-size: 26px;
			line-height: 32px;
			margin: 0 0 6px;
			font-weight: 900;
			letter-spacing: -.2px;
		}

		.sr-login-sub {
			margin: 0 0 18px;
			font-size: 14px;
			line-height: 22px;
			color: rgba(10, 25, 38, .74);
			font-weight: 600;
		}

		/* alert */
		.sr-login-alert {
			border-radius: 14px;
			padding: 12px 14px;
			background: rgba(220, 53, 69, .10);
			border: 1px solid rgba(220, 53, 69, .18);
			color: rgba(220, 53, 69, .95);
			font-weight: 700;
			margin-bottom: 14px;
			animation: fadeIn .4s ease;
		}

		@keyframes fadeIn {
			from {
				opacity: 0;
				transform: translateY(-5px)
			}

			to {
				opacity: 1
			}
		}

		/* form */
		.sr-login-form .form-label {
			font-weight: 800;
			color: rgba(10, 25, 38, .88);
			font-size: 13px;
		}

		.sr-login-form .form-control {
			border-radius: 14px;
			padding: 14px 16px;
			border: 1px solid rgba(10, 25, 38, .14);
			transition: .25s;
		}

		.sr-login-form .form-control:focus {
			border-color: rgba(16, 168, 84, .45);
			box-shadow: 0 0 0 .2rem rgba(16, 168, 84, .12);
			transform: translateY(-1px);
		}

		/* buttons */
		.sr-login-actions {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 12px;
			margin-top: 16px;
		}

		.sr-login-btn {
			border-radius: 14px;
			padding: 14px 22px;
			font-weight: 900;
			letter-spacing: .2px;
			transition: .3s;
		}

		.sr-login-btn:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 20px rgba(0, 0, 0, .15);
		}

		.sr-login-footer {
			margin-top: 18px;
			font-size: 12px;
			line-height: 20px;
			color: rgba(10, 25, 38, .62);
			font-weight: 600;
		}

		.sr-login-footer a {
			color: rgba(16, 168, 84, .95);
			text-decoration: none;
			font-weight: 900;
		}

		.sr-login-footer a:hover {
			text-decoration: underline;
		}

		/* responsive */
		@media (max-width: 991.98px) {
			.sr-login-grid {
				grid-template-columns: 1fr;
			}

			.sr-login-left {
				justify-content: flex-start;
				min-height: auto;
			}
		}

		@media (max-width: 575.98px) {
			.sr-login-left,
			.sr-login-right {
				padding: 34px 22px;
			}
			.sr-login-hero h2 {
				font-size: 28px;
				line-height: 36px;
			}
		}
	</style>
</head>

<body>
	<div class="sr-login-wrap">
		<div class="sr-login-card">
			<div class="sr-login-grid">
				<div class="sr-login-left">
					<div class="sr-login-brand">
						<div class="sr-login-logo">
							<img src="../images/Shivanjali_Logo.jpg" alt="Shivanjali Renewables">
						</div>
						<div>
							<h1>Shivanjali Renewables</h1>
							<p>Admin Console</p>
						</div>
					</div>
					<div class="sr-login-hero">
						<h2>Manage leads, projects, and content.</h2>
						<p>Secure admin access for internal operations. Use your credentials to continue to the
							dashboard.</p>
					</div>
					<div class="sr-login-badges">
						<div class="sr-login-badge"><i data-feather="shield"></i> Secure sessions</div>
						<div class="sr-login-badge"><i data-feather="briefcase"></i> Business tools</div>
						<div class="sr-login-badge"><i data-feather="bar-chart-2"></i> Insights</div>
					</div>
				</div>
				<div class="sr-login-right">
					<h3 class="sr-login-title">Sign in</h3>
					<p class="sr-login-sub mb-0">Enter your admin username and password.</p>
					<?php if ($error !== '') { ?>
						<div class="sr-login-alert mt-3"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
					<?php } ?>
					<form class="sr-login-form mt-3" method="post"
						action="login.php<?php echo $next ? ('?next=' . rawurlencode($next)) : ''; ?>">
						<input type="hidden" name="csrf"
							value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
						<input type="hidden" name="next"
							value="<?php echo htmlspecialchars($next, ENT_QUOTES, 'UTF-8'); ?>">
						<div class="mb-3">
							<label class="form-label" for="srAdminUsername">Username</label>
							<input class="form-control" id="srAdminUsername" type="text" name="username"
								autocomplete="username" required
								value="<?php echo htmlspecialchars((string) ($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="mb-2">
							<label class="form-label" for="srAdminPassword">Password</label>
							<input class="form-control" id="srAdminPassword" type="password" name="password"
								autocomplete="current-password" required>
						</div>
						<div class="sr-login-actions">
							<a href="../" class="text-decoration-none fw-bold">← Back to website</a>
							<button type="submit" class="btn btn-primary sr-login-btn">Login</button>
						</div>
					</form>
					<!-- <div class="sr-login-footer">
						<span><a href="reset.php" class="text-decoration-none fw-bold">Reset password</a></span>
					</div> -->
				</div>
			</div>
		</div>
	</div>
	<script src="./assets/js/vendors/jquery/jquery.min.js"></script>
	<script src="./assets/js/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="./assets/js/vendors/feather-icon/feather.min.js"></script>
	<script>
		if (window.feather) {
			window.feather.replace();
		}
	</script>
</body>

</html>
