<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
	$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
	$cookieParams = session_get_cookie_params();
	session_set_cookie_params([
		'lifetime' => 0,
		'path' => $cookieParams['path'] ?? '/',
		'domain' => $cookieParams['domain'] ?? '',
		'secure' => $secure,
		'httponly' => true,
		'samesite' => 'Lax',
	]);
	session_start();
}

require_once dirname(__DIR__) . '/includes/cms.php';

$sr_admin_username = (string)(getenv('SR_ADMIN_USERNAME') ?: 'admin');
$sr_admin_password_hash = (string)(getenv('SR_ADMIN_PASSWORD_HASH') ?: '$2y$10$QYMnF3hPzdmyUE3275Xuwu0EJrpEahv38lficheL99LXjAQDPxmRS');

function sr_admin_is_logged_in(): bool
{
	return isset($_SESSION['sr_admin_auth']) && is_array($_SESSION['sr_admin_auth']) && (($_SESSION['sr_admin_auth']['logged_in'] ?? false) === true);
}

function sr_admin_current_user(): array
{
	if (!sr_admin_is_logged_in()) {
		return ['username' => null, 'login_time' => null, 'full_name' => null, 'profile_image' => null, 'user_id' => null];
	}
	$auth = (array)$_SESSION['sr_admin_auth'];
	return [
		'username' => isset($auth['username']) ? (string)$auth['username'] : null,
		'login_time' => isset($auth['login_time']) ? (int)$auth['login_time'] : null,
		'full_name' => isset($auth['full_name']) ? (string)$auth['full_name'] : null,
		'profile_image' => isset($auth['profile_image']) ? (string)$auth['profile_image'] : null,
		'user_id' => isset($auth['user_id']) ? (int)$auth['user_id'] : null,
	];
}

function sr_admin_csrf_token(): string
{
	if (!isset($_SESSION['sr_admin_csrf']) || !is_string($_SESSION['sr_admin_csrf']) || $_SESSION['sr_admin_csrf'] === '') {
		$_SESSION['sr_admin_csrf'] = bin2hex(random_bytes(16));
	}
	return (string)$_SESSION['sr_admin_csrf'];
}

function sr_admin_verify_csrf(?string $token): bool
{
	if (!is_string($token) || $token === '') {
		return false;
	}
	if (!isset($_SESSION['sr_admin_csrf']) || !is_string($_SESSION['sr_admin_csrf'])) {
		return false;
	}
	return hash_equals((string)$_SESSION['sr_admin_csrf'], $token);
}

function sr_admin_safe_next(string $next): string
{
	$next = trim($next);
	if ($next === '') {
		return 'index';
	}
	if (preg_match('/^[a-z0-9-]+\.php$/i', $next) === 1) {
		return $next;
	}
	return 'index';
}

function sr_admin_login_attempts(): int
{
	return isset($_SESSION['sr_admin_login_attempts']) ? (int)$_SESSION['sr_admin_login_attempts'] : 0;
}

function sr_admin_login_locked_until(): int
{
	return isset($_SESSION['sr_admin_login_locked_until']) ? (int)$_SESSION['sr_admin_login_locked_until'] : 0;
}

function sr_admin_register_failed_login(): void
{
	$attempts = sr_admin_login_attempts() + 1;
	$_SESSION['sr_admin_login_attempts'] = $attempts;
	if ($attempts >= 8) {
		$_SESSION['sr_admin_login_locked_until'] = time() + 300;
	}
}

function sr_admin_reset_failed_login(): void
{
	unset($_SESSION['sr_admin_login_attempts'], $_SESSION['sr_admin_login_locked_until']);
}

