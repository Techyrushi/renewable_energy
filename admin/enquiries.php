<?php include 'header.php'; ?>
<div class="page-body-wrapper">
	<?php include 'sidebar.php'; ?>
	<div class="page-body">
		<div class="container-fluid">
			<div class="page-title">
				<div class="row">
					<div class="col-sm-6 col-12">
						<h2>Enquiries</h2>
						<p class="mb-0 text-title-gray">Track incoming leads and customer requests.</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Enquiries</li>
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
								<h4 class="mb-0">Inbox</h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="../contact" target="_blank" rel="noopener">Open Contact Form</a>
									<a class="btn btn-primary" href="settings.php">Setup Storage</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="alert alert-warning mb-3" role="alert">
								<strong>Not connected:</strong> enquiries are currently delivered via email. Connect storage (database or file) to view enquiries inside admin.
							</div>
							<div class="table-responsive">
								<table class="table table-striped mb-0">
									<thead>
										<tr>
											<th>Date</th>
											<th>Name</th>
											<th>Phone</th>
											<th>Email</th>
											<th>City</th>
											<th>Type</th>
											<th>System Size</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td colspan="8" class="text-center text-title-gray py-4">No enquiries available yet.</td>
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

