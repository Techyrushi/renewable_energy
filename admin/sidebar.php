<?php
$sr_admin_current = basename((string)($_SERVER['PHP_SELF'] ?? 'index.php'));
$sr_admin_slug = isset($_GET['slug']) ? (string)$_GET['slug'] : '';
function sr_admin_nav_active(string $file, string $current): string
{
	return $file === $current ? 'active' : '';
}
function sr_admin_nav_active_slug(string $file, string $current, string $slug, string $wantedSlug): string
{
	return ($file === $current && $slug === $wantedSlug) ? 'active' : '';
}
?>
<aside class="page-sidebar">
	<div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
	<div class="main-sidebar" id="main-sidebar">
		<ul class="sidebar-menu" id="simple-bar">
			<li class="sidebar-main-title">
				<div>
					<h5 class="f-w-700 sidebar-title">Menu</h5>
				</div>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active('index.php', $sr_admin_current); ?>" href="index">
					<i data-feather="home"></i>
					<h6>Dashboard</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active_slug('pages.php', $sr_admin_current, $sr_admin_slug, 'home'); ?>" href="pages?slug=home">
					<i data-feather="sliders"></i>
					<h6>Home</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active_slug('pages.php', $sr_admin_current, $sr_admin_slug, 'about'); ?>" href="pages?slug=about">
					<i data-feather="users"></i>
					<h6>About Us</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active('projects.php', $sr_admin_current); ?>" href="projects">
					<i data-feather="grid"></i>
					<h6>Projects</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active('products.php', $sr_admin_current); ?>" href="products">
					<i data-feather="package"></i>
					<h6>Products</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active('services.php', $sr_admin_current); ?>" href="services">
					<i data-feather="tool"></i>
					<h6>Services</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active_slug('pages.php', $sr_admin_current, $sr_admin_slug, 'why-us'); ?>" href="pages?slug=why-us">
					<i data-feather="award"></i>
					<h6>Why Us</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active('blog-posts.php', $sr_admin_current); ?>" href="blog-posts">
					<i data-feather="file-text"></i>
					<h6>Blog</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active_slug('pages.php', $sr_admin_current, $sr_admin_slug, 'blog'); ?>" href="pages?slug=blog">	
					<i data-feather="help-circle"></i>
					<h6>FAQ</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active_slug('pages.php', $sr_admin_current, $sr_admin_slug, 'contact'); ?>" href="pages?slug=contact">
					<i data-feather="phone-call"></i>
					<h6>Contact Us</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active_slug('pages.php', $sr_admin_current, $sr_admin_slug, 'privacy-policy'); ?>" href="pages?slug=privacy-policy">
					<i data-feather="shield"></i>
					<h6>Privacy Policy</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active_slug('pages.php', $sr_admin_current, $sr_admin_slug, 'terms-of-use'); ?>" href="pages?slug=terms-of-use">
					<i data-feather="file"></i>
					<h6>Terms</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active('enquiries.php', $sr_admin_current); ?>" href="enquiries">
					<i data-feather="inbox"></i>
					<h6>Enquiries</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active('seo.php', $sr_admin_current); ?>" href="seo">
					<i data-feather="search"></i>
					<h6>SEO Settings</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active('settings.php', $sr_admin_current); ?>" href="settings">
					<i data-feather="settings"></i>
					<h6>Settings</h6>
				</a>
			</li>

			<!-- <li class="sidebar-main-title">
				<div>
					<h5 class="f-w-700 sidebar-title pt-3">Quick</h5>
				</div>
			</li> -->

			<!-- <li class="sidebar-list">
				<a class="sidebar-link" href="../" target="_blank" rel="noopener">
					<i data-feather="globe"></i>
					<h6>Open Website</h6>
				</a>
			</li> -->

			<li class="sidebar-list">
				<a class="sidebar-link" href="logout">
					<i data-feather="log-out"></i>
					<h6>Logout</h6>
				</a>
			</li>
		</ul>
	</div>
	<div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
</aside>
