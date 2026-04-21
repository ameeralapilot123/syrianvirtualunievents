<?php
$pageTitle = 'Svu Events - About';
$teamMembers = [
	[
		'name' => 'Rona Ezzat Flara',
		'code' => 'rona_314544',
		'role' => 'Team Coordinator',
		'initials' => 'RF',
	],
	[
		'name' => 'Rema abdelkarem',
		'code' => 'rema_200501',
		'role' => 'UI and Frontend Designer',
		'initials' => 'RA',
	],
	[
		'name' => 'Farah Jdeed',
		'code' => 'farah_303579',
		'role' => 'Backend Systems Developer',
		'initials' => 'FJ',
	],
	[
		'name' => 'Aya Ahmed Taqi al-Din',
		'code' => 'aya_295382',
		'role' => 'Student Engagement Liaison',
		'initials' => 'AT',
	],
	[
		'name' => 'Ahmad hassoun',
		'code' => 'Ahmad_247784',
		'role' => 'Outreach and Community Partnerships',
		'initials' => 'AH',
	],
];
require_once 'include/layout.php';
renderSiteLayoutStart($pageTitle);
?>

<!-- Hero section  -->
<section class="about-hero mb-5 position-relative overflow-hidden rounded-4 border hero-surface p-4 p-md-5">
	<span class="about-shape about-shape-a" aria-hidden="true"></span>
	<span class="about-shape about-shape-b" aria-hidden="true"></span>
	<div class="position-relative">
		<p class="text-uppercase small fw-semibold mb-2 about-kicker">Inside The Platform</p>
		<h1 class="display-6 fw-bold mb-3">Built to Make Campus Life Easier to Join</h1>
		<p class="mb-0 text-secondary about-hero-copy">From workshops and competitions to club activities and career sessions, this space helps students quickly find what matters, decide faster, and show up with confidence.</p>
	</div>
</section>

<!-- Mission and vision section -->
<section class="mb-5">
	<div class="row g-4 align-items-stretch">
		<div class="col-lg-6">
			<article class="about-panel card border-0 shadow-sm h-100 p-4 p-md-5">
				<p class="small text-uppercase fw-semibold mb-2 about-panel-label">Mission</p>
				<h2 class="h4 mb-3">One reliable calendar for the whole university community</h2>
				<p class="mb-0 text-secondary">Our mission is to remove the noise around event discovery. Instead of checking scattered channels, students get one trustworthy place to browse upcoming activities, compare options, and plan participation around their schedule</p>
			</article>
		</div>
		<div class="col-lg-6">
			<article class="about-panel card border-0 shadow-sm h-100 p-4 p-md-5">
				<p class="small text-uppercase fw-semibold mb-2 about-panel-label">Long-term Vision</p>
				<h2 class="h4 mb-3">A stronger student network through visible opportunities</h2>
				<p class="mb-0 text-secondary">We envision a campus culture where every student can discover meaningful events early, connect with people beyond their classroom, and build practical skills through regular participation in community life</p>
			</article>
		</div>
	</div>
</section>

<!-- Unique value proposition section -->
<section class="mb-5">
	<div class="card border-0 shadow-sm p-4 p-md-5">
		<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
			<div>
				<p class="small text-uppercase fw-semibold mb-1 about-panel-label">What Makes This Platform Different</p>
				<h2 class="h4 mb-0">Focused on speed, clarity, and practical use</h2>
			</div>
		</div>
		<div class="row g-3">
			<div class="col-6 col-lg-3">
				<div class="about-stat h-100 p-3">
					<p class="about-stat-value mb-1">1</p>
					<p class="small mb-0 text-secondary">Unified place to find university events</p>
				</div>
			</div>
			<div class="col-6 col-lg-3">
				<div class="about-stat h-100 p-3">
					<p class="about-stat-value mb-1">Fast</p>
					<p class="small mb-0 text-secondary">Scanning by date, category, and location</p>
				</div>
			</div>
			<div class="col-6 col-lg-3">
				<div class="about-stat h-100 p-3">
					<p class="about-stat-value mb-1">Clear</p>
					<p class="small mb-0 text-secondary">Practical event details without clutter</p>
				</div>
			</div>
			<div class="col-6 col-lg-3">
				<div class="about-stat h-100 p-3">
					<p class="about-stat-value mb-1">Open</p>
					<p class="small mb-0 text-secondary">Supports clubs, departments, and partners</p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Team section -->
<section class="mb-5 home-section-block team-stage">
	<div class="d-flex justify-content-between align-items-center mb-3 home-section-head">
		<div>
			<h2 class="h4 mb-1">Team and Partners</h2>
			<p class="team-section-copy mb-0">The people shaping the platform and the campus partners helping keep it useful, current, and visible.</p>
		</div>
	</div>
	<div class="row g-4 team-grid">
		<?php foreach ($teamMembers as $member): ?>
			<div class="col-12 col-md-6 col-xl-4">
				<div class="card h-100 border-0 shadow-sm home-team-card">
					<div class="card-body p-4 d-flex flex-column">
						<div class="d-flex align-items-start justify-content-between gap-3 mb-4">
							<div class="team-avatar" aria-hidden="true"><?php echo htmlspecialchars($member['initials']); ?></div>
							<span class="badge text-bg-info team-role-pill"><?php echo htmlspecialchars($member['role']); ?></span>
						</div>
						<h3 class="h5 mb-1"><?php echo htmlspecialchars($member['name']); ?></h3>
						<p class="team-member-code mb-0"><?php echo htmlspecialchars($member['code']); ?></p>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<!-- Contact information section -->

<section class="mb-4">
	<div class="card border-0 shadow-sm p-4 p-md-5">
		<h2 class="h4 mb-4">Community Standards</h2>
		<div class="accordion accordion-flush" id="aboutPoliciesAccordion">
			<div class="accordion-item bg-transparent">
				<h3 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#policyAccuracy" aria-expanded="false" aria-controls="policyAccuracy">
						Accurate event details
					</button>
				</h3>
				<div id="policyAccuracy" class="accordion-collapse collapse" data-bs-parent="#aboutPoliciesAccordion">
					<div class="accordion-body text-secondary">Every listing should include a correct title, date, location, and category so students can trust what they see.</div>
				</div>
			</div>
			<div class="accordion-item bg-transparent">
				<h3 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#policyContent" aria-expanded="false" aria-controls="policyContent">
						Respectful and safe communication
					</button>
				</h3>
				<div id="policyContent" class="accordion-collapse collapse" data-bs-parent="#aboutPoliciesAccordion">
					<div class="accordion-body text-secondary">Event descriptions must follow university values and avoid harmful, discriminatory, or misleading language.</div>
				</div>
			</div>
			<div class="accordion-item bg-transparent">
				<h3 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#policyReview" aria-expanded="false" aria-controls="policyReview">
						Review timing and updates
					</button>
				</h3>
				<div id="policyReview" class="accordion-collapse collapse" data-bs-parent="#aboutPoliciesAccordion">
					<div class="accordion-body text-secondary">Submit early to allow review, and report any changes quickly so published information remains current.</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php renderSiteLayoutEnd(); ?>
