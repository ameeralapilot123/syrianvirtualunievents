<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
	header('Location: login.php');
	exit;
}

require_once '../db.php';

$events = [];
$categories = [];
$eventsSql = '
	SELECT id, title, event_date, category, location
	FROM events
	ORDER BY event_date DESC, id DESC
';

// Fetch events for dashboard
$eventsResult = $conn->query($eventsSql);
if ($eventsResult) {
	while ($row = $eventsResult->fetch_assoc()) {
		$events[] = $row;
		$rawCategory = trim((string)($row['category'] ?? ''));
		$normalizedCategory = $rawCategory !== '' ? $rawCategory : 'General';
		$categories[$normalizedCategory] = true;
	}
}

$categoryList = array_keys($categories);
sort($categoryList, SORT_NATURAL | SORT_FLAG_CASE);
?>
<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Dashboard - City Events</title>
	<link rel="icon" type="image/png" href="../assets/img/icony.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top site-navbar admin-navbar" id="siteNavbar">
	<div class="container">
		<div class="nav-shell d-flex align-items-center justify-content-between w-100">
			<a class="navbar-brand brand-mark d-inline-flex align-items-center gap-2" href="dashboard.php">
				<span class="brand-badge" aria-hidden="true"><i class="bi bi-grid-1x2-fill"></i></span>
				<span>
					<span class="d-block brand-title">Admin Panel</span>
					<span class="d-block brand-subtitle">Event Management</span>
				</span>
			</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="adminNavbar">
				<ul class="navbar-nav ms-auto mb-2 mb-lg-0 main-nav-links">
					<li class="nav-item">
						<span class="nav-link admin-user-pill"><i class="bi bi-person-circle me-1" aria-hidden="true"></i><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></span>
					</li>
					<li class="nav-item">
						<a class="nav-link nav-admin-link" href="../index.php"><i class="bi bi-eye me-1" aria-hidden="true"></i>View Site</a>
					</li>
					<li class="nav-item">
						<a class="nav-link nav-admin-link admin-logout-link" href="logout.php"><i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>Logout</a>
					</li>
				</ul>

				<button class="btn btn-outline-light ms-lg-3 theme-toggle" id="themeToggle" type="button" aria-label="Toggle dark mode" aria-pressed="true">
					<i class="bi bi-moon-stars-fill me-2" id="themeToggleIcon"></i>
					<span id="themeToggleText">Dark</span>
				</button>
			</div>
		</div>
	</div>
</nav>

