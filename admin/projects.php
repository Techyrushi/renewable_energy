<?php include 'header.php'; ?>
<div class="page-body-wrapper">
	<?php include 'sidebar.php'; ?>
	<div class="page-body">
		<div class="container-fluid">
			<div class="page-title">
				<div class="row">
					<div class="col-sm-6 col-12">
						<h2>Projects</h2>
						<p class="mb-0 text-title-gray">Manage featured projects and portfolio visibility.</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Projects</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row g-4">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
								<h4 class="mb-0">Featured Gallery</h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="../projects#gallery" target="_blank" rel="noopener">Open Gallery</a>
									<a class="btn btn-primary" href="../projects" target="_blank" rel="noopener">Open Projects Page</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="alert alert-info mb-3" role="alert">
								Projects currently render from static placeholders in the frontend. Share final project data (name, location, capacity, photo) to populate the production gallery.
							</div>
							<div class="row g-3">
								<div class="col-lg-4 col-md-6">
									<div class="border rounded-3 p-3 bg-light h-100">
										<div class="d-flex align-items-center justify-content-between">
											<div class="fw-bold">Rooftop Solar</div>
											<span class="badge bg-primary">3</span>
										</div>
										<div class="text-title-gray mt-2">Warehouses, factories, commercial roofs.</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-6">
									<div class="border rounded-3 p-3 bg-light h-100">
										<div class="d-flex align-items-center justify-content-between">
											<div class="fw-bold">Open Access</div>
											<span class="badge bg-success">2</span>
										</div>
										<div class="text-title-gray mt-2">Captive, group captive, PPA support.</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-12">
									<div class="border rounded-3 p-3 bg-light h-100">
										<div class="d-flex align-items-center justify-content-between">
											<div class="fw-bold">Solar Parks</div>
											<span class="badge bg-warning text-dark">1</span>
										</div>
										<div class="text-title-gray mt-2">Utility scale development and EPC.</div>
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

