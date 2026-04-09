<?php
require_once __DIR__ . '/db.php';
sr_admin_require_login();
$sr_admin_user = sr_admin_current_user();
$sr_admin_username_safe = htmlspecialchars((string) ($sr_admin_user['username'] ?? 'admin'), ENT_QUOTES, 'UTF-8');
$sr_admin_name_safe = htmlspecialchars((string) (($sr_admin_user['full_name'] ?? '') !== '' ? $sr_admin_user['full_name'] : ($sr_admin_user['username'] ?? 'Administrator')), ENT_QUOTES, 'UTF-8');
$sr_admin_role_safe = htmlspecialchars('Administrator', ENT_QUOTES, 'UTF-8');
$sr_admin_profile_image = (string) ($sr_admin_user['profile_image'] ?? '');
$sr_admin_profile_image = trim($sr_admin_profile_image);
$sr_admin_profile_image_safe = './assets/images/profile.png';
if ($sr_admin_profile_image !== '' && preg_match('/^assets\/images\/[a-z0-9._-]+\.(png|jpe?g|webp)$/i', $sr_admin_profile_image) === 1) {
	$sr_admin_profile_image_safe = './' . $sr_admin_profile_image;
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
		.logo-wrapper .sr-admin-brand {
			display: flex;
			align-items: center;
			gap: 12px;
			text-decoration: none;
		}

		.logo-wrapper .sr-admin-brand-logo {
			width: 36px;
			height: 36px;
			border-radius: 12px;
			overflow: hidden;
			border: 1px solid rgba(10, 25, 38, .10);
			background: #fff;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.logo-wrapper .sr-admin-brand-logo img {
			max-width: 100%;
			max-height: 100%;
		}

		.logo-wrapper .sr-admin-brand-text {
			display: flex;
			flex-direction: column;
			gap: 2px;
		}

		.logo-wrapper .sr-admin-brand-title {
			font-weight: 900;
			letter-spacing: -.2px;
			font-size: 15px;
			line-height: 18px;
			color: rgba(10, 25, 38, .96);
		}

		.logo-wrapper .sr-admin-brand-sub {
			font-weight: 700;
			font-size: 12px;
			line-height: 16px;
			color: rgba(10, 25, 38, .66);
		}

		.sr-admin-top-actions {
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.sr-admin-top-actions a {
			display: inline-flex;
			align-items: center;
			gap: 10px;
			border-radius: 14px;
			padding: 10px 12px;
			text-decoration: none;
			font-weight: 800;
			color: rgba(10, 25, 38, .84);
			background: rgba(10, 25, 38, .04);
			border: 1px solid rgba(10, 25, 38, .08);
		}

		.sr-admin-top-actions a:hover {
			background: rgba(16, 168, 84, .10);
			border-color: rgba(16, 168, 84, .18);
			color: rgba(10, 25, 38, .95);
		}

		.sr-admin-top-actions svg {
			width: 18px;
			height: 18px;
		}

		.profile-nav .user-wrap {
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.profile-nav .user-img {
			width: 38px;
			height: 38px;
			border-radius: 14px;
			overflow: hidden;
			border: 1px solid rgba(10, 25, 38, .12);
		}

		.profile-nav .user-img img {
			width: 100%;
			height: 100%;
			object-fit: cover;
		}

		.profile-nav .user-content h6 {
			font-weight: 900;
			margin: 0;
		}

		.profile-nav .user-content p {
			margin: 0;
			font-weight: 700;
			color: rgba(10, 25, 38, .66);
		}

		.profile-nav .custom-menu .profile-body li {
			gap: 10px;
			align-items: center;
		}

		.profile-nav .custom-menu .profile-body li svg {
			width: 18px;
			height: 18px;
		}

		.page-main-header .nav-right {
			display: flex;
		}

		.page-main-header .header-right {
			display: flex;
			width: 100%;
			align-items: center;
			gap: 12px;
		}

		.page-main-header .header-right .profile-nav {
			margin-left: auto;
		}

		.tap-top svg {
			width: 20px;
			height: 20px;
		}
	</style>
</head>

<body>
	<div class="tap-top"><i data-feather="arrow-up"></i></div>
	<div class="page-wrapper compact-wrapper" id="pageWrapper">
		<header class="page-header row">
			<div class="logo-wrapper d-flex align-items-center col-auto">
				<a class="sr-admin-brand" href="index.php">
					<span class="sr-admin-brand-logo">
						<img src="../images/Shivanjali_Logo.jpg" alt="Shivanjali Renewables">
					</span>
					<span class="sr-admin-brand-text d-none d-sm-flex">
						<span class="sr-admin-brand-title">Shivanjali Admin</span>
						<span class="sr-admin-brand-sub">Renewables Console</span>
					</span>
				</a>
				<a class="close-btn toggle-sidebar ms-3" href="javascript:void(0)" aria-label="Toggle sidebar">
					<i data-feather="menu"></i>
				</a>
			</div>
			<div class="page-main-header col">
				<div class="d-flex justify-content-between align-items-center w-100">

					<!-- LEFT SIDE -->
					<div class="header-left">
						<a href="../" target="_blank" rel="noopener" class="btn btn-secondary">
							<i data-feather="globe"></i>
							<span>Visit Website</span>
						</a>
					</div>

					<!-- RIGHT SIDE -->
					<div class="nav-right">
						<ul class="header-right">

							<li class="profile-nav custom-dropdown">
								<div class="user-wrap">
									<div class="user-img">
										<img src="<?php echo htmlspecialchars($sr_admin_profile_image_safe, ENT_QUOTES, 'UTF-8'); ?>"
											alt="Admin" />
									</div>

									<div class="user-content">
										<h6><?php echo $sr_admin_name_safe; ?></h6>
										<p>
											<?php echo $sr_admin_role_safe; ?>
											<i data-feather="chevron-down"></i>
										</p>
									</div>
								</div>

								<div class="custom-menu overflow-hidden">
									<ul class="profile-body">
										<li class="d-flex">
											<i data-feather="settings"></i>
											<a class="ms-2" href="settings.php">Settings</a>
										</li>

										<li class="d-flex">
											<i data-feather="globe"></i>
											<a class="ms-2" href="../" target="_blank">View Website</a>
										</li>

										<li class="d-flex">
											<i data-feather="log-out"></i>
											<a class="ms-2" href="logout.php">Log Out</a>
										</li>
									</ul>
								</div>

							</li>

						</ul>
					</div>

				</div>
			</div>
		</header>