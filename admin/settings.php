<?php
require_once __DIR__ . '/db.php';
$db = sr_cms_db_try();
if ($db instanceof mysqli) {
	sr_cms_migrate($db);
}
$sr_user = sr_admin_current_user();
$sr_username = htmlspecialchars((string)($sr_user['username'] ?? ''), ENT_QUOTES, 'UTF-8');
$sr_user_id = isset($sr_user['user_id']) ? (int)$sr_user['user_id'] : 0;
$sr_has_db_user = ($db instanceof mysqli) && $sr_user_id > 0;

$hashOut = '';
$hashErr = '';
$settingsMsg = '';
$settingsErr = '';
$profileMsg = '';
$profileErr = '';
$passwordMsg = '';
$passwordErr = '';

$settingsKeys = [
	'site_logo',
	'site_favicon',
	'company_name',
	'company_email',
	'company_phone',
	'company_phone_tel',
	'company_phone1',
	'company_phone1_tel',
	'company_phone2',
	'company_phone2_tel',
	'company_phone3',
	'company_phone3_tel',
	'company_address',
	'company_map_url',
	'company_map_label',
	'company_hours',
	'company_whatsapp_tel',
	'social_facebook',
	'social_instagram',
	'social_youtube',
	'home_kicker',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$op = isset($_POST['op']) ? (string)$_POST['op'] : 'hash';
	$csrf = isset($_POST['csrf']) ? (string)$_POST['csrf'] : null;
	if (!sr_admin_verify_csrf($csrf)) {
		$hashErr = 'Something went wrong. Please refresh and try again.';
		$settingsErr = $hashErr;
		$profileErr = $hashErr;
		$passwordErr = $hashErr;
	} else {
		if ($op === 'hash') {
			$plain = isset($_POST['new_password']) ? (string)$_POST['new_password'] : '';
			$plain = trim($plain);
			if ($plain === '' || strlen($plain) < 10) {
				$hashErr = 'Password should be at least 10 characters.';
			} else {
				$hashOut = password_hash($plain, PASSWORD_DEFAULT);
			}
		}

		if ($op === 'save_settings') {
			if (!$db instanceof mysqli) {
				$settingsErr = 'Database connection not available.';
			} else {
				$prevLogo = sr_cms_setting_get('site_logo', '');
				$prevFavicon = sr_cms_setting_get('site_favicon', '');

				if (isset($_FILES['site_logo_file']) && is_array($_FILES['site_logo_file'])) {
					$f = $_FILES['site_logo_file'];
					$err = isset($f['error']) ? (int)$f['error'] : UPLOAD_ERR_NO_FILE;
					if ($err !== UPLOAD_ERR_NO_FILE) {
						if ($err !== UPLOAD_ERR_OK) {
							$settingsErr = 'Unable to upload logo.';
						} else {
							$tmp = (string)($f['tmp_name'] ?? '');
							$size = (int)($f['size'] ?? 0);
							if ($size <= 0 || $size > 2_500_000) {
								$settingsErr = 'Logo must be under 2.5MB.';
							} else {
								$info = @getimagesize($tmp);
								$mime = is_array($info) ? (string)($info['mime'] ?? '') : '';
								$ext = '';
								if ($mime === 'image/jpeg') {
									$ext = 'jpg';
								} elseif ($mime === 'image/png') {
									$ext = 'png';
								} elseif ($mime === 'image/webp') {
									$ext = 'webp';
								}
								if ($ext === '') {
									$settingsErr = 'Logo must be JPG, PNG, or WEBP.';
								} else {
									$filename = 'site-logo-' . time() . '.' . $ext;
									$dest = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $filename;
									if (!@move_uploaded_file($tmp, $dest)) {
										$settingsErr = 'Unable to save logo.';
									} else {
										$_POST['site_logo'] = 'images/' . $filename;
										if (is_string($prevLogo) && preg_match('/^images\\/site-logo-\\d+\\.(png|jpe?g|webp)$/i', $prevLogo) === 1) {
											$oldAbs = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $prevLogo);
											if (is_file($oldAbs)) {
												@unlink($oldAbs);
											}
										}
									}
								}
							}
						}
					}
				}

				if ($settingsErr === '' && isset($_FILES['site_favicon_file']) && is_array($_FILES['site_favicon_file'])) {
					$f = $_FILES['site_favicon_file'];
					$err = isset($f['error']) ? (int)$f['error'] : UPLOAD_ERR_NO_FILE;
					if ($err !== UPLOAD_ERR_NO_FILE) {
						if ($err !== UPLOAD_ERR_OK) {
							$settingsErr = 'Unable to upload favicon.';
						} else {
							$tmp = (string)($f['tmp_name'] ?? '');
							$size = (int)($f['size'] ?? 0);
							if ($size <= 0 || $size > 800_000) {
								$settingsErr = 'Favicon must be under 800KB.';
							} else {
								$name = isset($f['name']) ? (string)$f['name'] : '';
								$lower = strtolower($name);
								$ext = '';
								$mime = '';
								$info = @getimagesize($tmp);
								if (is_array($info) && isset($info['mime'])) {
									$mime = (string)$info['mime'];
								}
								if ($mime === 'image/png') {
									$ext = 'png';
								} elseif ($mime === 'image/jpeg') {
									$ext = 'jpg';
								} elseif ($mime === 'image/webp') {
									$ext = 'webp';
								} elseif (str_ends_with($lower, '.ico')) {
									$ext = 'ico';
								}
								if ($ext === '') {
									$settingsErr = 'Favicon must be PNG, JPG, WEBP, or ICO.';
								} else {
									$filename = 'site-favicon-' . time() . '.' . $ext;
									$dest = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $filename;
									if (!@move_uploaded_file($tmp, $dest)) {
										$settingsErr = 'Unable to save favicon.';
									} else {
										$_POST['site_favicon'] = 'images/' . $filename;
										if (is_string($prevFavicon) && preg_match('/^images\\/site-favicon-\\d+\\.(png|jpe?g|webp|ico)$/i', $prevFavicon) === 1) {
											$oldAbs = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $prevFavicon);
											if (is_file($oldAbs)) {
												@unlink($oldAbs);
											}
										}
									}
								}
							}
						}
					}
				}

				$stmt = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
				if (!$stmt) {
					$settingsErr = 'Unable to save settings.';
				} else {
					foreach ($settingsKeys as $k) {
						$v = isset($_POST[$k]) ? trim((string)$_POST[$k]) : '';
						$stmt->bind_param('ss', $k, $v);
						$stmt->execute();
					}
					$stmt->close();
					$settingsMsg = 'Website settings saved.';
				}
			}
		}

		if ($op === 'update_profile') {
			if (!$sr_has_db_user) {
				$profileErr = 'Admin profile editing requires database-based admin users.';
			} else {
				$fullName = isset($_POST['full_name']) ? trim((string)$_POST['full_name']) : '';
				if ($fullName === '' || strlen($fullName) > 255) {
					$profileErr = 'Please enter a valid full name.';
				} else {
					$newImagePath = null;
					if (isset($_FILES['profile_image']) && is_array($_FILES['profile_image'])) {
						$f = $_FILES['profile_image'];
						$err = isset($f['error']) ? (int)$f['error'] : UPLOAD_ERR_NO_FILE;
						if ($err !== UPLOAD_ERR_NO_FILE) {
							if ($err !== UPLOAD_ERR_OK) {
								$profileErr = 'Unable to upload image. Please try again.';
							} else {
								$tmp = (string)($f['tmp_name'] ?? '');
								$size = (int)($f['size'] ?? 0);
								if ($size <= 0 || $size > 2_500_000) {
									$profileErr = 'Profile image must be under 2.5MB.';
								} else {
									$info = @getimagesize($tmp);
									$mime = is_array($info) ? (string)($info['mime'] ?? '') : '';
									$ext = '';
									if ($mime === 'image/jpeg') {
										$ext = 'jpg';
									} elseif ($mime === 'image/png') {
										$ext = 'png';
									} elseif ($mime === 'image/webp') {
										$ext = 'webp';
									}
									if ($ext === '') {
										$profileErr = 'Only JPG, PNG, or WEBP images are allowed.';
									} else {
										$filename = 'admin-user-' . $sr_user_id . '-' . time() . '.' . $ext;
										$relPath = 'assets/images/' . $filename;
										$dest = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $filename;
										if (!@move_uploaded_file($tmp, $dest)) {
											$profileErr = 'Unable to save the uploaded image.';
										} else {
											$newImagePath = $relPath;
										}
									}
								}
							}
						}
					}

					if ($profileErr === '') {
						$oldImage = '';
						$stmtOld = $db->prepare('SELECT profile_image FROM admin_users WHERE id=? LIMIT 1');
						if ($stmtOld) {
							$stmtOld->bind_param('i', $sr_user_id);
							$stmtOld->execute();
							$stmtOld->bind_result($oldImage);
							$stmtOld->fetch();
							$stmtOld->close();
						}

						if ($newImagePath === null) {
							$stmt = $db->prepare('UPDATE admin_users SET full_name=? WHERE id=?');
							if (!$stmt) {
								$profileErr = 'Unable to update profile.';
							} else {
								$stmt->bind_param('si', $fullName, $sr_user_id);
								$stmt->execute();
								$stmt->close();
							}
						} else {
							$stmt = $db->prepare('UPDATE admin_users SET full_name=?, profile_image=? WHERE id=?');
							if (!$stmt) {
								$profileErr = 'Unable to update profile.';
							} else {
								$stmt->bind_param('ssi', $fullName, $newImagePath, $sr_user_id);
								$stmt->execute();
								$stmt->close();
							}
						}

						if ($profileErr === '') {
							if ($newImagePath !== null && is_string($oldImage) && preg_match('/^assets\/images\/admin-user-\d+-\d+\.(png|jpe?g|webp)$/i', $oldImage) === 1) {
								$oldAbs = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $oldImage);
								if (is_file($oldAbs)) {
									@unlink($oldAbs);
								}
							}
							$_SESSION['sr_admin_auth']['full_name'] = $fullName;
							if ($newImagePath !== null) {
								$_SESSION['sr_admin_auth']['profile_image'] = $newImagePath;
							}
							$profileMsg = 'Admin profile updated.';
						}
					}
				}
			}
		}

		if ($op === 'remove_profile_image') {
			if (!$sr_has_db_user) {
				$profileErr = 'Admin profile editing requires database-based admin users.';
			} else {
				$oldImage = '';
				$stmtOld = $db->prepare('SELECT profile_image FROM admin_users WHERE id=? LIMIT 1');
				if ($stmtOld) {
					$stmtOld->bind_param('i', $sr_user_id);
					$stmtOld->execute();
					$stmtOld->bind_result($oldImage);
					$stmtOld->fetch();
					$stmtOld->close();
				}

				$stmt = $db->prepare('UPDATE admin_users SET profile_image=? WHERE id=?');
				if (!$stmt) {
					$profileErr = 'Unable to update profile.';
				} else {
					$empty = '';
					$stmt->bind_param('si', $empty, $sr_user_id);
					$stmt->execute();
					$stmt->close();
					$_SESSION['sr_admin_auth']['profile_image'] = '';
					if (is_string($oldImage) && preg_match('/^assets\/images\/admin-user-\d+-\d+\.(png|jpe?g|webp)$/i', $oldImage) === 1) {
						$oldAbs = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $oldImage);
						if (is_file($oldAbs)) {
							@unlink($oldAbs);
						}
					}
					$profileMsg = 'Profile image removed.';
				}
			}
		}

		if ($op === 'change_password') {
			if (!$sr_has_db_user) {
				$passwordErr = 'Password changes require database-based admin users.';
			} else {
				$current = isset($_POST['current_password']) ? (string)$_POST['current_password'] : '';
				$new = isset($_POST['new_password']) ? (string)$_POST['new_password'] : '';
				$confirm = isset($_POST['confirm_password']) ? (string)$_POST['confirm_password'] : '';
				if (strlen(trim($new)) < 10) {
					$passwordErr = 'New password should be at least 10 characters.';
				} elseif (!hash_equals($new, $confirm)) {
					$passwordErr = 'New password and confirmation do not match.';
				} else {
					$dbHash = '';
					$stmt = $db->prepare('SELECT password_hash FROM admin_users WHERE id=? LIMIT 1');
					if (!$stmt) {
						$passwordErr = 'Unable to update password.';
					} else {
						$stmt->bind_param('i', $sr_user_id);
						$stmt->execute();
						$stmt->bind_result($dbHash);
						$stmt->fetch();
						$stmt->close();
						if (!password_verify($current, (string)$dbHash)) {
							$passwordErr = 'Current password is incorrect.';
						} else {
							$newHash = password_hash($new, PASSWORD_DEFAULT);
							$uStmt = $db->prepare('UPDATE admin_users SET password_hash=? WHERE id=?');
							if (!$uStmt) {
								$passwordErr = 'Unable to update password.';
							} else {
								$uStmt->bind_param('si', $newHash, $sr_user_id);
								$uStmt->execute();
								$uStmt->close();
								$passwordMsg = 'Password updated.';
							}
						}
					}
				}
			}
		}
	}
}

