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
$logoMsg = '';
$logoErr = '';

function sr_settings_seed_setting_if_empty(mysqli $db, string $key, string $value): void
{
	$cur = sr_cms_setting_get($key, '');
	if (trim($cur) !== '') {
		return;
	}
	$stmt = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
	if (!$stmt) {
		return;
	}
	$stmt->bind_param('ss', $key, $value);
	$stmt->execute();
	$stmt->close();
}

function sr_settings_upload_image_to(string $absDir, string $relDir, string $prefix, array $file, int $maxBytes): array
{
	$out = ['ok' => false, 'path' => '', 'error' => ''];
	$err = isset($file['error']) ? (int) $file['error'] : UPLOAD_ERR_NO_FILE;
	if ($err === UPLOAD_ERR_NO_FILE) {
		return $out;
	}
	if ($err !== UPLOAD_ERR_OK) {
		$out['error'] = 'Unable to upload image.';
		return $out;
	}
	$tmp = (string)($file['tmp_name'] ?? '');
	$size = (int)($file['size'] ?? 0);
	if ($size <= 0 || $size > $maxBytes) {
		$out['error'] = 'Image file is too large.';
		return $out;
	}
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
		$out['error'] = 'Image must be JPG, PNG, or WEBP.';
		return $out;
	}
	if (!is_dir($absDir)) {
		@mkdir($absDir, 0777, true);
	}
	$filename = $prefix . '-' . time() . '-' . bin2hex(random_bytes(3)) . '.' . $ext;
	$dest = rtrim($absDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
	if (!@move_uploaded_file($tmp, $dest)) {
		$out['error'] = 'Unable to save uploaded image.';
		return $out;
	}
	$out['ok'] = true;
	$out['path'] = rtrim($relDir, '/') . '/' . $filename;
	return $out;
}

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
	'social_facebook_enabled',
	'social_instagram',
	'social_instagram_enabled',
	'social_linkedin',
	'social_linkedin_enabled',
	'social_youtube',
	'social_youtube_enabled',
	'social_whatsapp_url',
	'social_whatsapp_enabled',
	'mail_from_email',
	'mail_from_name',
	'smtp_host',
	'smtp_port',
	'smtp_user',
	'smtp_pass',
	'smtp_secure',
	'home_kicker',
];

