<?php
ob_start(function ($buffer) {
	return preg_replace_callback('/<img\\b[^>]*>/i', function ($m) {
		$tag = $m[0];
		if (stripos($tag, ' loading=') !== false) {
			return $tag;
		}
		if (preg_match('/class\\s*=\\s*([\'"])[^\\1>]*(logo-img|pbmit-main-logo)[^\\1>]*\\1/i', $tag)) {
			return $tag;
		}
		if (preg_match('/\\/\\>\\s*$/', $tag)) {
			$tag = preg_replace('/\\s*\\/\\>\\s*$/', ' loading="lazy" decoding="async" />', $tag, 1);
		} else {
			$tag = preg_replace('/\\s*>\\s*$/', ' loading="lazy" decoding="async">', $tag, 1);
		}
		return $tag;
	}, $buffer);
});
require_once __DIR__ . '/cms.php';
$sr_company_email = sr_cms_setting_get('company_email', 'info@shivanjalirenewables.com');
$sr_company_phone = sr_cms_setting_get('company_phone', '+91 8686313133');
$sr_company_phone_tel = sr_cms_setting_get('company_phone_tel', '+918686313133');
$sr_company_map_url = sr_cms_setting_get('company_map_url', 'https://maps.app.goo.gl/4r1P4qqp36AEcAce8');
$sr_company_map_label = sr_cms_setting_get('company_map_label', 'Shivanjali Renewables');
$sr_site_logo = sr_cms_setting_get('site_logo', 'images/Shivanjali_Logo.jpg');
$sr_site_favicon = sr_cms_setting_get('site_favicon', 'images/fevicon.png');

$sr_site_logo = (preg_match('/^images\\/[a-z0-9._\\/-]+\\.(png|jpe?g|webp)$/i', $sr_site_logo) === 1) ? $sr_site_logo : 'images/Shivanjali_Logo.jpg';
$sr_site_favicon = (preg_match('/^images\\/[a-z0-9._\\/-]+\\.(png|jpe?g|webp|ico)$/i', $sr_site_favicon) === 1) ? $sr_site_favicon : 'images/fevicon.png';