$settings = [];
foreach ($settingsKeys as $k) {
	$settings[$k] = sr_cms_setting_get($k, '');
}

$adminProfile = [
	'full_name' => (string)($sr_user['full_name'] ?? ''),
	'profile_image' => (string)($sr_user['profile_image'] ?? ''),
];
if ($sr_has_db_user) {
	$stmt = $db->prepare('SELECT full_name, profile_image FROM admin_users WHERE id=? LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('i', $sr_user_id);
		$stmt->execute();
		$stmt->bind_result($fn, $pi);
		if ($stmt->fetch()) {
			$adminProfile['full_name'] = (string)$fn;
			$adminProfile['profile_image'] = (string)$pi;
		}
		$stmt->close();
	}
}
$adminProfileNameSafe = htmlspecialchars($adminProfile['full_name'] !== '' ? $adminProfile['full_name'] : $sr_username, ENT_QUOTES, 'UTF-8');
$adminProfileImage = trim($adminProfile['profile_image']);
$adminProfileImageSafe = './assets/images/profile.png';
if ($adminProfileImage !== '' && preg_match('/^assets\/images\/[a-z0-9._-]+\.(png|jpe?g|webp)$/i', $adminProfileImage) === 1) {
	$adminProfileImageSafe = './' . $adminProfileImage;
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
			<?php if ($settingsMsg !== '') { ?>
				<div class="alert alert-success"><?php echo htmlspecialchars($settingsMsg, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php } ?>
			<?php if ($settingsErr !== '') { ?>
				<div class="alert alert-danger"><?php echo htmlspecialchars($settingsErr, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php } ?>
			<?php if ($profileMsg !== '') { ?>
				<div class="alert alert-success"><?php echo htmlspecialchars($profileMsg, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php } ?>
			<?php if ($profileErr !== '') { ?>
				<div class="alert alert-danger"><?php echo htmlspecialchars($profileErr, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php } ?>
			<?php if ($passwordMsg !== '') { ?>
				<div class="alert alert-success"><?php echo htmlspecialchars($passwordMsg, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php } ?>
			<?php if ($passwordErr !== '') { ?>
				<div class="alert alert-danger"><?php echo htmlspecialchars($passwordErr, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php } ?>
			<div class="row g-4">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
								<h4 class="mb-0">Website Settings</h4>
								<a class="btn btn-outline-primary" href="../" target="_blank" rel="noopener">Open Website</a>
							</div>
						</div>
						<div class="card-body">
							<form method="post" action="settings.php" enctype="multipart/form-data">
								<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
								<input type="hidden" name="op" value="save_settings">
								<div class="row g-3">
									<div class="col-12">
										<div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between flex-wrap gap-3">
											<div class="d-flex align-items-center gap-3 flex-wrap">
												<img src="<?php echo htmlspecialchars($settings['site_logo'] !== '' ? $settings['site_logo'] : 'images/Shivanjali_Logo.jpg', ENT_QUOTES, 'UTF-8'); ?>" alt="Logo" style="width:64px;height:64px;object-fit:contain;background:#fff;border:1px solid rgba(10,25,38,.12);border-radius:16px;padding:8px;">
												<img src="<?php echo htmlspecialchars($settings['site_favicon'] !== '' ? $settings['site_favicon'] : 'images/fevicon.png', ENT_QUOTES, 'UTF-8'); ?>" alt="Favicon" style="width:44px;height:44px;object-fit:contain;background:#fff;border:1px solid rgba(10,25,38,.12);border-radius:14px;padding:6px;">
												<div>
													<div class="fw-bold">Branding</div>
													<div class="text-title-gray">Upload website logo and favicon.</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										<label class="form-label">Logo (upload)</label>
										<input class="form-control" type="file" name="site_logo_file" accept="image/jpeg,image/png,image/webp">
										<input type="hidden" name="site_logo" value="<?php echo htmlspecialchars($settings['site_logo'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-6">
										<label class="form-label">Favicon (upload)</label>
										<input class="form-control" type="file" name="site_favicon_file" accept="image/png,image/x-icon,image/vnd.microsoft.icon,image/jpeg,image/webp">
										<input type="hidden" name="site_favicon" value="<?php echo htmlspecialchars($settings['site_favicon'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Company name</label>
										<input class="form-control" name="company_name" value="<?php echo htmlspecialchars($settings['company_name'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Company email</label>
										<input class="form-control" name="company_email" value="<?php echo htmlspecialchars($settings['company_email'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Header phone (display)</label>
										<input class="form-control" name="company_phone" value="<?php echo htmlspecialchars($settings['company_phone'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Header phone (tel)</label>
										<input class="form-control" name="company_phone_tel" value="<?php echo htmlspecialchars($settings['company_phone_tel'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Map label</label>
										<input class="form-control" name="company_map_label" value="<?php echo htmlspecialchars($settings['company_map_label'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Map URL</label>
										<input class="form-control" name="company_map_url" value="<?php echo htmlspecialchars($settings['company_map_url'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-12">
										<label class="form-label">Address</label>
										<textarea class="form-control" name="company_address" rows="2"><?php echo htmlspecialchars($settings['company_address'], ENT_QUOTES, 'UTF-8'); ?></textarea>
									</div>
									<div class="col-lg-6">
										<label class="form-label">Working hours</label>
										<input class="form-control" name="company_hours" value="<?php echo htmlspecialchars($settings['company_hours'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-6">
										<label class="form-label">WhatsApp number (digits only)</label>
										<input class="form-control" name="company_whatsapp_tel" value="<?php echo htmlspecialchars($settings['company_whatsapp_tel'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Phone 1 (display)</label>
										<input class="form-control" name="company_phone1" value="<?php echo htmlspecialchars($settings['company_phone1'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Phone 1 (tel)</label>
										<input class="form-control" name="company_phone1_tel" value="<?php echo htmlspecialchars($settings['company_phone1_tel'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Phone 2 (display)</label>
										<input class="form-control" name="company_phone2" value="<?php echo htmlspecialchars($settings['company_phone2'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Phone 2 (tel)</label>
										<input class="form-control" name="company_phone2_tel" value="<?php echo htmlspecialchars($settings['company_phone2_tel'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Phone 3 (display)</label>
										<input class="form-control" name="company_phone3" value="<?php echo htmlspecialchars($settings['company_phone3'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Phone 3 (tel)</label>
										<input class="form-control" name="company_phone3_tel" value="<?php echo htmlspecialchars($settings['company_phone3_tel'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Facebook URL</label>
										<input class="form-control" name="social_facebook" value="<?php echo htmlspecialchars($settings['social_facebook'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">Instagram URL</label>
										<input class="form-control" name="social_instagram" value="<?php echo htmlspecialchars($settings['social_instagram'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">YouTube URL</label>
										<input class="form-control" name="social_youtube" value="<?php echo htmlspecialchars($settings['social_youtube'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-12">
										<label class="form-label">Home hero kicker (top line)</label>
										<input class="form-control" name="home_kicker" value="<?php echo htmlspecialchars($settings['home_kicker'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-12 d-flex justify-content-end">
										<button type="submit" class="btn btn-primary">Save Settings</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="card">
						<div class="card-header">
							<h4 class="mb-0">Admin Profile</h4>
						</div>
						<div class="card-body">
							<div class="d-flex align-items-center gap-3 flex-wrap">
								<img src="<?php echo htmlspecialchars($adminProfileImageSafe, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile" style="width:72px;height:72px;border-radius:18px;object-fit:cover;border:1px solid rgba(10,25,38,.12);">
								<div>
									<div class="text-title-gray">Username</div>
									<div class="fw-bold"><?php echo $sr_username; ?></div>
									<div class="text-title-gray mt-2">Full name</div>
									<div class="fw-bold"><?php echo $adminProfileNameSafe; ?></div>
								</div>
							</div>

							<?php if (!$sr_has_db_user) { ?>
								<div class="mt-3 alert alert-warning mb-0" role="alert">
									Admin profile editing is available when using database-based admin users.
								</div>
							<?php } ?>

							<form class="mt-3" method="post" action="settings.php" enctype="multipart/form-data">
								<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
								<input type="hidden" name="op" value="update_profile">
								<div class="mb-3">
									<label class="form-label" for="srFullName">Full name</label>
									<input class="form-control" id="srFullName" name="full_name" value="<?php echo htmlspecialchars($adminProfile['full_name'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $sr_has_db_user ? '' : 'disabled'; ?>>
								</div>
								<div class="mb-3">
									<label class="form-label" for="srProfileImage">Profile image (JPG/PNG/WEBP)</label>
									<input class="form-control" id="srProfileImage" name="profile_image" type="file" accept="image/jpeg,image/png,image/webp" <?php echo $sr_has_db_user ? '' : 'disabled'; ?>>
								</div>
								<div class="d-flex gap-2 flex-wrap">
									<button type="submit" class="btn btn-primary" <?php echo $sr_has_db_user ? '' : 'disabled'; ?>>Save Profile</button>
								</div>
							</form>

							<form class="mt-2" method="post" action="settings.php">
								<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
								<input type="hidden" name="op" value="remove_profile_image">
								<button type="submit" class="btn btn-outline-danger" <?php echo ($sr_has_db_user && $adminProfileImage !== '') ? '' : 'disabled'; ?>>Remove Profile Image</button>
							</form>
						</div>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="card">
						<div class="card-header">
							<h4 class="mb-0">Security</h4>
						</div>
						<div class="card-body">
							<form method="post" action="settings.php">
								<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
								<input type="hidden" name="op" value="change_password">
								<div class="mb-3">
									<label class="form-label" for="srCurrentPassword">Current password</label>
									<input class="form-control" id="srCurrentPassword" name="current_password" type="password" autocomplete="current-password" <?php echo $sr_has_db_user ? '' : 'disabled'; ?> required>
								</div>
								<div class="mb-3">
									<label class="form-label" for="srNewPasswordChange">New password</label>
									<input class="form-control" id="srNewPasswordChange" name="new_password" type="password" autocomplete="new-password" placeholder="Min 10 characters" <?php echo $sr_has_db_user ? '' : 'disabled'; ?> required>
								</div>
								<div class="mb-3">
									<label class="form-label" for="srConfirmPassword">Confirm new password</label>
									<input class="form-control" id="srConfirmPassword" name="confirm_password" type="password" autocomplete="new-password" <?php echo $sr_has_db_user ? '' : 'disabled'; ?> required>
								</div>
								<button type="submit" class="btn btn-primary" <?php echo $sr_has_db_user ? '' : 'disabled'; ?>>Update Password</button>
							</form>

							<hr class="my-4">

							<?php if ($hashErr !== '') { ?>
								<div class="alert alert-danger"><?php echo htmlspecialchars($hashErr, ENT_QUOTES, 'UTF-8'); ?></div>
							<?php } ?>
							<form method="post" action="settings.php">
								<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
								<input type="hidden" name="op" value="hash">
								<div class="mb-3">
									<label class="form-label" for="srNewPassword">Password hash tool</label>
									<input class="form-control" id="srNewPassword" name="new_password" type="password" autocomplete="new-password" placeholder="Enter a password (min 10 chars)" required>
								</div>
								<button type="submit" class="btn btn-outline-primary">Generate Hash</button>
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
