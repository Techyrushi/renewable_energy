<?php
require_once __DIR__ . '/db.php';

$next = isset($_GET['next']) ? sr_admin_safe_next((string)$_GET['next']) : 'index.php';
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
	$postedNext = isset($_POST['next']) ? sr_admin_safe_next((string)$_POST['next']) : 'index.php';
	$next = $postedNext;

	$csrf = isset($_POST['csrf']) ? (string)$_POST['csrf'] : null;
	if (!sr_admin_verify_csrf($csrf)) {
		$error = 'Something went wrong. Please refresh and try again.';
	} else {
		$username = isset($_POST['username']) ? (string)$_POST['username'] : '';
		$password = isset($_POST['password']) ? (string)$_POST['password'] : '';

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
	<title>Admin Login • Shivanjali Renewables</title>
	<link rel="icon" href="./assets/images/favicon.png" type="image/x-icon" />
	<link rel="shortcut icon" href="./assets/images/favicon.png" type="image/x-icon" />
	<link rel="preconnect" href="https://fonts.googleapis.com/" />
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
	<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200;6..12,300;6..12,400;6..12,500;6..12,600;6..12,700;6..12,800;6..12,900;6..12,1000&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="./assets/css/vendors/bootstrap.css" />
	<link rel="stylesheet" href="./assets/css/vendors/scrollbar.css" />
	<link rel="stylesheet" href="./assets/css/iconly-icon.css" />
	<link rel="stylesheet" href="./assets/css/bulk-style.css" />
	<link rel="stylesheet" href="./assets/css/themify.css" />
	<link rel="stylesheet" href="./assets/css/fontawesome-min.css" />
	<link id="color" rel="stylesheet" href="./assets/css/color-1.css" media="screen" />
	<link rel="stylesheet" href="./assets/css/style.css" />
	<style>
		body{min-height:100vh;background:linear-gradient(180deg, rgba(236,246,255,.85) 0%, rgba(255,255,255,1) 55%, rgba(242,250,246,.95) 100%);}
		.sr-login-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:28px 14px;}
		.sr-login-card{width:min(980px, 100%);border-radius:22px;overflow:hidden;box-shadow:0 30px 90px rgba(10,25,38,.16);border:1px solid rgba(10,25,38,.08);background:#fff;}
		.sr-login-grid{display:grid;grid-template-columns:1.05fr .95fr;}
		.sr-login-left{position:relative;padding:34px 32px;background:linear-gradient(180deg, rgba(16,168,84,.10) 0%, rgba(255,255,255,1) 55%);min-height:420px;}
		.sr-login-left:before{content:"";position:absolute;inset:-140px -140px auto auto;width:360px;height:360px;border-radius:999px;background:radial-gradient(circle, rgba(16,168,84,.25), rgba(255,255,255,0) 70%);filter:blur(2px);pointer-events:none;}
		.sr-login-left:after{content:"";position:absolute;inset:auto -140px -160px -140px;height:380px;border-radius:26px;background:radial-gradient(circle at 20% 20%, rgba(31,44,115,.14), rgba(255,255,255,0) 58%), radial-gradient(circle at 70% 60%, rgba(16,168,84,.14), rgba(255,255,255,0) 60%);pointer-events:none;}
		.sr-login-brand{position:relative;display:flex;align-items:center;gap:12px;margin-bottom:18px;}
		.sr-login-logo{width:44px;height:44px;border-radius:12px;overflow:hidden;border:1px solid rgba(10,25,38,.10);background:#fff;display:flex;align-items:center;justify-content:center;}
		.sr-login-logo img{max-width:100%;max-height:100%;}
		.sr-login-brand h1{font-size:18px;line-height:24px;margin:0;font-weight:900;color:#0a2d12;}
		.sr-login-brand p{margin:0;font-size:13px;line-height:20px;color:rgba(10,25,38,.74);font-weight:600;}
		.sr-login-hero{position:relative;margin-top:22px;}
		.sr-login-hero h2{font-size:30px;line-height:38px;margin:0 0 10px;font-weight:900;letter-spacing:-.4px;color:rgba(10,25,38,.96);}
		.sr-login-hero p{margin:0;font-size:15px;line-height:26px;color:rgba(10,25,38,.76);max-width:46ch;}
		.sr-login-badges{position:relative;margin-top:16px;display:flex;flex-wrap:wrap;gap:10px;}
		.sr-login-badge{display:inline-flex;align-items:center;gap:8px;border-radius:999px;padding:8px 12px;background:rgba(255,255,255,.9);border:1px solid rgba(10,25,38,.10);box-shadow:0 14px 34px rgba(10,25,38,.08);font-weight:800;font-size:12px;color:rgba(10,25,38,.86);}
		.sr-login-right{padding:34px 32px;display:flex;flex-direction:column;justify-content:center;}
		.sr-login-title{font-size:22px;line-height:30px;margin:0 0 6px;font-weight:900;letter-spacing:-.2px;}
		.sr-login-sub{margin:0 0 18px;font-size:14px;line-height:22px;color:rgba(10,25,38,.74);font-weight:600;}
		.sr-login-alert{border-radius:14px;padding:12px 14px;background:rgba(220,53,69,.10);border:1px solid rgba(220,53,69,.18);color:rgba(220,53,69,.95);font-weight:700;margin-bottom:14px;}
		.sr-login-form .form-label{font-weight:800;color:rgba(10,25,38,.88);font-size:13px;}
		.sr-login-form .form-control{border-radius:14px;padding:12px 14px;border:1px solid rgba(10,25,38,.14);}
		.sr-login-form .form-control:focus{border-color:rgba(16,168,84,.45);box-shadow:0 0 0 .2rem rgba(16,168,84,.12);}
		.sr-login-actions{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:14px;}
		.sr-login-btn{border-radius:14px;padding:12px 16px;font-weight:900;letter-spacing:.2px;}
		.sr-login-footer{margin-top:18px;font-size:12px;line-height:20px;color:rgba(10,25,38,.62);font-weight:600;}
		.sr-login-footer a{color:rgba(16,168,84,.95);text-decoration:none;font-weight:900;}
		.sr-login-footer a:hover{text-decoration:underline;}
		@media (max-width: 991.98px){.sr-login-grid{grid-template-columns:1fr;}.sr-login-left{min-height:auto;}}
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
						<p>Secure admin access for internal operations. Use your credentials to continue to the dashboard.</p>
					</div>
					<div class="sr-login-badges">
						<div class="sr-login-badge"><i class="iconly-Shield-Done icli"></i> Secure sessions</div>
						<div class="sr-login-badge"><i class="iconly-Work icli"></i> Business tools</div>
						<div class="sr-login-badge"><i class="iconly-Chart icli"></i> Insights</div>
					</div>
				</div>
				<div class="sr-login-right">
					<h3 class="sr-login-title">Sign in</h3>
					<p class="sr-login-sub mb-0">Enter your admin username and password.</p>
					<?php if ($error !== '') { ?>
						<div class="sr-login-alert mt-3"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
					<?php } ?>
					<form class="sr-login-form mt-3" method="post" action="login.php<?php echo $next ? ('?next=' . rawurlencode($next)) : ''; ?>">
						<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
						<input type="hidden" name="next" value="<?php echo htmlspecialchars($next, ENT_QUOTES, 'UTF-8'); ?>">
						<div class="mb-3">
							<label class="form-label" for="srAdminUsername">Username</label>
							<input class="form-control" id="srAdminUsername" type="text" name="username" autocomplete="username" required value="<?php echo htmlspecialchars((string)($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="mb-2">
							<label class="form-label" for="srAdminPassword">Password</label>
							<input class="form-control" id="srAdminPassword" type="password" name="password" autocomplete="current-password" required>
						</div>
						<div class="sr-login-actions">
							<a href="../" class="text-decoration-none fw-bold">← Back to website</a>
							<button type="submit" class="btn btn-primary sr-login-btn">Login</button>
						</div>
					</form>
					<div class="sr-login-footer">
						<span>Need access? Contact the website administrator.</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="./assets/js/vendors/jquery/jquery.min.js"></script>
	<script src="./assets/js/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

