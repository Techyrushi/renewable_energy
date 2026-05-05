<?php

declare(strict_types=1);

function sr_cms_load_env_file(): void
{
	static $loaded = false;
	if ($loaded) {
		return;
	}
	$loaded = true;

	$envPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
	if (!is_file($envPath) || !is_readable($envPath)) {
		return;
	}

	$lines = @file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	if (!is_array($lines)) {
		return;
	}

	foreach ($lines as $line) {
		$line = trim((string) $line);
		if ($line === '' || str_starts_with($line, '#')) {
			continue;
		}
		$eqPos = strpos($line, '=');
		if ($eqPos === false) {
			continue;
		}
		$key = trim(substr($line, 0, $eqPos));
		$val = trim(substr($line, $eqPos + 1));
		if ($key === '') {
			continue;
		}
		if (strlen($val) >= 2) {
			$first = $val[0];
			$last = $val[strlen($val) - 1];
			if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
				$val = substr($val, 1, -1);
			}
		}
		if (getenv($key) === false) {
			putenv($key . '=' . $val);
			$_ENV[$key] = $val;
			$_SERVER[$key] = $val;
		}
	}
}

function sr_cms_db_try(): ?mysqli
{
	static $db = null;
	static $attempted = false;

	if ($attempted) {
		return $db;
	}
	$attempted = true;
	sr_cms_load_env_file();

	$host = trim((string)(getenv('SR_DB_HOST') ?: ''));
	$user = trim((string)(getenv('SR_DB_USER') ?: ''));
	$pass = (string)(getenv('SR_DB_PASS') ?: '');
	$name = trim((string)(getenv('SR_DB_NAME') ?: ''));
	$port = (int)(getenv('SR_DB_PORT') ?: 3306);

	if ($host === '' || $user === '' || $name === '') {
		error_log('CMS DB config missing. Required env vars: SR_DB_HOST, SR_DB_USER, SR_DB_NAME');
		$db = null;
		return null;
	}

	mysqli_report(MYSQLI_REPORT_OFF);
	$conn = @mysqli_connect($host, $user, $pass, $name, $port);
	if (!$conn instanceof mysqli) {
		$err = mysqli_connect_error();
		error_log('CMS DB connect failed for host: ' . $host . ', db: ' . $name . ', user: ' . $user . ', error: ' . ($err !== '' ? $err : 'unknown error'));
		$db = null;
		return null;
	}

	$conn->set_charset('utf8mb4');
	$db = $conn;

	sr_cms_migrate($db);

	return $db;
}

function sr_cms_db_required(): mysqli
{
	$db = sr_cms_db_try();
	if (!$db instanceof mysqli) {
		http_response_code(500);
		echo 'Database connection not available.';
		exit;
	}
	return $db;
}

