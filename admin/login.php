<?php
session_start();
require_once '../db.php';

if (isset($_SESSION['admin_id'])) {
	header('Location: dashboard.php');
	exit;
}

$errorMessage = '';
$successMessage = '';
$username = '';

// Check if redirected from registration page
if (isset($_GET['registered']) && $_GET['registered'] === '1') {
	$successMessage = 'Account created successfully. You can sign in now.';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = trim($_POST['username'] ?? '');
	$password = trim($_POST['password'] ?? '');

	if ($username === '' || $password === '') {
		$errorMessage = 'Please enter both username and password.';
	} else {
		$stmt = $conn->prepare('SELECT id, username, password FROM users WHERE username = ? LIMIT 1');

		if ($stmt) {
			$stmt->bind_param('s', $username);
			$stmt->execute();
			$result = $stmt->get_result();
			$user = $result ? $result->fetch_assoc() : null;
			$stmt->close();

			if ($user && $user['password'] === $password) {
				$_SESSION['admin_id'] = (int)$user['id'];
				$_SESSION['admin_username'] = $user['username'];
				header('Location: dashboard.php');
				exit;
			}
		}

		$errorMessage = 'Invalid username or password.';
	}
}
?>
<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Login - City Events</title>
	<link rel="icon" type="image/png" href="../assets/img/icony.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
<main class="content-wrap auth-page">
	<div class="container auth-container">
		<div class="row g-0 auth-shell shadow-sm overflow-hidden rounded-4">
			<div class="col-lg-5">
				<section class="auth-aside h-100 p-4 p-md-5">
					<p class="small text-uppercase fw-semibold auth-eyebrow mb-2">Admin Access</p>
					<h1 class="h2 mb-3">Welcome Back</h1>
					<p class="text-secondary mb-4">Sign in to review submissions, update events, and keep listings accurate for students.</p>
					<ul class="list-unstyled d-grid gap-3 mb-0 auth-feature-list">
						<li><i class="bi bi-check-circle me-2" aria-hidden="true"></i>Manage all event records in one place</li>
						<li><i class="bi bi-check-circle me-2" aria-hidden="true"></i>Keep dates and locations current</li>
						<li><i class="bi bi-check-circle me-2" aria-hidden="true"></i>Publish updates quickly for students</li>
					</ul>
				</section>
			</div>
			<div class="col-lg-7">
				<section class="auth-form-panel h-100 p-4 p-md-5">
					<div class="mb-4">
						<h2 class="h3 mb-1">Sign In</h2>
						<p class="text-secondary mb-0">Use your admin credentials to access the dashboard.</p>
					</div>

					<?php if ($errorMessage !== ''): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo htmlspecialchars($errorMessage); ?>
						</div>
					<?php endif; ?>

					<?php if ($successMessage !== ''): ?>
						<div class="alert alert-success" role="status" aria-live="polite">
							<?php echo htmlspecialchars($successMessage); ?>
						</div>
					<?php endif; ?>

					<form method="post" action="login.php" class="d-grid gap-3">
						<div>
							<label for="username" class="form-label">Username</label>
							<input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" autocomplete="username" required>
							<div class="invalid-feedback">Username is required.</div>
						</div>

						<div>
							<label for="password" class="form-label">Password</label>
							<input type="password" id="password" name="password" class="form-control" autocomplete="current-password" required>
							<div class="invalid-feedback">Password is required.</div>
						</div>

						<button type="submit" class="btn btn-info auth-submit-btn">
							<i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Login
						</button>
					</form>

					<div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mt-4 auth-links">
						<p class="mb-0 text-secondary">Need an account?</p>
						<div class="d-flex gap-2">
							<a href="signup.php" class="btn btn-sm btn-outline-info">Sign Up</a>
							<a href="../index.php" class="btn btn-sm btn-outline-secondary">Back to Site</a>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>
