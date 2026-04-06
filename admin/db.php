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

$sr_admin_username = (string)(getenv('SR_ADMIN_USERNAME') ?: 'admin');
$sr_admin_password_hash = (string)(getenv('SR_ADMIN_PASSWORD_HASH') ?: '$2y$10$QYMnF3hPzdmyUE3275Xuwu0EJrpEahv38lficheL99LXjAQDPxmRS');

function sr_admin_is_logged_in(): bool
{
	return isset($_SESSION['sr_admin_auth']) && is_array($_SESSION['sr_admin_auth']) && (($_SESSION['sr_admin_auth']['logged_in'] ?? false) === true);
}

function sr_admin_current_user(): array
{
	if (!sr_admin_is_logged_in()) {
		return ['username' => null, 'login_time' => null];
	}
	$auth = (array)$_SESSION['sr_admin_auth'];
	return [
		'username' => isset($auth['username']) ? (string)$auth['username'] : null,
		'login_time' => isset($auth['login_time']) ? (int)$auth['login_time'] : null,
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
		return 'index.php';
	}
	if (preg_match('/^[a-z0-9-]+\.php$/i', $next) === 1) {
		return $next;
	}
	return 'index.php';
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
		return;
	}
	$target = basename((string)($_SERVER['PHP_SELF'] ?? 'index.php'));
	header('Location: login.php?next=' . rawurlencode($target));
	exit;
}
