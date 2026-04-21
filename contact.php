<?php
$pageTitle = 'Svu Events - Contact';

$name = '';
$email = '';
$message = '';
$consentAccepted = false;
$successMessage = '';
$errorMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = trim($_POST['name'] ?? '');
	$email = trim($_POST['email'] ?? '');
	$message = trim($_POST['message'] ?? '');
	$consentAccepted = isset($_POST['contact_consent']) && $_POST['contact_consent'] === '1';

	$isEmailValid = filter_var($email, FILTER_VALIDATE_EMAIL);

	if ($name === '' || $email === '' || $message === '') {
		$errorMessage = 'Please fill in all required fields';
	} elseif (!$isEmailValid) {
		$errorMessage = 'Please enter a valid email address';
	} elseif (!$consentAccepted) {
		$errorMessage = 'Please confirm consent so we can contact you about your message';
	} else {
		$successMessage = 'Message received. This is a demo submission';
		$name = '';
		$email = '';
		$message = '';
		$consentAccepted = false;
	}
}

require_once 'include/layout.php';
renderSiteLayoutStart($pageTitle);
?>

<!-- Hero section  -->
<section class="mb-4">
	<div class="p-4 p-md-5 rounded-4 border hero-surface">
		<p class="text-uppercase small fw-semibold mb-2 contact-kicker">Contact and Support</p>
		<h1 class="h2 mb-2">Let's Plan Better Events Together</h1>
		<p class="text-secondary mb-0">Reach out for publishing help, partnership requests, or general questions about student activities</p>
	</div>
</section>

<!-- Contact information and form section -->
<section class="mb-4">
	<div class="row g-4">
		<div class="col-lg-4">
			<div class="card border-0 shadow-sm h-100 p-4 contact-channel-card">
				<h2 class="h5 mb-3">Contact Channels</h2>
				<ul class="list-unstyled mb-4 d-grid gap-3 contact-meta-list">
					<li class="contact-meta-item">
						<p class="small text-secondary mb-1">General Email</p>
						<p class="mb-0"><i class="bi bi-envelope me-2" aria-hidden="true"></i>svu@cityevents.local</p>
					</li>
					<li class="contact-meta-item">
						<p class="small text-secondary mb-1">Phone</p>
						<p class="mb-0"><i class="bi bi-telephone me-2" aria-hidden="true"></i>+963 111 222 333</p>
					</li>
					<li class="contact-meta-item">
						<p class="small text-secondary mb-1">Office Hours</p>
						<p class="mb-0"><i class="bi bi-clock me-2" aria-hidden="true"></i>Sun-Thu, 9:00 AM to 3:00 PM</p>
					</li>
					<li class="contact-meta-item">
						<p class="small text-secondary mb-1">Social</p>
						<p class="mb-0"><i class="bi bi-share me-2" aria-hidden="true"></i>@svu.city.events</p>
					</li>
				</ul>
				<p class="small text-secondary mb-0">For urgent event corrections, include your event title and date in the first line of your message for faster support.</p>
			</div>
		</div>

		<!-- Contact form section -->
		<div class="col-lg-8">
			<div class="card border-0 shadow-sm p-4 p-md-5 contact-form-shell">
				<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
					<h2 class="h4 mb-0">Send a Message</h2>
					<span class="badge text-bg-info">Response within 2 business days</span>
				</div>

				<?php if ($successMessage !== ''): ?>
					<div class="alert alert-success" role="status" aria-live="polite"><?php echo htmlspecialchars($successMessage); ?></div>
				<?php endif; ?>

				<?php if ($errorMessage !== ''): ?>
					<div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($errorMessage); ?></div>
				<?php endif; ?>

				<form method="post" action="contact.php" id="contactForm" class="js-contact-form" novalidate>
					<div class="row g-3 mb-1">
						<div class="col-md-6">
							<label for="name" class="form-label">Full name</label>
							<input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" autocomplete="name" value="<?php echo htmlspecialchars($name); ?>" aria-describedby="nameHelp" required>
							<div id="nameHelp" class="form-text">Use your real name so we can address you correctly</div>
							<div class="invalid-feedback">Please provide your full name</div>
						</div>

						<div class="col-md-6">
							<label for="email" class="form-label">Email address</label>
							<input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" autocomplete="email" value="<?php echo htmlspecialchars($email); ?>" aria-describedby="emailHelp" required>
							<div id="emailHelp" class="form-text">We will only use this to follow up on your request</div>
							<div class="invalid-feedback">Enter a valid email address in this format: name@example.com</div>
						</div>
					</div>

					<div class="mb-3">
						<label for="message" class="form-label">Your message</label>
						<textarea id="message" name="message" class="form-control" rows="6" maxlength="800" placeholder="Tell us what you need help with..." aria-describedby="messageHelp messageCounter" required><?php echo htmlspecialchars($message); ?></textarea>
						<div class="d-flex justify-content-between align-items-center mt-1">
							<div id="messageHelp" class="form-text mb-0">Include event title, date, and specific request for faster help</div>
							<div id="messageCounter" class="small text-secondary" aria-live="polite">0 / 800</div>
						</div>
						<div class="invalid-feedback">Please write a message before submitting.</div>
					</div>

					<div class="form-check mb-4">
						<input class="form-check-input" type="checkbox" id="contactConsent" name="contact_consent" value="1" <?php echo $consentAccepted ? 'checked' : ''; ?> required>
						<label class="form-check-label" for="contactConsent">I agree to be contacted about this message</label>
						<div class="invalid-feedback">You must agree before sending the form</div>
					</div>

					<button type="submit" id="contactSubmitButton" class="btn btn-info" disabled>
						<i class="bi bi-send me-2" aria-hidden="true"></i>Send Message
					</button>
				</form>
			</div>
		</div>
	</div>
</section>

<!-- FAQ section -->
<section class="mb-4">
	<div class="card border-0 shadow-sm p-4 p-md-5">
		<h2 class="h5 mb-3">Quick FAQ</h2>
		<div class="accordion accordion-flush" id="contactFaqAccordion">
			<div class="accordion-item bg-transparent">
				<h3 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqPublish" aria-expanded="false" aria-controls="faqPublish">
						How do I publish a new event?
					</button>
				</h3>
				<div id="faqPublish" class="accordion-collapse collapse" data-bs-parent="#contactFaqAccordion">
					<div class="accordion-body text-secondary">Use the admin panel to add a new event entry with title, date, location, category, and a complete description</div>
				</div>
			</div>
			<div class="accordion-item bg-transparent">
				<h3 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqEdit" aria-expanded="false" aria-controls="faqEdit">
						Can I update event details after publishing?
					</button>
				</h3>
				<div id="faqEdit" class="accordion-collapse collapse" data-bs-parent="#contactFaqAccordion">
					<div class="accordion-body text-secondary">Yes. Open the dashboard, edit the event, and save changes. If the event is urgent, mention that in your message to support</div>
				</div>
			</div>
			<div class="accordion-item bg-transparent">
				<h3 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqPartnership" aria-expanded="false" aria-controls="faqPartnership">
						Do you support external partnerships?
					</button>
				</h3>
				<div id="faqPartnership" class="accordion-collapse collapse" data-bs-parent="#contactFaqAccordion">
					<div class="accordion-body text-secondary">Partnership requests are welcome for activities aligned with student development and university community goals</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php renderSiteLayoutEnd(); ?>
