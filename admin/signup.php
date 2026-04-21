<?php
session_start();
require_once '../db.php';

if (isset($_SESSION['admin_id'])) {
	header('Location: dashboard.php');
	exit;
}

// Initialize variables for form handling
$errorMessage = '';
$username = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = trim($_POST['username'] ?? '');
	$password = trim($_POST['password'] ?? '');
	$confirmPassword = trim($_POST['confirm_password'] ?? '');

	if ($username === '' || $password === '' || $confirmPassword === '') {
		$errorMessage = 'Please fill in all fields.';
	} elseif ($password !== $confirmPassword) {
		$errorMessage = 'Passwords do not match.';
	} else {
		$checkStmt = $conn->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
		if ($checkStmt) {
			$checkStmt->bind_param('s', $username);
			$checkStmt->execute();
			$checkResult = $checkStmt->get_result();
			$existingUser = $checkResult ? $checkResult->fetch_assoc() : null;
			$checkStmt->close();

			if ($existingUser) {
				$errorMessage = 'Username already exists. Please choose another one.';
			}
		}

		if ($errorMessage === '') {
			$insertStmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
			if ($insertStmt) {
				$insertStmt->bind_param('ss', $username, $password);
				if ($insertStmt->execute()) {
					$insertStmt->close();
					header('Location: login.php?registered=1');
					exit;
				}
				$insertStmt->close();
			}

			$errorMessage = 'Unable to create account right now.';
		}
	}
}
?>

<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Sign Up - City Events</title>
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
					<p class="small text-uppercase fw-semibold auth-eyebrow mb-2">Admin Registration</p>
					<h1 class="h2 mb-3">Create Your Admin Profile</h1>
					<p class="text-secondary mb-4">Set up your account to start publishing and moderating events from the management dashboard.</p>
					<ul class="list-unstyled d-grid gap-3 mb-0 auth-feature-list">
						<li><i class="bi bi-check-circle me-2" aria-hidden="true"></i>Access event editing and publishing tools</li>
						<li><i class="bi bi-check-circle me-2" aria-hidden="true"></i>Coordinate updates with your team</li>
						<li><i class="bi bi-check-circle me-2" aria-hidden="true"></i>Keep listings clear and reliable</li>
					</ul>
				</section>
			</div>
			<div class="col-lg-7">
				<section class="auth-form-panel h-100 p-4 p-md-5">
					<div class="mb-4">
						<h2 class="h3 mb-1">Sign Up</h2>
						<p class="text-secondary mb-0">Create admin credentials to continue.</p>
					</div>

					<?php if ($errorMessage !== ''): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo htmlspecialchars($errorMessage); ?>
						</div>
					<?php endif; ?>

					<form method="post" action="signup.php" class="d-grid gap-3">
						<div>
							<label for="username" class="form-label">Username</label>
							<input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" autocomplete="username" required>
							<div class="invalid-feedback">Username is required.</div>
						</div>

						<div>
							<label for="password" class="form-label">Password</label>
							<input type="password" id="password" name="password" class="form-control" autocomplete="new-password" required>
							<div class="invalid-feedback">Password is required.</div>
						</div>

						<div>
							<label for="confirm_password" class="form-label">Confirm Password</label>
							<input type="password" id="confirm_password" name="confirm_password" class="form-control" autocomplete="new-password" required>
							<div class="invalid-feedback">Please confirm your password.</div>
						</div>

						<button type="submit" class="btn btn-info auth-submit-btn">
							<i class="bi bi-person-plus me-2" aria-hidden="true"></i>Sign Up
						</button>
					</form>

					<div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mt-4 auth-links">
						<p class="mb-0 text-secondary">Already registered?</p>
						<div class="d-flex gap-2">
							<a href="login.php" class="btn btn-sm btn-outline-info">Login</a>
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