<main class="content-wrap">
	<!-- Dashboard content section -->
	<div class="container-fluid px-3 px-lg-4">
		<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
			<div>
				<h1 class="h3 mb-1">Events Dashboard</h1>
				<p class="text-secondary mb-0">Manage all city events from one place.</p>
			</div>
			<a href="add_event.php" class="btn btn-info">
				<i class="bi bi-plus-circle me-2"></i>Add New Event
			</a>
		</div>

		<div class="card border-0 shadow-sm dashboard-shell mb-4">
			<div class="card-body p-3 p-md-4 border-bottom dashboard-toolbar">
				<div class="row g-3 align-items-center">
					<div class="col-lg-5">
						<label for="dashboardSearchInput" class="form-label small text-secondary mb-1">Search by title or location</label>
						<div class="input-group">
							<span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search" aria-hidden="true"></i></span>
							<input type="search" id="dashboardSearchInput" class="form-control border-start-0" placeholder="Search events..." aria-label="Search events">
						</div>
					</div>
					<div class="col-lg-7">
						<p class="small text-secondary mb-2">Quick category filters</p>
						<div class="d-flex flex-wrap gap-2">
							<button type="button" class="btn btn-sm btn-outline-info js-dashboard-filter is-active" data-category="all" aria-pressed="true">All</button>
							<?php foreach ($categoryList as $category): ?>
								<?php $categoryValue = strtolower($category); ?>
								<button type="button" class="btn btn-sm btn-outline-info js-dashboard-filter" data-category="<?php echo htmlspecialchars($categoryValue); ?>" aria-pressed="false"><?php echo htmlspecialchars($category); ?></button>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top">
					<p class="mb-0 text-secondary small">Showing <span id="dashboardCountLabel"><?php echo count($events); ?></span> events</p>
					<p class="mb-0 text-secondary small d-none d-md-block">Tip: use filters to focus on one category faster.</p>
				</div>
			</div>

			<div class="card-body p-0">
				<?php if (!empty($events)): ?>
					<div class="table-responsive d-none d-lg-block">
						<table class="table table-hover align-middle mb-0 dashboard-table" id="dashboardEventTable">
							<thead>
								<tr>
									<th scope="col">Event</th>
									<th scope="col">Date</th>
									<th scope="col">Category</th>
									<th scope="col">Location</th>
									<th scope="col" class="text-end">Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($events as $event): ?>
									<?php
									$eventTitle = trim((string)($event['title'] ?? 'Untitled Event'));
									$eventDate = !empty($event['event_date']) ? date('M d, Y', strtotime($event['event_date'])) : 'Date TBD';
									$eventCategory = trim((string)($event['category'] ?? '')) !== '' ? trim((string)$event['category']) : 'General';
									$eventLocation = trim((string)($event['location'] ?? '')) !== '' ? trim((string)$event['location']) : 'City Venue';
									?>
									<tr class="dashboard-item" data-category="<?php echo htmlspecialchars(strtolower($eventCategory)); ?>" data-search="<?php echo htmlspecialchars(strtolower($eventTitle . ' ' . $eventLocation)); ?>">
										<td>
											<p class="fw-semibold mb-0"><?php echo htmlspecialchars($eventTitle); ?></p>
										</td>
										<td><span class="badge rounded-pill dashboard-chip"><?php echo htmlspecialchars($eventDate); ?></span></td>
										<td><span class="badge rounded-pill text-bg-info"><?php echo htmlspecialchars($eventCategory); ?></span></td>
										<td><i class="bi bi-geo-alt me-1" aria-hidden="true"></i><?php echo htmlspecialchars($eventLocation); ?></td>
										<td class="text-end">
											<div class="btn-group btn-group-sm" role="group" aria-label="Event actions">
												<a href="edit_event.php?id=<?php echo (int)$event['id']; ?>" class="btn btn-outline-info"><i class="bi bi-pencil-square me-1" aria-hidden="true"></i>Edit</a>
												<a href="delete_event.php?id=<?php echo (int)$event['id']; ?>" class="btn btn-outline-danger js-delete-event" data-event-title="<?php echo htmlspecialchars($eventTitle); ?>"><i class="bi bi-trash3 me-1" aria-hidden="true"></i>Delete</a>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

					<div class="d-grid gap-3 p-3 p-md-4 d-lg-none" id="dashboardCardList">
						<?php foreach ($events as $event): ?>
							<?php
							$eventTitle = trim((string)($event['title'] ?? 'Untitled Event'));
							$eventDate = !empty($event['event_date']) ? date('M d, Y', strtotime($event['event_date'])) : 'Date TBD';
							$eventCategory = trim((string)($event['category'] ?? '')) !== '' ? trim((string)$event['category']) : 'General';
							$eventLocation = trim((string)($event['location'] ?? '')) !== '' ? trim((string)$event['location']) : 'City Venue';
							?>
							<article class="dashboard-event-card dashboard-item" data-category="<?php echo htmlspecialchars(strtolower($eventCategory)); ?>" data-search="<?php echo htmlspecialchars(strtolower($eventTitle . ' ' . $eventLocation)); ?>">
								<div class="d-flex justify-content-between gap-2 mb-2">
									<p class="fw-semibold mb-0"><?php echo htmlspecialchars($eventTitle); ?></p>
									<span class="badge rounded-pill text-bg-info"><?php echo htmlspecialchars($eventCategory); ?></span>
								</div>
								<p class="small text-secondary mb-2"><i class="bi bi-calendar-event me-1" aria-hidden="true"></i><?php echo htmlspecialchars($eventDate); ?></p>
								<p class="small mb-3"><i class="bi bi-geo-alt me-1" aria-hidden="true"></i><?php echo htmlspecialchars($eventLocation); ?></p>
								<div class="d-flex gap-2">
									<a href="edit_event.php?id=<?php echo (int)$event['id']; ?>" class="btn btn-sm btn-outline-info flex-fill"><i class="bi bi-pencil-square me-1" aria-hidden="true"></i>Edit</a>
									<a href="delete_event.php?id=<?php echo (int)$event['id']; ?>" class="btn btn-sm btn-outline-danger flex-fill js-delete-event" data-event-title="<?php echo htmlspecialchars($eventTitle); ?>"><i class="bi bi-trash3 me-1" aria-hidden="true"></i>Delete</a>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<div class="text-center py-5 px-3 dashboard-empty-state">
						<i class="bi bi-calendar2-x d-block mb-3" aria-hidden="true"></i>
						<h2 class="h5 mb-2">No events have been added yet</h2>
						<p class="text-secondary mb-3">Start your dashboard by creating the first event listing for students.</p>
						<a href="add_event.php" class="btn btn-info"><i class="bi bi-plus-circle me-2" aria-hidden="true"></i>Create First Event</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</main>

<button type="button" class="btn btn-info scroll-top-btn" id="scrollTopButton" aria-label="Scroll to top" hidden>
	<i class="bi bi-arrow-up-short" aria-hidden="true"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>