function sr_admin_attempt_login(string $username, string $password): bool
{
	global $sr_admin_username, $sr_admin_password_hash;

	$lockedUntil = sr_admin_login_locked_until();
	if ($lockedUntil > time()) {
		return false;
	}

	$username = trim($username);
	$password = (string)$password;

	$db = sr_cms_db_try();
	if ($db instanceof mysqli) {
		$res = $db->query('SELECT COUNT(*) AS c FROM admin_users');
		if ($res && ($row = $res->fetch_assoc())) {
			$count = (int)($row['c'] ?? 0);
			if ($count === 0) {
				$stmtSeed = $db->prepare('INSERT INTO admin_users (username, password_hash, full_name, is_active) VALUES (?, ?, ?, 1)');
				if ($stmtSeed) {
					$defaultName = 'Administrator';
					$stmtSeed->bind_param('sss', $sr_admin_username, $sr_admin_password_hash, $defaultName);
					$stmtSeed->execute();
					$stmtSeed->close();
				}
			}
		}
		if ($res) {
			$res->free();
		}

		$stmt = $db->prepare('SELECT id, password_hash, full_name, profile_image, is_active FROM admin_users WHERE username=? LIMIT 1');
		if ($stmt) {
			$stmt->bind_param('s', $username);
			$stmt->execute();
			$stmt->bind_result($uid, $hash, $fullName, $profileImage, $isActive);
			if ($stmt->fetch()) {
				$stmt->close();
				if ((int)$isActive === 1 && password_verify($password, (string)$hash)) {
					sr_admin_reset_failed_login();
					session_regenerate_id(true);
					$_SESSION['sr_admin_auth'] = [
						'logged_in' => true,
						'username' => $username,
						'full_name' => (string)$fullName,
						'profile_image' => (string)$profileImage,
						'user_id' => (int)$uid,
						'login_time' => time(),
					];
					$uStmt = $db->prepare('UPDATE admin_users SET last_login_at = NOW() WHERE id=?');
					if ($uStmt) {
						$uStmt->bind_param('i', $uid);
						$uStmt->execute();
						$uStmt->close();
					}
					return true;
				}
				sr_admin_register_failed_login();
				return false;
			}
			$stmt->close();
		}
	}

	$okUser = hash_equals($sr_admin_username, $username);
	$okPass = password_verify($password, $sr_admin_password_hash);

	if (!$okUser || !$okPass) {
		sr_admin_register_failed_login();
		return false;
	}

	sr_admin_reset_failed_login();
	session_regenerate_id(true);
	$_SESSION['sr_admin_auth'] = [
		'logged_in' => true,
		'username' => $username,
		'full_name' => 'Administrator',
		'profile_image' => '',
		'user_id' => null,
		'login_time' => time(),
	];
	return true;
}

function sr_admin_logout(): void
{
	unset($_SESSION['sr_admin_auth']);
	session_regenerate_id(true);
}

function sr_admin_require_login(): void
{
	if (sr_admin_is_logged_in()) {
		$db = sr_cms_db_try();
		if ($db instanceof mysqli) {
			$auth = isset($_SESSION['sr_admin_auth']) && is_array($_SESSION['sr_admin_auth']) ? (array)$_SESSION['sr_admin_auth'] : [];
			$username = isset($auth['username']) ? (string)$auth['username'] : '';
			if ($username !== '') {
				$stmt = $db->prepare('SELECT id, full_name, profile_image, is_active FROM admin_users WHERE username=? LIMIT 1');
				if ($stmt) {
					$stmt->bind_param('s', $username);
					$stmt->execute();
					$stmt->bind_result($uid, $fullName, $profileImage, $isActive);
					$found = $stmt->fetch();
					$stmt->close();
					if ($found) {
						if ((int)$isActive === 1) {
							$_SESSION['sr_admin_auth']['user_id'] = (int)$uid;
							$_SESSION['sr_admin_auth']['full_name'] = (string)$fullName;
							$_SESSION['sr_admin_auth']['profile_image'] = (string)$profileImage;
						}
					} else {
						$res = $db->query('SELECT COUNT(*) AS c FROM admin_users');
						$count = 0;
						if ($res && ($row = $res->fetch_assoc())) {
							$count = (int)($row['c'] ?? 0);
						}
						if ($res) {
							$res->free();
						}
						if ($count === 0) {
							$seedHash = (string)(getenv('SR_ADMIN_PASSWORD_HASH') ?: '$2y$10$QYMnF3hPzdmyUE3275Xuwu0EJrpEahv38lficheL99LXjAQDPxmRS');
							$seedName = 'Administrator';
							$stmtSeed = $db->prepare('INSERT INTO admin_users (username, password_hash, full_name, is_active) VALUES (?, ?, ?, 1)');
							if ($stmtSeed) {
								$stmtSeed->bind_param('sss', $username, $seedHash, $seedName);
								$stmtSeed->execute();
								$newId = (int)$stmtSeed->insert_id;
								$stmtSeed->close();
								$_SESSION['sr_admin_auth']['user_id'] = $newId > 0 ? $newId : null;
								$_SESSION['sr_admin_auth']['full_name'] = $seedName;
								$_SESSION['sr_admin_auth']['profile_image'] = '';
							}
						}
					}
				}
			}
		}
		return;
	}
	$target = basename((string)($_SERVER['PHP_SELF'] ?? 'index.php'));
	header('Location: login.php?next=' . rawurlencode($target));
	exit;
}
