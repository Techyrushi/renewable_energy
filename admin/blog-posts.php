<?php include 'header.php'; ?>
<div class="page-body-wrapper">
	<?php include 'sidebar.php'; ?>
	<div class="page-body">
		<div class="container-fluid">
			<div class="page-title">
				<div class="row">
					<div class="col-sm-6 col-12">
						<h2>Blog / Resources</h2>
						<p class="mb-0 text-title-gray">Manage blog content and quick links.</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Blog</li>
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
								<h4 class="mb-0">Posts</h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="../blog" target="_blank" rel="noopener">Open Blog Page</a>
									<a class="btn btn-outline-primary" href="../blog#faqs" target="_blank" rel="noopener">Open Blog FAQs</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="alert alert-info mb-3" role="alert">
								Posts are currently defined in the frontend templates. For production CMS-style editing, connect a database and implement CRUD screens.
							</div>
							<div class="table-responsive">
								<table class="table table-striped mb-0">
									<thead>
										<tr>
											<th>Title</th>
											<th>Category</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Solar Rooftop Basics: What to Know Before You Buy</td>
											<td>Solar Guides</td>
											<td><span class="badge bg-success">Published</span></td>
											<td><a class="btn btn-sm btn-primary" href="../blog/solar-rooftop-basics" target="_blank" rel="noopener">View</a></td>
										</tr>
										<tr>
											<td>Net Metering in Maharashtra: Process &amp; Timeline</td>
											<td>Solar Guides</td>
											<td><span class="badge bg-success">Published</span></td>
											<td><a class="btn btn-sm btn-primary" href="../blog/net-metering-maharashtra" target="_blank" rel="noopener">View</a></td>
										</tr>
										<tr>
											<td>Open Access Solar: Captive vs Group Captive Explained</td>
											<td>Business</td>
											<td><span class="badge bg-success">Published</span></td>
											<td><a class="btn btn-sm btn-primary" href="../blog/open-access-captive-vs-group-captive" target="_blank" rel="noopener">View</a></td>
										</tr>
										<tr>
											<td>O&amp;M Checklist: Keep Your Plant Performing</td>
											<td>Maintenance</td>
											<td><span class="badge bg-success">Published</span></td>
											<td><a class="btn btn-sm btn-primary" href="../blog/om-checklist" target="_blank" rel="noopener">View</a></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include 'footer.php'; ?>

