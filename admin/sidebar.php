<?php
$sr_admin_current = basename((string)($_SERVER['PHP_SELF'] ?? 'index.php'));
function sr_admin_nav_active(string $file, string $current): string
{
	return $file === $current ? 'active' : '';
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
				<a class="sidebar-link <?php echo sr_admin_nav_active('index.php', $sr_admin_current); ?>" href="index.php">
					<i data-feather="home"></i>
					<h6>Dashboard</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active('enquiries.php', $sr_admin_current); ?>" href="enquiries.php">
					<i data-feather="inbox"></i>
					<h6>Enquiries</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active('projects.php', $sr_admin_current); ?>" href="projects.php">
					<i data-feather="grid"></i>
					<h6>Projects</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active('blog-posts.php', $sr_admin_current); ?>" href="blog-posts.php">
					<i data-feather="file-text"></i>
					<h6>Blog / Resources</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link <?php echo sr_admin_nav_active('settings.php', $sr_admin_current); ?>" href="settings.php">
					<i data-feather="settings"></i>
					<h6>Settings</h6>
				</a>
			</li>

			<li class="sidebar-main-title">
				<div>
					<h5 class="f-w-700 sidebar-title pt-3">Quick</h5>
				</div>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link" href="../" target="_blank" rel="noopener">
					<i data-feather="globe"></i>
					<h6>Open Website</h6>
				</a>
			</li>

			<li class="sidebar-list">
				<a class="sidebar-link" href="logout.php">
					<i data-feather="log-out"></i>
					<h6>Logout</h6>
				</a>
			</li>
		</ul>
	</div>
	<div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
</aside>

