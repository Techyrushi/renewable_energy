<?php
require_once __DIR__ . '/includes/cms.php';

header('Content-Type: text/plain; charset=UTF-8');

$scheme = (!empty($_SERVER['HTTPS']) && (string)$_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = trim((string)($_SERVER['HTTP_HOST'] ?? ''));
$host = $host !== '' ? $host : 'localhost';
$basePath = rtrim(str_replace('\\', '/', (string)dirname((string)($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
$siteBase = $scheme . '://' . $host;
if ($basePath !== '' && $basePath !== '/') {
	$siteBase .= $basePath;
}

$prefix = ($basePath !== '' && $basePath !== '/') ? $basePath : '';
$sitemapUrl = $siteBase . '/sitemap.xml';

echo "User-agent: *\n";
echo "Disallow: " . $prefix . "/admin/\n";
echo "Disallow: " . $prefix . "/includes/\n";
echo "Disallow: " . $prefix . "/vendor/\n";
echo "\n";
echo "Sitemap: " . $sitemapUrl . "\n";

