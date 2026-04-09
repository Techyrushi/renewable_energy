<?php

declare(strict_types=1);

function sr_cms_db_try(): ?mysqli
{
	static $db = null;
	static $attempted = false;

	if ($attempted) {
		return $db;
	}
	$attempted = true;

	$host = (string)(getenv('SR_DB_HOST') ?: 'localhost');
	$user = (string)(getenv('SR_DB_USER') ?: 'root');
	$pass = (string)(getenv('SR_DB_PASS') ?: '');
	$name = (string)(getenv('SR_DB_NAME') ?: 'renewable');

	mysqli_report(MYSQLI_REPORT_OFF);
	$conn = @mysqli_connect($host, $user, $pass, $name);
	if (!$conn instanceof mysqli) {
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

function sr_cms_seo_route_get(string $route): ?array
{
	$db = sr_cms_db_try();
	if (!$db instanceof mysqli) {
		return null;
	}
	$stmt = $db->prepare('SELECT route, title, description, keywords, og_image, noindex FROM cms_seo_routes WHERE route=? LIMIT 1');
	if (!$stmt) {
		return null;
	}
	$stmt->bind_param('s', $route);
	$stmt->execute();
	$stmt->bind_result($rRoute, $title, $desc, $keywords, $ogImage, $noindex);
	$row = null;
	if ($stmt->fetch()) {
		$row = [
			'route' => (string)$rRoute,
			'title' => (string)$title,
			'description' => (string)$desc,
			'keywords' => (string)$keywords,
			'og_image' => (string)$ogImage,
			'noindex' => (int)$noindex,
		];
	}
	$stmt->close();
	return $row;
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
