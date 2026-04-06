<?php include 'header.php'; ?>
<div class="page-body-wrapper">
	<?php include 'sidebar.php'; ?>
	<div class="page-body">
		<div class="container-fluid">
			<div class="page-title">
				<div class="row">
					<div class="col-sm-6 col-12">
						<h2>Dashboard</h2>
						<p class="mb-0 text-title-gray">Overview for Shivanjali Renewables operations.</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Dashboard</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row g-4">
				<div class="col-lg-4 col-md-6">
					<div class="card">
						<div class="card-body">
							<div class="d-flex align-items-start justify-content-between">
								<div>
									<h5 class="mb-1">New Enquiries</h5>
									<p class="mb-0 text-title-gray">Last 7 days</p>
								</div>
								<div class="badge rounded-pill bg-light-primary text-primary">Tracking</div>
							</div>
							<div class="mt-3 d-flex align-items-end justify-content-between">
								<div>
									<h2 class="mb-0">—</h2>
									<p class="mb-0 text-title-gray">Connect to enquiries storage</p>
								</div>
								<a class="btn btn-primary" href="enquiries.php">View</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4 col-md-6">
					<div class="card">
						<div class="card-body">
							<div class="d-flex align-items-start justify-content-between">
								<div>
									<h5 class="mb-1">Projects</h5>
									<p class="mb-0 text-title-gray">Featured gallery items</p>
								</div>
								<div class="badge rounded-pill bg-light-success text-success">Portfolio</div>
							</div>
							<div class="mt-3 d-flex align-items-end justify-content-between">
								<div>
									<h2 class="mb-0">6</h2>
									<p class="mb-0 text-title-gray">Current placeholders</p>
								</div>
								<a class="btn btn-primary" href="projects.php">Manage</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4 col-md-12">
					<div class="card">
						<div class="card-body">
							<div class="d-flex align-items-start justify-content-between">
								<div>
									<h5 class="mb-1">Blog / Resources</h5>
									<p class="mb-0 text-title-gray">Published posts</p>
								</div>
								<div class="badge rounded-pill bg-light-warning text-warning">Content</div>
							</div>
							<div class="mt-3 d-flex align-items-end justify-content-between">
								<div>
									<h2 class="mb-0">—</h2>
									<p class="mb-0 text-title-gray">Static posts map</p>
								</div>
								<a class="btn btn-primary" href="blog-posts.php">View</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
								<h4 class="mb-0">Quick Actions</h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="../contact" target="_blank" rel="noopener">Open Contact Page</a>
									<a class="btn btn-outline-primary" href="../projects" target="_blank" rel="noopener">Open Projects Page</a>
									<a class="btn btn-outline-primary" href="../blog" target="_blank" rel="noopener">Open Blog Page</a>
									<a class="btn btn-primary" href="settings.php">Admin Settings</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="row g-3">
								<div class="col-lg-4">
									<div class="p-3 rounded-3 border bg-light">
										<div class="d-flex align-items-center gap-2 mb-2">
											<i data-feather="mail"></i>
											<h6 class="mb-0">Primary Email</h6>
										</div>
										<div class="fw-bold">info@shivanjalirenewables.com</div>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="p-3 rounded-3 border bg-light">
										<div class="d-flex align-items-center gap-2 mb-2">
											<i data-feather="phone"></i>
											<h6 class="mb-0">Primary Phone</h6>
										</div>
										<div class="fw-bold">+91 8686 313 133</div>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="p-3 rounded-3 border bg-light">
										<div class="d-flex align-items-center gap-2 mb-2">
											<i data-feather="map-pin"></i>
											<h6 class="mb-0">Office</h6>
										</div>
										<div class="fw-bold">Nashik, Maharashtra</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include 'footer.php'; ?>