if ($db instanceof mysqli && $_SERVER['REQUEST_METHOD'] !== 'POST') {
	$hasAny = trim(sr_cms_setting_get('company_name', '')) !== '' || trim(sr_cms_setting_get('company_email', '')) !== '';
	if (!$hasAny) {
		$defaults = [
			'company_name' => 'Shivanjali Renewables',
			'company_email' => 'info@shivanjalirenewables.com',
			'company_phone' => '+91 8686 313 133',
			'company_phone_tel' => '+918686313133',
			'company_phone1' => '+91 8686 313 133',
			'company_phone1_tel' => '+918686313133',
			'company_phone2' => '+91 7447 777 070',
			'company_phone2_tel' => '+917447777070',
			'company_phone3' => '+91 8889 303 303',
			'company_phone3_tel' => '+918889303303',
			'company_address' => 'Office No. 505, ABH Samruddhi, Near Dream Castle Signal, Makhamalabad Road, Nashik – 422003, Maharashtra, India',
			'company_map_label' => 'Shivanjali Renewables, Nashik',
			'company_map_url' => 'https://maps.app.goo.gl/4r1P4qqp36AEcAce8',
			'company_hours' => 'Monday – Saturday: 9:00 AM – 6:00 PM',
			'company_whatsapp_tel' => '918686313133',
			'social_facebook' => 'https://facebook.com/',
			'social_facebook_enabled' => '1',
			'social_instagram' => 'https://instagram.com/',
			'social_instagram_enabled' => '1',
			'social_linkedin' => 'https://linkedin.com/',
			'social_linkedin_enabled' => '1',
			'social_youtube' => 'https://youtube.com/',
			'social_youtube_enabled' => '1',
			'social_whatsapp_url' => 'https://wa.me/918686313133',
			'social_whatsapp_enabled' => '1',
			'mail_from_email' => 'info@shivanjalirenewables.com',
			'mail_from_name' => 'Shivanjali Renewables',
			'smtp_host' => '',
			'smtp_port' => '587',
			'smtp_user' => '',
			'smtp_pass' => '',
			'smtp_secure' => 'tls',
		];
		foreach ($defaults as $k => $v) {
			sr_settings_seed_setting_if_empty($db, (string)$k, (string)$v);
		}
		$has = 0;
		$res = $db->query('SELECT COUNT(*) AS c FROM cms_client_logos');
		if ($res) {
			$row = $res->fetch_assoc();
			$has = (int)($row['c'] ?? 0);
			$res->free();
		}
		if ($has === 0) {
			$ins = $db->prepare('INSERT INTO cms_client_logos (image, label, url, sort_order, is_active) VALUES (?, ?, "", ?, 1)');
			if ($ins) {
				for ($i = 1; $i <= 12; $i++) {
					$no = str_pad((string)$i, 2, '0', STR_PAD_LEFT);
					$image = 'images/client/client-dark-' . $no . '.png';
					$label = 'Client-' . $no;
					$sort = $i;
					$ins->bind_param('ssi', $image, $label, $sort);
					$ins->execute();
				}
				$ins->close();
			}
		}
	}
}

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

		if ($op === 'seed_dummy') {
			if (!$db instanceof mysqli) {
				$settingsErr = 'Database connection not available.';
			} else {
				$defaults = [
					'company_name' => 'Shivanjali Renewables',
					'company_email' => 'info@shivanjalirenewables.com',
					'company_phone' => '+91 8686 313 133',
					'company_phone_tel' => '+918686313133',
					'company_phone1' => '+91 8686 313 133',
					'company_phone1_tel' => '+918686313133',
					'company_phone2' => '+91 7447 777 070',
					'company_phone2_tel' => '+917447777070',
					'company_phone3' => '+91 8889 303 303',
					'company_phone3_tel' => '+918889303303',
					'company_address' => 'Office No. 505, ABH Samruddhi, Near Dream Castle Signal, Makhamalabad Road, Nashik – 422003, Maharashtra, India',
					'company_map_label' => 'Shivanjali Renewables, Nashik',
					'company_map_url' => 'https://maps.app.goo.gl/4r1P4qqp36AEcAce8',
					'company_hours' => 'Monday – Saturday: 9:00 AM – 6:00 PM',
					'company_whatsapp_tel' => '918686313133',
					'social_facebook' => 'https://facebook.com/',
					'social_facebook_enabled' => '1',
					'social_instagram' => 'https://instagram.com/',
					'social_instagram_enabled' => '1',
					'social_linkedin' => 'https://linkedin.com/',
					'social_linkedin_enabled' => '1',
					'social_youtube' => 'https://youtube.com/',
					'social_youtube_enabled' => '1',
					'social_whatsapp_url' => 'https://wa.me/918686313133',
					'social_whatsapp_enabled' => '1',
					'mail_from_email' => 'info@shivanjalirenewables.com',
					'mail_from_name' => 'Shivanjali Renewables',
					'smtp_host' => '',
					'smtp_port' => '587',
					'smtp_user' => '',
					'smtp_pass' => '',
					'smtp_secure' => 'tls',
				];
				foreach ($defaults as $k => $v) {
					sr_settings_seed_setting_if_empty($db, (string)$k, (string)$v);
				}

				$has = 0;
				$res = $db->query('SELECT COUNT(*) AS c FROM cms_client_logos');
				if ($res) {
					$row = $res->fetch_assoc();
					$has = (int)($row['c'] ?? 0);
					$res->free();
				}
				if ($has === 0) {
					$ins = $db->prepare('INSERT INTO cms_client_logos (image, label, url, sort_order, is_active) VALUES (?, ?, "", ?, 1)');
					if ($ins) {
						for ($i = 1; $i <= 12; $i++) {
							$no = str_pad((string)$i, 2, '0', STR_PAD_LEFT);
							$image = 'images/client/client-dark-' . $no . '.png';
							$label = 'Client-' . $no;
							$sort = $i;
							$ins->bind_param('ssi', $image, $label, $sort);
							$ins->execute();
						}
						$ins->close();
					}
				}

				$settingsMsg = 'Dummy settings saved (only empty fields were filled).';
			}
		}

		if ($op === 'client_logo_save') {
			if (!$db instanceof mysqli) {
				$logoErr = 'Database connection not available.';
			} else {
				$logoId = isset($_POST['logo_id']) ? (int) $_POST['logo_id'] : 0;
				$label = trim((string) ($_POST['label'] ?? ''));
				$url = trim((string) ($_POST['url'] ?? ''));
				$sort = isset($_POST['sort_order']) ? (int) $_POST['sort_order'] : 0;
				$isActive = isset($_POST['is_active']) ? 1 : 0;
				$image = trim((string) ($_POST['image_existing'] ?? ''));

				if (isset($_FILES['logo_image']) && is_array($_FILES['logo_image'])) {
					$up = sr_settings_upload_image_to(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'client', 'images/client', 'client-logo', $_FILES['logo_image'], 3_000_000);
					if ($up['error'] !== '') {
						$logoErr = $up['error'];
					} elseif ($up['ok']) {
						$image = (string) $up['path'];
					}
				}

				if ($logoErr === '') {
					if ($logoId > 0) {
						$stmt = $db->prepare('UPDATE cms_client_logos SET image=?, label=?, url=?, sort_order=?, is_active=? WHERE id=?');
						if (!$stmt) {
							$logoErr = 'Unable to save logo.';
						} else {
							$stmt->bind_param('sssiii', $image, $label, $url, $sort, $isActive, $logoId);
							$stmt->execute();
							$stmt->close();
							$logoMsg = 'Client logo updated.';
						}
					} else {
						$stmt = $db->prepare('INSERT INTO cms_client_logos (image, label, url, sort_order, is_active) VALUES (?, ?, ?, ?, ?)');
						if (!$stmt) {
							$logoErr = 'Unable to add logo.';
						} else {
							$stmt->bind_param('sssii', $image, $label, $url, $sort, $isActive);
							$stmt->execute();
							$stmt->close();
							$logoMsg = 'Client logo added.';
						}
					}
				}
			}
		}

		if ($op === 'client_logo_delete') {
			if (!$db instanceof mysqli) {
				$logoErr = 'Database connection not available.';
			} else {
				$logoId = isset($_POST['logo_id']) ? (int) $_POST['logo_id'] : 0;
				if ($logoId > 0) {
					$stmt = $db->prepare('DELETE FROM cms_client_logos WHERE id=?');
					if (!$stmt) {
						$logoErr = 'Unable to delete logo.';
					} else {
						$stmt->bind_param('i', $logoId);
						$stmt->execute();
						$stmt->close();
						$logoMsg = 'Client logo deleted.';
					}
				}
			}
		}

		if ($op === 'send_test_email') {
			$testTo = trim((string)($_POST['test_email_to'] ?? ''));
			if ($testTo === '' || !filter_var($testTo, FILTER_VALIDATE_EMAIL)) {
				$settingsErr = 'Test email address is not valid.';
			} else {
				$companyName = sr_cms_setting_get('company_name', 'Website');
				$subject = 'SMTP Test - ' . $companyName;
				$text = "This is a test email sent from the admin panel.\n\nIf you received this, SMTP is working.\n";
				$html = '<div style="font-family:Arial,sans-serif;max-width:640px;margin:0 auto;padding:18px;border:1px solid #e5e7eb;border-radius:14px;">'
					. '<h2 style="margin:0 0 8px 0;">SMTP Test</h2>'
					. '<p style="margin:0;color:#475569;">This is a test email sent from the admin panel.</p>'
					. '<p style="margin:14px 0 0 0;color:#475569;">If you received this, SMTP is working.</p>'
					. '</div>';
				$ok = sr_cms_send_mail($testTo, $testTo, $subject, $text, $html);
				if ($ok) {
					$settingsMsg = 'Test email sent to ' . $testTo . '.';
				} else {
					$settingsErr = 'Test email failed: ' . sr_cms_mail_last_error();
				}
			}
		}

		if ($op === 'save_settings') {
			if (!$db instanceof mysqli) {
				$settingsErr = 'Database connection not available.';
			} else {
				$enableKeys = [
					'social_facebook_enabled',
					'social_instagram_enabled',
					'social_linkedin_enabled',
					'social_youtube_enabled',
					'social_whatsapp_enabled',
				];
				foreach ($enableKeys as $k) {
					$_POST[$k] = isset($_POST[$k]) ? '1' : '0';
				}

				$mailFromEmail = trim((string)($_POST['mail_from_email'] ?? ''));
				$smtpUser = trim((string)($_POST['smtp_user'] ?? ''));
				$smtpHost = trim((string)($_POST['smtp_host'] ?? ''));
				if ($mailFromEmail !== '' && !filter_var($mailFromEmail, FILTER_VALIDATE_EMAIL)) {
					$settingsErr = 'From email is not a valid email address.';
				} elseif ($smtpUser !== '' && !filter_var($smtpUser, FILTER_VALIDATE_EMAIL)) {
					$settingsErr = 'SMTP username must be a valid email address.';
				} elseif ($smtpHost !== '' && trim((string)($_POST['smtp_pass'] ?? '')) === '') {
					$settingsErr = 'SMTP password is required when SMTP host is set.';
				}

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

$clientLogos = [];
$logoEditId = isset($_GET['logo_id']) ? (int) $_GET['logo_id'] : 0;
$logoEditing = [
	'id' => 0,
	'image' => '',
	'label' => '',
	'url' => '',
	'sort_order' => 0,
	'is_active' => 1,
];
if ($db instanceof mysqli) {
	$res = $db->query('SELECT id, image, label, url, sort_order, is_active, updated_at FROM cms_client_logos ORDER BY sort_order ASC, updated_at DESC LIMIT 200');
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$clientLogos[] = $row;
		}
		$res->free();
	}
	if ($logoEditId > 0) {
		$stmt = $db->prepare('SELECT id, image, label, url, sort_order, is_active FROM cms_client_logos WHERE id=? LIMIT 1');
		if ($stmt) {
			$stmt->bind_param('i', $logoEditId);
			$stmt->execute();
			$stmt->bind_result($lid, $limg, $llabel, $lurl, $lsort, $lactive);
			if ($stmt->fetch()) {
				$logoEditing = [
					'id' => (int) $lid,
					'image' => (string) $limg,
					'label' => (string) $llabel,
					'url' => (string) $lurl,
					'sort_order' => (int) $lsort,
					'is_active' => (int) $lactive,
				];
			}
			$stmt->close();
		}
	}
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
			<?php if ($logoMsg !== '') { ?>
				<div class="alert alert-success"><?php echo htmlspecialchars($logoMsg, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php } ?>
			<?php if ($logoErr !== '') { ?>
				<div class="alert alert-danger"><?php echo htmlspecialchars($logoErr, ENT_QUOTES, 'UTF-8'); ?></div>
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
								<div class="d-flex align-items-center gap-2 flex-wrap">
									<!-- <form method="post" action="settings.php" onsubmit="return confirm('Add dummy data to empty fields?');" class="m-0">
										<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
										<input type="hidden" name="op" value="seed_dummy">
										<button type="submit" class="btn btn-outline-secondary">Add Dummy Data</button>
									</form> -->
									<a class="btn btn-outline-primary" href="../" target="_blank" rel="noopener">Open Website</a>
								</div>
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
												<?php
												$sr_logo_preview = $settings['site_logo'] !== '' ? $settings['site_logo'] : 'images/Shivanjali_Logo.jpg';
												$sr_favicon_preview = $settings['site_favicon'] !== '' ? $settings['site_favicon'] : 'images/fevicon.png';
												$sr_logo_preview = preg_match('#^https?://#i', $sr_logo_preview) ? $sr_logo_preview : ('../' . ltrim($sr_logo_preview, '/'));
												$sr_favicon_preview = preg_match('#^https?://#i', $sr_favicon_preview) ? $sr_favicon_preview : ('../' . ltrim($sr_favicon_preview, '/'));
												?>
												<img src="<?php echo htmlspecialchars($sr_logo_preview, ENT_QUOTES, 'UTF-8'); ?>" alt="Logo" style="width:64px;height:64px;object-fit:contain;background:#fff;border:1px solid rgba(10,25,38,.12);border-radius:16px;padding:8px;">
												<img src="<?php echo htmlspecialchars($sr_favicon_preview, ENT_QUOTES, 'UTF-8'); ?>" alt="Favicon" style="width:44px;height:44px;object-fit:contain;background:#fff;border:1px solid rgba(10,25,38,.12);border-radius:14px;padding:6px;">
												<div>
													<div class="fw-bold text-dark">Branding</div>
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
									<div class="col-12">
										<div class="p-3 rounded-3 border bg-light">
											<div class="fw-bold text-dark">Contact &amp; Social</div>
											<div class="text-title-gray">These details are used on the Contact page and in the header/footer.</div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="d-flex align-items-center justify-content-between">
											<label class="form-label mb-0">Facebook URL</label>
											<div class="form-check form-switch">
												<input class="form-check-input" type="checkbox" name="social_facebook_enabled" value="1" <?php echo ($settings['social_facebook_enabled'] === '' || $settings['social_facebook_enabled'] === '1') ? 'checked' : ''; ?>>
											</div>
										</div>
										<input class="form-control" name="social_facebook" value="<?php echo htmlspecialchars($settings['social_facebook'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-6">
										<div class="d-flex align-items-center justify-content-between">
											<label class="form-label mb-0">Instagram URL</label>
											<div class="form-check form-switch">
												<input class="form-check-input" type="checkbox" name="social_instagram_enabled" value="1" <?php echo ($settings['social_instagram_enabled'] === '' || $settings['social_instagram_enabled'] === '1') ? 'checked' : ''; ?>>
											</div>
										</div>
										<input class="form-control" name="social_instagram" value="<?php echo htmlspecialchars($settings['social_instagram'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-6">
										<div class="d-flex align-items-center justify-content-between">
											<label class="form-label mb-0">LinkedIn URL</label>
											<div class="form-check form-switch">
												<input class="form-check-input" type="checkbox" name="social_linkedin_enabled" value="1" <?php echo ($settings['social_linkedin_enabled'] === '' || $settings['social_linkedin_enabled'] === '1') ? 'checked' : ''; ?>>
											</div>
										</div>
										<input class="form-control" name="social_linkedin" value="<?php echo htmlspecialchars($settings['social_linkedin'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-6">
										<div class="d-flex align-items-center justify-content-between">
											<label class="form-label mb-0">YouTube URL</label>
											<div class="form-check form-switch">
												<input class="form-check-input" type="checkbox" name="social_youtube_enabled" value="1" <?php echo ($settings['social_youtube_enabled'] === '' || $settings['social_youtube_enabled'] === '1') ? 'checked' : ''; ?>>
											</div>
										</div>
										<input class="form-control" name="social_youtube" value="<?php echo htmlspecialchars($settings['social_youtube'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-12">
										<div class="d-flex align-items-center justify-content-between">
											<label class="form-label mb-0">WhatsApp link (optional)</label>
											<div class="form-check form-switch">
												<input class="form-check-input" type="checkbox" name="social_whatsapp_enabled" value="1" <?php echo ($settings['social_whatsapp_enabled'] === '' || $settings['social_whatsapp_enabled'] === '1') ? 'checked' : ''; ?>>
											</div>
										</div>
										<input class="form-control" name="social_whatsapp_url" value="<?php echo htmlspecialchars($settings['social_whatsapp_url'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://wa.me/91XXXXXXXXXX">
										<div class="form-text">If empty, Contact page will use WhatsApp number from Company settings.</div>
									</div>

									<div class="col-12">
										<div class="p-3 rounded-3 border bg-light">
											<div class="fw-bold text-dark">Email (PHPMailer)</div>
											<div class="text-title-gray">Optional SMTP setup. If SMTP is empty, server mail() is used.</div>
										</div>
									</div>
									<div class="col-lg-6">
										<label class="form-label">From email</label>
										<input class="form-control" name="mail_from_email" value="<?php echo htmlspecialchars($settings['mail_from_email'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-6">
										<label class="form-label">From name</label>
										<input class="form-control" name="mail_from_name" value="<?php echo htmlspecialchars($settings['mail_from_name'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-4">
										<label class="form-label">SMTP host</label>
										<input class="form-control" name="smtp_host" value="<?php echo htmlspecialchars($settings['smtp_host'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="smtp.gmail.com">
									</div>
									<div class="col-lg-2">
										<label class="form-label">SMTP port</label>
										<input class="form-control" name="smtp_port" value="<?php echo htmlspecialchars($settings['smtp_port'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="587">
									</div>
									<div class="col-lg-3">
										<label class="form-label">SMTP secure</label>
										<select class="form-select" name="smtp_secure">
											<?php $sec = strtolower(trim((string)$settings['smtp_secure'])); ?>
											<option value="" <?php echo $sec === '' ? 'selected' : ''; ?>>None</option>
											<option value="tls" <?php echo $sec === 'tls' ? 'selected' : ''; ?>>TLS</option>
											<option value="ssl" <?php echo $sec === 'ssl' ? 'selected' : ''; ?>>SSL</option>
										</select>
									</div>
									<div class="col-lg-3">
										<label class="form-label">SMTP username</label>
										<input class="form-control" name="smtp_user" value="<?php echo htmlspecialchars($settings['smtp_user'], ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="col-lg-6">
										<label class="form-label">SMTP password</label>
										<input class="form-control" type="password" name="smtp_pass" value="<?php echo htmlspecialchars($settings['smtp_pass'], ENT_QUOTES, 'UTF-8'); ?>" autocomplete="new-password">
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
							<form class="mt-3" method="post" action="settings.php">
								<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
								<input type="hidden" name="op" value="send_test_email">
								<div class="row g-3 align-items-end">
									<div class="col-lg-8">
										<label class="form-label">Send test email to</label>
										<input class="form-control" name="test_email_to" value="<?php echo htmlspecialchars($settings['company_email'] !== '' ? $settings['company_email'] : $settings['mail_from_email'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="you@example.com">
									</div>
									<div class="col-lg-4 d-flex justify-content-end">
										<button type="submit" class="btn btn-outline-primary">Send Test Email</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
								<h4 class="mb-0">Client Logos (Contact Page)</h4>
								<a class="btn btn-outline-primary" href="settings.php">Clear</a>
							</div>
						</div>
						<div class="card-body">
							<div class="row g-3">
								<div class="col-lg-5">
									<form method="post" action="settings.php<?php echo $logoEditing['id'] > 0 ? ('?logo_id=' . (int)$logoEditing['id']) : ''; ?>" enctype="multipart/form-data">
										<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
										<input type="hidden" name="op" value="client_logo_save">
										<input type="hidden" name="logo_id" value="<?php echo (int) $logoEditing['id']; ?>">
										<input type="hidden" name="image_existing" value="<?php echo htmlspecialchars((string) $logoEditing['image'], ENT_QUOTES, 'UTF-8'); ?>">

										<div class="p-3 rounded-3 border bg-light">
											<div class="fw-bold text-dark"><?php echo $logoEditing['id'] > 0 ? 'Edit Logo' : 'Add Logo'; ?></div>
											<div class="text-title-gray">Upload logo image and optionally link it.</div>
										</div>

										<div class="mt-3">
											<label class="form-label">Logo image (JPG/PNG/WEBP)</label>
											<input class="form-control" type="file" name="logo_image" accept="image/jpeg,image/png,image/webp">
											<?php if (trim((string) $logoEditing['image']) !== '') { ?>
												<?php $p = (string) $logoEditing['image'];
												$p = preg_match('#^https?://#i', $p) ? $p : ('../' . ltrim($p, '/')); ?>
												<div class="mt-2">
													<img src="<?php echo htmlspecialchars($p, ENT_QUOTES, 'UTF-8'); ?>" alt="Preview" style="width:100%;max-width:420px;height:130px;object-fit:contain;border-radius:16px;border:1px solid rgba(10,25,38,.12);background:#fff;padding:14px;">
												</div>
											<?php } ?>
										</div>

										<div class="mt-3">
											<label class="form-label">Label (optional)</label>
											<input class="form-control" name="label" value="<?php echo htmlspecialchars((string) $logoEditing['label'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>

										<div class="mt-3">
											<label class="form-label">Link URL (optional)</label>
											<input class="form-control" name="url" value="<?php echo htmlspecialchars((string) $logoEditing['url'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>

										<div class="row g-3 mt-1">
											<div class="col-6">
												<label class="form-label">Sort order</label>
												<input class="form-control" type="number" name="sort_order" value="<?php echo (int) $logoEditing['sort_order']; ?>">
											</div>
											<div class="col-6 d-flex align-items-end">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" name="is_active" value="1" <?php echo (int) $logoEditing['is_active'] === 1 ? 'checked' : ''; ?>>
													<label class="form-check-label">Active</label>
												</div>
											</div>
										</div>

										<div class="d-flex gap-2 flex-wrap mt-3">
											<button type="submit" class="btn btn-primary"><?php echo $logoEditing['id'] > 0 ? 'Save Changes' : 'Add Logo'; ?></button>
											<?php if ($logoEditing['id'] > 0) { ?>
												<a class="btn btn-outline-secondary" href="settings.php">Cancel</a>
											<?php } ?>
										</div>
									</form>
								</div>

								<div class="col-lg-7">
									<div class="table-responsive">
										<table class="table table-striped align-middle mb-0">
											<thead>
												<tr>
													<th style="width:90px;">Image</th>
													<th>Label</th>
													<th>Status</th>
													<th style="width:90px;">Sort</th>
													<th class="text-end">Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php if (!$clientLogos) { ?>
													<tr>
														<td colspan="5" class="text-center text-title-gray py-4">No logos yet.</td>
													</tr>
												<?php } ?>
												<?php foreach ($clientLogos as $l) { ?>
													<?php
													$img = trim((string)($l['image'] ?? ''));
													$imgPreview = $img !== '' ? ('../' . ltrim($img, '/')) : '';
													$active = (int)($l['is_active'] ?? 1) === 1;
													$badge = $active ? 'bg-light-success text-success' : 'bg-light-secondary text-secondary';
													?>
													<tr>
														<td>
															<?php if ($imgPreview !== '') { ?>
																<img src="<?php echo htmlspecialchars($imgPreview, ENT_QUOTES, 'UTF-8'); ?>" alt="Logo" style="width:74px;height:44px;object-fit:contain;border-radius:10px;border:1px solid rgba(10,25,38,.12);background:#fff;padding:8px;">
															<?php } else { ?>
																<span class="text-title-gray">—</span>
															<?php } ?>
														</td>
														<td>
															<div class="fw-bold"><?php echo htmlspecialchars((string)($l['label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
															<?php if (trim((string)($l['url'] ?? '')) !== '') { ?>
																<div class="text-title-gray"><?php echo htmlspecialchars((string)($l['url'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
															<?php } ?>
														</td>
														<td><span class="badge rounded-pill <?php echo $badge; ?>"><?php echo $active ? 'Active' : 'Disabled'; ?></span></td>
														<td><?php echo (int)($l['sort_order'] ?? 0); ?></td>
														<td class="text-end">
															<a class="btn btn-sm btn-outline-primary" href="settings.php?logo_id=<?php echo (int)($l['id'] ?? 0); ?>">Edit</a>
															<form class="d-inline" method="post" action="settings.php" onsubmit="return confirm('Delete this logo?');">
																<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
																<input type="hidden" name="op" value="client_logo_delete">
																<input type="hidden" name="logo_id" value="<?php echo (int)($l['id'] ?? 0); ?>">
																<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
															</form>
														</td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
									<div class="mt-3 p-3 rounded-3 border bg-light">
										<div class="fw-bold text-dark">Tip</div>
										<div class="text-title-gray">These logos are shown in the Contact page “Top Brands” carousel.</div>
									</div>
								</div>
							</div>
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