function sr_cms_migrate(mysqli $db): void
{
	$db->query("CREATE TABLE IF NOT EXISTS cms_settings (
		k VARCHAR(120) NOT NULL,
		v TEXT NOT NULL,
		updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (k)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$db->query("CREATE TABLE IF NOT EXISTS admin_users (
		id INT NOT NULL AUTO_INCREMENT,
		username VARCHAR(120) NOT NULL,
		password_hash VARCHAR(255) NOT NULL,
		full_name VARCHAR(255) NOT NULL DEFAULT '',
		profile_image VARCHAR(255) NOT NULL DEFAULT '',
		is_active TINYINT(1) NOT NULL DEFAULT 1,
		last_login_at DATETIME NULL,
		created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		UNIQUE KEY uniq_username (username),
		KEY idx_active (is_active)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$db->query("CREATE TABLE IF NOT EXISTS cms_banners (
		id INT NOT NULL AUTO_INCREMENT,
		image VARCHAR(255) NOT NULL DEFAULT '',
		kicker VARCHAR(255) NOT NULL DEFAULT '',
		title VARCHAR(255) NOT NULL DEFAULT '',
		subtitle TEXT NOT NULL,
		primary_label VARCHAR(120) NOT NULL DEFAULT '',
		primary_url VARCHAR(255) NOT NULL DEFAULT '',
		secondary_label VARCHAR(120) NOT NULL DEFAULT '',
		secondary_url VARCHAR(255) NOT NULL DEFAULT '',
		sort_order INT NOT NULL DEFAULT 0,
		is_active TINYINT(1) NOT NULL DEFAULT 1,
		created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		KEY idx_active_sort (is_active, sort_order)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$db->query("CREATE TABLE IF NOT EXISTS cms_testimonials (
		id INT NOT NULL AUTO_INCREMENT,
		name VARCHAR(255) NOT NULL DEFAULT '',
		company VARCHAR(255) NOT NULL DEFAULT '',
		quote TEXT NOT NULL,
		image VARCHAR(255) NOT NULL DEFAULT '',
		rating TINYINT NOT NULL DEFAULT 5,
		sort_order INT NOT NULL DEFAULT 0,
		is_active TINYINT(1) NOT NULL DEFAULT 1,
		created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		KEY idx_active_sort (is_active, sort_order)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$db->query("CREATE TABLE IF NOT EXISTS cms_client_logos (
		id INT NOT NULL AUTO_INCREMENT,
		image VARCHAR(255) NOT NULL DEFAULT '',
		label VARCHAR(255) NOT NULL DEFAULT '',
		url VARCHAR(255) NOT NULL DEFAULT '',
		sort_order INT NOT NULL DEFAULT 0,
		is_active TINYINT(1) NOT NULL DEFAULT 1,
		created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		KEY idx_active_sort (is_active, sort_order)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$db->query("CREATE TABLE IF NOT EXISTS cms_pages (
		id INT NOT NULL AUTO_INCREMENT,
		slug VARCHAR(120) NOT NULL,
		title VARCHAR(255) NOT NULL DEFAULT '',
		hero_title VARCHAR(255) NOT NULL DEFAULT '',
		hero_subtitle TEXT NOT NULL,
		banner_image VARCHAR(255) NOT NULL DEFAULT '',
		content LONGTEXT NOT NULL,
		updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		UNIQUE KEY uniq_slug (slug)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$db->query("CREATE TABLE IF NOT EXISTS cms_seo_routes (
		route VARCHAR(190) NOT NULL,
		title VARCHAR(255) NOT NULL DEFAULT '',
		description TEXT NOT NULL,
		keywords TEXT NOT NULL,
		og_image VARCHAR(255) NOT NULL DEFAULT '',
		noindex TINYINT(1) NOT NULL DEFAULT 0,
		updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (route)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$db->query("CREATE TABLE IF NOT EXISTS cms_blog_posts (
		id INT NOT NULL AUTO_INCREMENT,
		slug VARCHAR(160) NOT NULL,
		title VARCHAR(255) NOT NULL,
		category VARCHAR(120) NOT NULL DEFAULT '',
		date_label VARCHAR(60) NOT NULL DEFAULT '',
		read_time VARCHAR(40) NOT NULL DEFAULT '',
		cover_image VARCHAR(255) NOT NULL DEFAULT '',
		excerpt TEXT NOT NULL,
		content LONGTEXT NOT NULL,
		published TINYINT(1) NOT NULL DEFAULT 1,
		published_at DATETIME NULL,
		created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		UNIQUE KEY uniq_slug (slug),
		KEY idx_published (published, published_at)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$db->query("CREATE TABLE IF NOT EXISTS cms_blog_faqs (
		id INT NOT NULL AUTO_INCREMENT,
		question VARCHAR(255) NOT NULL,
		answer TEXT NOT NULL,
		sort_order INT NOT NULL DEFAULT 0,
		is_active TINYINT(1) NOT NULL DEFAULT 1,
		created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		KEY idx_active_sort (is_active, sort_order)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$db->query("CREATE TABLE IF NOT EXISTS cms_projects (
		id INT NOT NULL AUTO_INCREMENT,
		slug VARCHAR(160) NULL DEFAULT NULL,
		category VARCHAR(60) NOT NULL DEFAULT 'rooftop',
		category_label VARCHAR(120) NOT NULL DEFAULT '',
		title VARCHAR(255) NOT NULL,
		location_label VARCHAR(255) NOT NULL DEFAULT '',
		capacity_label VARCHAR(255) NOT NULL DEFAULT '',
		savings_label VARCHAR(255) NOT NULL DEFAULT '',
		outcome_label VARCHAR(255) NOT NULL DEFAULT '',
		image VARCHAR(255) NOT NULL DEFAULT '',
		content LONGTEXT NULL,
		featured TINYINT(1) NOT NULL DEFAULT 1,
		sort_order INT NOT NULL DEFAULT 0,
		created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		UNIQUE KEY uniq_slug (slug),
		KEY idx_featured (featured, sort_order),
		KEY idx_category (category)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$db->query("CREATE TABLE IF NOT EXISTS cms_products (
		id INT NOT NULL AUTO_INCREMENT,
		slug VARCHAR(160) NOT NULL,
		category_anchor VARCHAR(80) NOT NULL DEFAULT '',
		badge_label VARCHAR(120) NOT NULL DEFAULT '',
		title VARCHAR(255) NOT NULL,
		range_label VARCHAR(255) NOT NULL DEFAULT '',
		short_desc TEXT NOT NULL,
		bullets TEXT NOT NULL,
		image VARCHAR(255) NOT NULL DEFAULT '',
		content LONGTEXT NOT NULL,
		published TINYINT(1) NOT NULL DEFAULT 1,
		sort_order INT NOT NULL DEFAULT 0,
		created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		UNIQUE KEY uniq_slug (slug),
		KEY idx_pub_sort (published, sort_order),
		KEY idx_anchor (category_anchor)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$db->query("CREATE TABLE IF NOT EXISTS cms_services (
		id INT NOT NULL AUTO_INCREMENT,
		slug VARCHAR(160) NOT NULL,
		title VARCHAR(255) NOT NULL,
		short_desc TEXT NOT NULL,
		image VARCHAR(255) NOT NULL DEFAULT '',
		icon_svg TEXT NOT NULL,
		content LONGTEXT NOT NULL,
		published TINYINT(1) NOT NULL DEFAULT 1,
		sort_order INT NOT NULL DEFAULT 0,
		created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		UNIQUE KEY uniq_slug (slug),
		KEY idx_pub_sort (published, sort_order)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$db->query("CREATE TABLE IF NOT EXISTS cms_enquiries (
		id INT NOT NULL AUTO_INCREMENT,
		full_name VARCHAR(255) NOT NULL,
		phone VARCHAR(60) NOT NULL,
		email VARCHAR(255) NOT NULL,
		city VARCHAR(120) NOT NULL DEFAULT '',
		customer_type VARCHAR(120) NOT NULL DEFAULT '',
		system_size VARCHAR(120) NOT NULL DEFAULT '',
		source VARCHAR(120) NOT NULL DEFAULT '',
		message TEXT NOT NULL,
		status VARCHAR(40) NOT NULL DEFAULT 'new',
		created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		KEY idx_status_date (status, created_at)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$db->query("ALTER TABLE cms_projects ADD COLUMN slug VARCHAR(160) NULL DEFAULT NULL");
	$db->query("ALTER TABLE cms_projects ADD COLUMN content LONGTEXT NULL");
	$db->query("ALTER TABLE cms_projects ADD UNIQUE KEY uniq_slug (slug)");

	$db->query("ALTER TABLE cms_pages ADD COLUMN banner_image VARCHAR(255) NOT NULL DEFAULT ''");
}

function sr_cms_phpmailer_load(): bool
{
	static $loaded = false;
	static $attempted = false;
	if ($attempted) {
		return $loaded;
	}
	$attempted = true;

	$vendor = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
	if (is_file($vendor)) {
		require_once $vendor;
		if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
			$loaded = true;
			return $loaded;
		}
	}

	$base = __DIR__ . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR;
	$ex = $base . 'Exception.php';
	$pm = $base . 'PHPMailer.php';
	$smtp = $base . 'SMTP.php';
	if (is_file($ex) && is_file($pm) && is_file($smtp)) {
		require_once $ex;
		require_once $smtp;
		require_once $pm;
		$loaded = true;
	}
	return $loaded;
}

function sr_cms_mail_last_error(bool $clear = true): string
{
	$msg = isset($GLOBALS['sr_cms_last_mail_error']) ? (string) $GLOBALS['sr_cms_last_mail_error'] : '';
	if ($clear) {
		$GLOBALS['sr_cms_last_mail_error'] = '';
	}
	return $msg;
}

function sr_cms_send_mail(string $toEmail, string $toName, string $subject, string $textBody, string $htmlBody = '', string $replyToEmail = '', string $replyToName = ''): bool
{
	$GLOBALS['sr_cms_last_mail_error'] = '';
	$toEmail = trim($toEmail);
	if ($toEmail === '' || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
		$GLOBALS['sr_cms_last_mail_error'] = 'Invalid recipient email.';
		return false;
	}
	$companyEmail = trim(sr_cms_setting_get('company_email', ''));
	$companyName = trim(sr_cms_setting_get('company_name', ''));
	$fromEmail = trim(sr_cms_setting_get('mail_from_email', $companyEmail));
	$fromName = trim(sr_cms_setting_get('mail_from_name', $companyName));

	$smtpHost = trim(sr_cms_setting_get('smtp_host', ''));
	$smtpPort = (int) sr_cms_setting_get('smtp_port', '587');
	$smtpUser = trim(sr_cms_setting_get('smtp_user', ''));
	$smtpPass = (string) sr_cms_setting_get('smtp_pass', '');
	$smtpSecure = strtolower(trim(sr_cms_setting_get('smtp_secure', 'tls')));

	if ($fromEmail === '' || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
		if ($smtpUser !== '' && filter_var($smtpUser, FILTER_VALIDATE_EMAIL)) {
			$fromEmail = $smtpUser;
		} elseif ($companyEmail !== '' && filter_var($companyEmail, FILTER_VALIDATE_EMAIL)) {
			$fromEmail = $companyEmail;
		} else {
			$fromEmail = $toEmail;
		}
	}
	if ($fromName === '') {
		$fromName = $companyName !== '' ? $companyName : $fromName;
	}

	$hasMailer = sr_cms_phpmailer_load();
	if ($hasMailer) {
		try {
			$mail = new PHPMailer\PHPMailer\PHPMailer(true);
			if (property_exists($mail, 'Timeout')) {
				$mail->Timeout = 8;
			}
			if ($smtpHost !== '') {
				$mail->isSMTP();
				$mail->Host = $smtpHost;
				$mail->Port = $smtpPort > 0 ? $smtpPort : 587;
				$mail->SMTPSecure = $smtpSecure !== '' ? $smtpSecure : 'tls';
				if ($smtpUser !== '') {
					$mail->SMTPAuth = true;
					$mail->Username = $smtpUser;
					$mail->Password = $smtpPass;
				}
			} else {
				$mail->isMail();
			}

			$mail->CharSet = 'UTF-8';
			$mail->setFrom($fromEmail, $fromName);
			$mail->addAddress($toEmail, $toName);
			if ($replyToEmail !== '' && filter_var($replyToEmail, FILTER_VALIDATE_EMAIL)) {
				$mail->addReplyTo($replyToEmail, $replyToName);
			}
			$mail->Subject = $subject;
			if ($htmlBody !== '') {
				$mail->msgHTML($htmlBody);
				$mail->AltBody = $textBody;
			} else {
				$mail->Body = $textBody;
				$mail->AltBody = $textBody;
			}
			return (bool) $mail->send();
		} catch (Throwable $e) {
			$msg = trim((string) $e->getMessage());
			$smtpPassMask = trim((string) sr_cms_setting_get('smtp_pass', ''));
			if ($smtpPassMask !== '') {
				$msg = str_replace($smtpPassMask, '***', $msg);
			}
			$msg = preg_replace('/\s+/', ' ', $msg);
			$GLOBALS['sr_cms_last_mail_error'] = $msg !== '' ? $msg : 'Email sending failed.';
			return false;
		}
	}

	$safeTo = $toEmail;
	$headers = [];
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/plain; charset=UTF-8';
	$headers[] = 'From: ' . ($fromName !== '' ? ($fromName . ' <' . $fromEmail . '>') : $fromEmail);
	if ($replyToEmail !== '' && filter_var($replyToEmail, FILTER_VALIDATE_EMAIL)) {
		$headers[] = 'Reply-To: ' . $replyToEmail;
	}
	$ok = @mail($safeTo, $subject, $textBody, implode("\r\n", $headers));
	if (!$ok) {
		$GLOBALS['sr_cms_last_mail_error'] = 'Server mail() failed.';
	}
	return $ok;
}

function sr_cms_setting_get(string $key, string $default = ''): string
{
	$db = sr_cms_db_try();
	if (!$db instanceof mysqli) {
		return $default;
	}
	$stmt = $db->prepare('SELECT v FROM cms_settings WHERE k = ? LIMIT 1');
	if (!$stmt) {
		return $default;
	}
	$stmt->bind_param('s', $key);
	$stmt->execute();
	$stmt->bind_result($v);
	$out = $default;
	if ($stmt->fetch()) {
		$out = (string)$v;
	}
	$stmt->close();
	return $out;
}

function sr_cms_testimonials_get(int $limit = 50, bool $activeOnly = true): array
{
	$db = sr_cms_db_try();
	if (!$db instanceof mysqli) {
		return [];
	}

	if ($limit < 1) {
		$limit = 1;
	}
	if ($limit > 200) {
		$limit = 200;
	}

	if ($activeOnly) {
		$sql = 'SELECT id, name, company, `quote`, image, rating, sort_order, is_active, created_at, updated_at
			FROM cms_testimonials
			WHERE is_active = 1
			ORDER BY sort_order ASC, updated_at DESC
			LIMIT ?';
		$stmt = $db->prepare($sql);
		if (!$stmt) {
			error_log('Failed preparing testimonials query (activeOnly=1): ' . $db->error);
			return [];
		}
		$stmt->bind_param('i', $limit);
	} else {
		$sql = 'SELECT id, name, company, `quote`, image, rating, sort_order, is_active, created_at, updated_at
			FROM cms_testimonials
			ORDER BY sort_order ASC, updated_at DESC
			LIMIT ?';
		$stmt = $db->prepare($sql);
		if (!$stmt) {
			error_log('Failed preparing testimonials query (activeOnly=0): ' . $db->error);
			return [];
		}
		$stmt->bind_param('i', $limit);
	}

	$out = [];
	$stmt->execute();
	$res = $stmt->get_result();
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$out[] = $row;
		}
		$res->free();
	} else {
		error_log('Failed loading testimonials result: ' . $db->error);
	}
	$stmt->close();

	return $out;
}

function sr_cms_page_get(string $slug): ?array
{
	$db = sr_cms_db_try();
	if (!$db instanceof mysqli) {
		return null;
	}
	$stmt = $db->prepare('SELECT slug, title, hero_title, hero_subtitle, banner_image, content FROM cms_pages WHERE slug = ? LIMIT 1');
	if (!$stmt) {
		return null;
	}
	$stmt->bind_param('s', $slug);
	$stmt->execute();
	$stmt->bind_result($rSlug, $title, $heroTitle, $heroSubtitle, $bannerImage, $content);
	$page = null;
	if ($stmt->fetch()) {
		$page = [
			'slug' => (string)$rSlug,
			'title' => (string)$title,
			'hero_title' => (string)$heroTitle,
			'hero_subtitle' => (string)$heroSubtitle,
			'banner_image' => (string)$bannerImage,
			'content' => (string)$content,
		];
	}
	$stmt->close();
	return $page;
}

/**
 * Normalized request path + common aliases so SEO rows saved as /contact-us or /about-us
 * still match pretty URLs (/contact, /about) and home variants.
 *
 * @return list<string>
 */
function sr_cms_seo_route_candidates(string $route): array
{
	$r = trim($route);
	if ($r === '') {
		$r = '/';
	}
	if ($r[0] !== '/') {
		$r = '/' . $r;
	}
	if ($r !== '/') {
		$r = rtrim($r, '/');
	}

	$out = [];
	$push = function (string $x) use (&$out): void {
		if ($x === '' || in_array($x, $out, true)) {
			return;
		}
		$out[] = $x;
	};

	$push($r);

	$homePaths = ['/', '/home', '/index.php'];
	if (in_array($r, $homePaths, true)) {
		foreach ($homePaths as $h) {
			$push($h);
		}
	}

	$equiv = [
		'/contact' => ['/contact-us', '/contact-us.php'],
		'/contact-us' => ['/contact'],
		'/contact-us.php' => ['/contact', '/contact-us'],
		'/about' => ['/about-us', '/about-us.php'],
		'/about-us' => ['/about'],
		'/about-us.php' => ['/about', '/about-us'],
		'/terms-of-use' => ['/terms', '/terms.php'],
		'/terms' => ['/terms-of-use'],
		'/terms.php' => ['/terms-of-use', '/terms'],
		'/privacy-policy' => ['/privacy-policy.php'],
		'/privacy-policy.php' => ['/privacy-policy'],
	];

	if (isset($equiv[$r])) {
		foreach ($equiv[$r] as $alt) {
			$push((string) $alt);
		}
	}
	foreach ($equiv as $canonical => $alts) {
		if (in_array($r, $alts, true)) {
			$push((string) $canonical);
			foreach ($alts as $a) {
				$push((string) $a);
			}
		}
	}

	return $out;
}

function sr_cms_seo_route_get(string $route): ?array
{
	$db = sr_cms_db_try();
	if (!$db instanceof mysqli) {
		return null;
	}

	$candidates = sr_cms_seo_route_candidates($route);
	if ($candidates === []) {
		return null;
	}

	$placeholders = implode(',', array_fill(0, count($candidates), '?'));
	$sql = 'SELECT route, title, description, keywords, og_image, noindex FROM cms_seo_routes WHERE route IN (' . $placeholders . ')';
	$stmt = $db->prepare($sql);
	if (!$stmt) {
		return null;
	}

	$types = str_repeat('s', count($candidates));
	$stmt->bind_param($types, ...$candidates);
	$stmt->execute();
	$res = $stmt->get_result();
	$rows = [];
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$rows[(string) ($row['route'] ?? '')] = $row;
		}
	}
	$stmt->close();

	foreach ($candidates as $c) {
		if (isset($rows[$c])) {
			$r = $rows[$c];
			return [
				'route' => (string) ($r['route'] ?? ''),
				'title' => (string) ($r['title'] ?? ''),
				'description' => (string) ($r['description'] ?? ''),
				'keywords' => (string) ($r['keywords'] ?? ''),
				'og_image' => (string) ($r['og_image'] ?? ''),
				'noindex' => (int) ($r['noindex'] ?? 0),
			];
		}
	}

	return null;
}

function sr_cms_slugify(string $s): string
{
	$s = strtolower(trim($s));
	$s = preg_replace('/[^a-z0-9\\s-]+/', '', $s) ?? $s;
	$s = preg_replace('/\\s+/', '-', $s) ?? $s;
	$s = preg_replace('/-+/', '-', $s) ?? $s;
	$s = trim($s, '-');
	return $s !== '' ? $s : 'item';
}
