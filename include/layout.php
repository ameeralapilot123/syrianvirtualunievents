<?php

if (!function_exists('renderSiteLayoutStart')) {
	function renderSiteLayoutStart($pageTitle = 'Svu City Events')
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		$isAdminLoggedIn = isset($_SESSION['admin_id']);
		?>
<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo htmlspecialchars($pageTitle); ?></title>
	<link rel="icon" type="image/png" href="assets/img/icony.png">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top site-navbar" id="siteNavbar">
	<div class="container">
		<div class="nav-shell d-flex align-items-center justify-content-between w-100">
			<a class="navbar-brand brand-mark d-inline-flex align-items-center gap-2" href="index.php">
				<span>
					<span class="d-block brand-title">Svu City Events</span>
					<span class="d-block brand-subtitle">Student Activity Directory</span>
				</span>
			</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="mainNavbar">
				<ul class="navbar-nav ms-auto mb-2 mb-lg-0 main-nav-links">
					<li class="nav-item">
						<a class="nav-link" href="index.php">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="events.php">Events</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="about.php">About</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="contact.php">Contact</a>
					</li>
					<li class="nav-item">
						<?php if ($isAdminLoggedIn): ?>
							<a class="nav-link nav-admin-link" href="admin/dashboard.php">Dashboard</a>
						<?php else: ?>
							<a class="nav-link nav-admin-link" href="admin/login.php">LogIn/SignUp</a>
						<?php endif; ?>
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
	<div class="container">
		<?php
	}

	function renderSiteLayoutEnd()
	{
		?>
	</div>
</main>

<footer class="site-footer border-top mt-auto py-4">
	<div class="container">
		<div class="footer-shell rounded-4 p-4 p-md-5">
			<div class="row g-4 align-items-center">
				<div class="col-lg-6">
					<p class="small text-uppercase fw-semibold mb-2 footer-kicker">Svu Events</p>
					<h6 class="mb-2 footer-title">Stay Connected to Campus Moments</h6>
					<p class="mb-0 text-secondary">Contact: svu@events.com | +963 963 369 963</p>
				</div>
				<div class="col-lg-3">
					<p class="small text-uppercase text-secondary mb-2">Quick Links</p>
					<div class="d-grid gap-1 footer-links">
						<a href="index.php">Home</a>
						<a href="events.php">Events</a>
						<a href="about.php">About</a>
						<a href="contact.php">Contact</a>
					</div>
				</div>
				<div class="col-lg-3 text-lg-end">
					<small class="d-block text-secondary mb-2">Built for students and organizers</small>
					<small>&copy; <?php echo date('Y'); ?> Svu Events. All rights reserved</small>
				</div>
			</div>
		</div>
	</div>
</footer>

<button type="button" class="btn btn-info scroll-top-btn" id="scrollTopButton" aria-label="Scroll to top" hidden>
	<i class="bi bi-arrow-up-short" aria-hidden="true"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
		<?php
	}
}