$sr_req_path = (string)(parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?? '/');
$sr_base_path = rtrim(str_replace('\\', '/', (string)dirname((string)($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
$sr_route = $sr_req_path;
if ($sr_base_path !== '' && $sr_base_path !== '/' && str_starts_with($sr_route, $sr_base_path)) {
	$sr_route = substr($sr_route, strlen($sr_base_path));
}
if ($sr_route === '') {
	$sr_route = '/';
}
if ($sr_route !== '/') {
	$sr_route = rtrim($sr_route, '/');
}

$sr_seo_row = sr_cms_seo_route_get($sr_route);
$sr_seo_title = is_array($sr_seo_row) ? trim((string)($sr_seo_row['title'] ?? '')) : '';
$sr_seo_desc = is_array($sr_seo_row) ? trim((string)($sr_seo_row['description'] ?? '')) : '';
$sr_seo_keywords = is_array($sr_seo_row) ? trim((string)($sr_seo_row['keywords'] ?? '')) : '';
$sr_seo_og_image = is_array($sr_seo_row) ? trim((string)($sr_seo_row['og_image'] ?? '')) : '';
$sr_seo_noindex = is_array($sr_seo_row) ? ((int)($sr_seo_row['noindex'] ?? 0) === 1) : false;

$sr_slug = ($sr_route === '/') ? 'home' : ltrim($sr_route, '/');
$sr_page_for_meta = sr_cms_page_get($sr_slug);
$sr_fallback_title = $sr_page_for_meta && trim((string)($sr_page_for_meta['title'] ?? '')) !== '' ? (string)$sr_page_for_meta['title'] : 'Shivanjali Renewables';
if ($sr_page_for_meta && trim((string)($sr_page_for_meta['hero_title'] ?? '')) !== '') {
	$sr_fallback_title = (string)$sr_page_for_meta['hero_title'] . ' • Shivanjali Renewables';
}
$sr_fallback_desc = '';
if ($sr_page_for_meta && trim((string)($sr_page_for_meta['hero_subtitle'] ?? '')) !== '') {
	$sr_fallback_desc = trim(strip_tags((string)$sr_page_for_meta['hero_subtitle']));
}
$sr_meta_title = $sr_seo_title !== '' ? $sr_seo_title : $sr_fallback_title;
$sr_meta_desc = $sr_seo_desc !== '' ? $sr_seo_desc : $sr_fallback_desc;
$sr_meta_desc = trim((string)$sr_meta_desc);
$sr_meta_robots = $sr_seo_noindex ? 'noindex, follow' : 'index, follow';
?>
<!doctype html>
<html class="no-js" lang="en">

<!-- Mirrored from solaar-demo.pbminfotech.com/html-demo/homepage-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 23 Mar 2026 03:54:12 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo htmlspecialchars($sr_meta_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="robots" content="<?php echo htmlspecialchars($sr_meta_robots, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="description" content="<?php echo htmlspecialchars($sr_meta_desc, ENT_QUOTES, 'UTF-8'); ?>">
    <?php if ($sr_seo_keywords !== '') { ?>
        <meta name="keywords" content="<?php echo htmlspecialchars($sr_seo_keywords, ENT_QUOTES, 'UTF-8'); ?>">
    <?php } ?>
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo htmlspecialchars($sr_meta_title, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($sr_meta_desc, ENT_QUOTES, 'UTF-8'); ?>">
    <?php if ($sr_seo_og_image !== '') { ?>
        <meta property="og:image" content="<?php echo htmlspecialchars($sr_seo_og_image, ENT_QUOTES, 'UTF-8'); ?>">
    <?php } ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <base href="/renewable/">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo htmlspecialchars($sr_site_favicon, ENT_QUOTES, 'UTF-8'); ?>">
    <!-- CSS
         ============================================ -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="css/fontawesome.css">
    <!-- Solaar Icon -->
    <link rel="stylesheet" href="fonts/pbmit-solaar-icon/pbmit_solaar.css">
    <!-- Base Icons -->
    <link rel="stylesheet" href="css/pbminfotech-base-icons.css">
    <!-- Themify Icons -->
    <link rel="stylesheet" href="css/themify-icons.css">
    <!-- Slick -->
    <link rel="stylesheet" href="css/swiper.min.css">
    <!-- Magnific -->
    <link rel="stylesheet" href="css/magnific-popup.css">
    <!-- AOS -->
    <link rel="stylesheet" href="css/aos.css">
    <!-- Shortcode CSS -->
    <link rel="stylesheet" href="css/shortcode.css">
    <!-- Base CSS -->
    <link rel="stylesheet" href="css/base.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="css/responsive.css">

</head>

<body>

    <!-- page wrapper -->
    <div class="page-wrapper" id="page">

        <!-- Header Main Area -->
        <header class="site-header pbmit-header-style-2 pbmit-header-sticky-yes" id="masthead">
            <div class="pbmit-pre-header-wrapper pbmit-bg-color-secondary pbmit-color-white">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between">
                        <div class="pbmit-pre-header-left">
                            <ul class="pbmit-contact-info">
                                <li><i class="pbmit-base-icon-headphones"></i> Looking for the right solar solution?<a
                                        href="contact"> Get a free quote</a></li>
                            </ul>
                        </div>
                        <div class="pbmit-pre-header-right">
                            <ul class="pbmit-contact-info">
                                <li>
                                    <a href="mailto:<?php echo htmlspecialchars($sr_company_email, ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="pbmit-base-icon-email"></i> <?php echo htmlspecialchars($sr_company_email, ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo htmlspecialchars($sr_company_map_url, ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class=" pbmit-base-icon-marker"></i><?php echo htmlspecialchars($sr_company_map_label, ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="tel:<?php echo htmlspecialchars($sr_company_phone_tel, ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="pbmit-base-icon-phone-call-1"></i> <?php echo htmlspecialchars($sr_company_phone, ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pbmit-header-overlay">
                <div class="pbmit-main-header-area pbmit-bg-color-secondary">
                    <div class="pbmit-header-content">
                        <div class="container-fluid">
                            <div class="pbmit-header-content d-flex align-items-center justify-content-between">
                                <div class="pbmit-header-menu-area d-flex align-items-center">
                                    <div class="pbmit-logo-area">
                                        <div class="site-branding">
                                            <h1 class="site-title">
                                                <a href="./" class="pbmit-logo-link">
                                                    <img class="logo-img" src="<?php echo htmlspecialchars($sr_site_logo, ENT_QUOTES, 'UTF-8'); ?>"
                                                        alt="Shivanjali Renewables">
                                                    <span class="pbmit-logo-tagline">Solar &amp; Renewable Energy</span>
                                                </a>
                                            </h1>
                                        </div>
                                    </div>
                                    <div class="pbmit-menuarea d-flex justify-content-between align-items-center">
                                        <div class="site-navigation">
                                            <nav class="main-menu pbmit-navbar">
                                                <div>
                                                    <ul id="pbmit-top-menu" class="navigation clearfix">
                                                        <li class="active"><a href="./">Home</a></li>
                                                        <li class="dropdown">
                                                            <a href="about">About Us</a>
                                                            <ul>
                                                                <li><a href="about#our-story">Our Story</a></li>
                                                                <li><a href="about#vision-mission">Vision &amp;
                                                                        Mission</a></li>
                                                                <li><a href="about#core-values">Core Values</a></li>
                                                                <li><a href="about#leadership-team">Leadership Team</a>
                                                                </li>
                                                                <li><a
                                                                        href="about#milestones">Achievements/Milestones</a>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                        <li class="dropdown">
                                                            <a href="services">Services</a>
                                                            <ul>
                                                                <li><a href="services/solar-installation">Solar Module &amp; System Installation</a></li>
                                                                <li><a href="services/operations-maintenance">Operations &amp; Maintenance</a></li>
                                                                <li><a href="services/energy-consulting">Energy Efficiency Consulting</a></li>
                                                                <li><a href="services/open-access-ppa">Open Access &amp; Power Purchase</a></li>
                                                            </ul>
                                                        </li>
                                                        <li class="dropdown">
                                                            <a href="products">Products</a>
                                                            <ul>
                                                                <li><a href="products#residential">Residential (3–19
                                                                        kW)</a></li>
                                                                <li><a href="products#commercial">Commercial (20–200
                                                                        kW)</a></li>
                                                                <li><a href="products#ht-consumer">HT Consumer (200–990
                                                                        kW)</a></li>
                                                                <li><a href="products#open-access">Open Access (1–20
                                                                        MW)</a></li>
                                                            </ul>
                                                        </li>
                                                        <li class="dropdown">
                                                            <a href="projects">Projects</a>
                                                            <ul>
                                                                <li><a href="projects#rooftop-solar">Rooftop Solar</a>
                                                                </li>
                                                                <li><a href="projects#solar-farming-parks">Solar Farming
                                                                        &amp; Parks</a></li>
                                                                <li><a href="projects#open-access-captive">Open Access
                                                                        Captive</a></li>
                                                            </ul>
                                                        </li>
                                                        <li><a href="why-us">Why Us</a></li>
                                                        <li class="dropdown">
                                                            <a href="blog">Blog / Resources</a>
                                                            <ul>
                                                                <li><a href="blog#solar-guides">Solar Guides</a></li>
                                                                <li><a href="blog#news">News</a></li>
                                                                <li><a href="blog#faqs">FAQs</a></li>
                                                            </ul>
                                                        </li>
                                                        <li><a href="contact">Contact Us</a></li>
                                                    </ul>
                                                </div>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                <div class="pbmit-right-box d-flex align-items-center">
                                    <div class="pbmit-button-box">
                                        <a href="contact" class="pbmit-btn">
                                            <span class="pbmit-button-text">Get Free Quote</span>
                                        </a>
                                    </div>
                                    <div class="pbmit-header-phone">
                                        <a href="tel:<?php echo htmlspecialchars($sr_company_phone_tel, ENT_QUOTES, 'UTF-8'); ?>" class="pbmit-header-phone-link"
                                            aria-label="Call Shivanjali Renewables">
                                            <i class="pbmit-base-icon-phone-call-1"></i>
                                        </a>
                                    </div>
                                    <div class="pbmit-burger-menu-wrapper">
                                        <div class="pbmit-mobile-menu-bg"></div>
                                        <button class="nav-menu-toggle" id="menu-toggle">
                                            <i class="pbmit-base-icon-menu-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pbmit-sticky-header"></div>
