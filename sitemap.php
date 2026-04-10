<?php
require_once __DIR__ . '/includes/cms.php';

header('Content-Type: application/xml; charset=UTF-8');

$scheme = (!empty($_SERVER['HTTPS']) && (string)$_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = trim((string)($_SERVER['HTTP_HOST'] ?? ''));
$host = $host !== '' ? $host : 'localhost';
$basePath = rtrim(str_replace('\\', '/', (string)dirname((string)($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
$siteBase = $scheme . '://' . $host;
if ($basePath !== '' && $basePath !== '/') {
	$siteBase .= $basePath;
}

function sr_sitemap_iso(?string $dt): string
{
	$dt = $dt ? trim($dt) : '';
	if ($dt === '') {
		return gmdate('c');
	}
	$ts = strtotime($dt);
	if ($ts === false) {
		return gmdate('c');
	}
	return gmdate('c', $ts);
}

function sr_sitemap_escape(string $s): string
{
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$urls = [];
$staticRoutes = [
	'/' => ['slug' => 'home', 'changefreq' => 'daily', 'priority' => '1.0'],
	'/about' => ['slug' => 'about', 'changefreq' => 'monthly', 'priority' => '0.8'],
	'/services' => ['slug' => 'services', 'changefreq' => 'weekly', 'priority' => '0.8'],
	'/products' => ['slug' => 'products', 'changefreq' => 'weekly', 'priority' => '0.8'],
	'/projects' => ['slug' => 'projects', 'changefreq' => 'weekly', 'priority' => '0.8'],
	'/why-us' => ['slug' => 'why-us', 'changefreq' => 'monthly', 'priority' => '0.7'],
	'/blog' => ['slug' => 'blog', 'changefreq' => 'weekly', 'priority' => '0.7'],
	'/contact' => ['slug' => 'contact', 'changefreq' => 'monthly', 'priority' => '0.6'],
	'/privacy-policy' => ['slug' => 'privacy-policy', 'changefreq' => 'yearly', 'priority' => '0.3'],
	'/terms-of-use' => ['slug' => 'terms-of-use', 'changefreq' => 'yearly', 'priority' => '0.3'],
];

$pageUpdated = [];
$db = sr_cms_db_try();
if ($db instanceof mysqli) {
	$slugs = array_values(array_unique(array_map(static function ($r) {
		return (string)($r['slug'] ?? '');
	}, $staticRoutes)));
	$slugs = array_values(array_filter($slugs, static fn($s) => $s !== ''));
	if ($slugs) {
		$in = implode(',', array_fill(0, count($slugs), '?'));
		$types = str_repeat('s', count($slugs));
		$sql = 'SELECT slug, updated_at FROM cms_pages WHERE slug IN (' . $in . ')';
		$stmt = $db->prepare($sql);
		if ($stmt) {
			$stmt->bind_param($types, ...$slugs);
			$stmt->execute();
			$res = $stmt->get_result();
			if ($res) {
				while ($row = $res->fetch_assoc()) {
					$pageUpdated[(string)($row['slug'] ?? '')] = (string)($row['updated_at'] ?? '');
				}
				$res->free();
			}
			$stmt->close();
		}
	}
}

foreach ($staticRoutes as $route => $cfg) {
	$slug = (string)($cfg['slug'] ?? '');
	$lastmod = isset($pageUpdated[$slug]) ? sr_sitemap_iso($pageUpdated[$slug]) : gmdate('c');
	$urls[] = [
		'loc' => $siteBase . $route,
		'lastmod' => $lastmod,
		'changefreq' => (string)($cfg['changefreq'] ?? 'weekly'),
		'priority' => (string)($cfg['priority'] ?? '0.5'),
	];
}

if ($db instanceof mysqli) {
	$res = $db->query("SELECT slug, updated_at FROM cms_services WHERE published=1 AND slug<>'' ORDER BY sort_order ASC, updated_at DESC");
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$slug = trim((string)($row['slug'] ?? ''));
			if ($slug === '') {
				continue;
			}
			$urls[] = [
				'loc' => $siteBase . '/services/' . rawurlencode($slug),
				'lastmod' => sr_sitemap_iso((string)($row['updated_at'] ?? '')),
				'changefreq' => 'monthly',
				'priority' => '0.6',
			];
		}
		$res->free();
	}

	$res = $db->query("SELECT slug, updated_at FROM cms_products WHERE published=1 AND slug<>'' ORDER BY sort_order ASC, updated_at DESC");
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$slug = trim((string)($row['slug'] ?? ''));
			if ($slug === '') {
				continue;
			}
			$urls[] = [
				'loc' => $siteBase . '/products/' . rawurlencode($slug),
				'lastmod' => sr_sitemap_iso((string)($row['updated_at'] ?? '')),
				'changefreq' => 'monthly',
				'priority' => '0.6',
			];
		}
		$res->free();
	}

	$res = $db->query("SELECT slug, updated_at FROM cms_projects WHERE slug IS NOT NULL AND slug<>'' ORDER BY sort_order ASC, updated_at DESC");
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$slug = trim((string)($row['slug'] ?? ''));
			if ($slug === '') {
				continue;
			}
			$urls[] = [
				'loc' => $siteBase . '/projects/' . rawurlencode($slug),
				'lastmod' => sr_sitemap_iso((string)($row['updated_at'] ?? '')),
				'changefreq' => 'monthly',
				'priority' => '0.6',
			];
		}
		$res->free();
	}

	$res = $db->query("SELECT slug, COALESCE(published_at, updated_at) AS lastmod FROM cms_blog_posts WHERE published=1 AND slug<>'' ORDER BY published_at DESC, updated_at DESC");
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$slug = trim((string)($row['slug'] ?? ''));
			if ($slug === '') {
				continue;
			}
			$urls[] = [
				'loc' => $siteBase . '/blog/' . rawurlencode($slug),
				'lastmod' => sr_sitemap_iso((string)($row['lastmod'] ?? '')),
				'changefreq' => 'monthly',
				'priority' => '0.5',
			];
		}
		$res->free();
	}
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $u) { ?>
	<url>
		<loc><?php echo sr_sitemap_escape((string)$u['loc']); ?></loc>
		<lastmod><?php echo sr_sitemap_escape((string)$u['lastmod']); ?></lastmod>
		<changefreq><?php echo sr_sitemap_escape((string)$u['changefreq']); ?></changefreq>
		<priority><?php echo sr_sitemap_escape((string)$u['priority']); ?></priority>
	</url>
<?php } ?>
</urlset>

