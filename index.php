<?php
$pageTitle = 'Svu Events - Home';
require_once 'db.php';

// Fetch featured events 
$featuredEvents = [];
$featuredSql = "
	SELECT id, title, description, category, location, event_date, image
	FROM events
	WHERE event_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
	ORDER BY event_date ASC
	LIMIT 5
";

$featuredResult = $conn->query($featuredSql);
if ($featuredResult) {
	while ($row = $featuredResult->fetch_assoc()) {
		$featuredEvents[] = $row;
	}
}

$latestEvents = [];
$latestSql = "
	SELECT id, title, description, category, location, event_date, image
	FROM events
	ORDER BY id DESC
	LIMIT 3
";

// Fetch latest events for the "Latest Events" section
$latestResult = $conn->query($latestSql);
if ($latestResult) {
	while ($row = $latestResult->fetch_assoc()) {
		$latestEvents[] = $row;
	}
}

// image URL helper function
function eventImageUrl($image)
{
	if (!empty($image)) {
		if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
			return $image;
		}

		if (strpos($image, 'uploads/') === 0) {
			return $image;
		}

		return 'uploads/' . ltrim($image, '/');
	}

	return 'https://images.unsplash.com/photo-1472653431158-6364773b2a56?auto=format&fit=crop&w=1200&q=80';
}

// team members data 
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

<!-- Hero section -->
<section class="mb-5 home-hero-wrap home-section-block">
	<div class="home-hero rounded-4 border overflow-hidden position-relative">
		<div class="row g-4 align-items-center">
			<div class="col-lg-8">
				<p class="text-uppercase small fw-semibold mb-2 home-kicker">Happening Now</p>
				<h1 class="display-5 fw-bold mb-3">Your Weekly Map of Campus Activity</h1>
				<p class="text-secondary mb-0 home-hero-copy">Find workshops, competitions, community gatherings, and student-led moments in one place. Plan your week with less searching and more participation</p>
			</div>
			<div class="col-lg-4 text-lg-end">
				<a href="events.php" class="btn btn-info px-4 py-2">Explore Event Directory</a>
			</div>
		</div>
	</div>
</section>

<!-- Featured events carousel -->
<section class="mb-5 feature-stage home-section-block">
	<?php if (!empty($featuredEvents)): ?>
		<div id="featuredEventsCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-indicators">
				<?php foreach ($featuredEvents as $index => $event): ?>
					<button type="button" data-bs-target="#featuredEventsCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>" <?php echo $index === 0 ? 'aria-current="true"' : ''; ?> aria-label="Slide <?php echo $index + 1; ?>"></button>
				<?php endforeach; ?>
			</div>

			<!-- Carousel items -->
			<div class="carousel-inner rounded-4 overflow-hidden border">
				<?php foreach ($featuredEvents as $index => $event): ?>
					<div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
						<img src="<?php echo htmlspecialchars(eventImageUrl($event['image'])); ?>" class="d-block w-100 featured-image" alt="<?php echo htmlspecialchars($event['title']); ?>">
						<div class="carousel-caption text-start feature-caption">
							<span class="badge text-bg-info mb-2"><?php echo htmlspecialchars($event['category'] ?: 'General'); ?></span>
							<h5 class="fw-bold"><?php echo htmlspecialchars($event['title']); ?></h5>
							<p class="mb-1"><?php echo htmlspecialchars($event['location'] ?: 'City Venue'); ?></p>
							<small class="d-block mb-2"><?php echo !empty($event['event_date']) ? date('M d, Y', strtotime($event['event_date'])) : 'Date to be announced'; ?></small>
							<a href="event.php?id=<?php echo (int)$event['id']; ?>" class="btn btn-info btn-sm">Open event brief</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php else: ?>
		<div class="card border rounded-4 p-4">
			<h3 class="h5 mb-2">Featured spotlight</h3>
			<p class="text-secondary mb-0">No events are highlighted this week yet. New picks will appear as soon as listings are updated</p>
		</div>
	<?php endif; ?>
</section>

<!-- Quick categories section -->
<section class="mb-5 home-section-block quick-categories-panel">
	<div class="d-flex justify-content-between align-items-center mb-3 home-section-head">
		<h2 class="h4 mb-0">Quick Categories</h2>
	</div>
	<div class="d-flex flex-wrap gap-2 quick-grid">
		<a href="events.php?filter=Culture" class="btn btn-outline-light category-btn"><i class="bi bi-palette me-2"></i>Culture</a>
		<a href="events.php?filter=Sports" class="btn btn-outline-light category-btn"><i class="bi bi-trophy me-2"></i>Sports</a>
		<a href="events.php?filter=Music" class="btn btn-outline-light category-btn"><i class="bi bi-music-note-beamed me-2"></i>Music</a>
		<a href="events.php?filter=Family" class="btn btn-outline-light category-btn"><i class="bi bi-people me-2"></i>Family</a>
		<a href="events.php?filter=Food" class="btn btn-outline-light category-btn"><i class="bi bi-cup-hot me-2"></i>Food</a>
		<a href="events.php?filter=Community" class="btn btn-outline-light category-btn"><i class="bi bi-geo-alt me-2"></i>Community</a>
	</div>
</section>

<!-- Team section -->
<section class="mb-5 home-section-block team-stage">
	<div class="d-flex justify-content-between align-items-center mb-3 home-section-head">
		<div>
			<h2 class="h4 mb-1">Team and Partners</h2>
			<p class="team-section-copy mb-0">The people shaping the platform and the campus partners helping keep it useful, current, and visible</p>
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

<!-- Latest events section -->
<section class="mb-4 home-section-block latest-stage">
	<div class="d-flex justify-content-between align-items-center mb-3 home-section-head">
		<h2 class="h4 mb-0">Latest Events</h2>
		<a href="events.php" class="btn btn-sm btn-outline-info">View all</a>
	</div>

	<div class="row g-4 latest-grid">
		<?php if (!empty($latestEvents)): ?>
			<?php foreach ($latestEvents as $event): ?>
				<div class="col-md-6 col-lg-4">
					<div class="card h-100 border-0 shadow-sm overflow-hidden latest-card">
						<img src="<?php echo htmlspecialchars(eventImageUrl($event['image'])); ?>" class="card-img-top latest-image" alt="<?php echo htmlspecialchars($event['title']); ?>">
						<div class="card-body d-flex flex-column">
							<span class="badge text-bg-secondary align-self-start mb-2"><?php echo htmlspecialchars($event['category'] ?: 'General'); ?></span>
							<h3 class="h5 card-title"><?php echo htmlspecialchars($event['title']); ?></h3>
							<p class="card-text text-secondary mb-3"><?php echo htmlspecialchars(substr((string)($event['description'] ?: 'No description available yet.'), 0, 120)); ?>...</p>
							<div class="mt-auto">
								<p class="mb-1 small text-secondary"><?php echo htmlspecialchars($event['location'] ?: 'City Venue'); ?></p>
								<p class="mb-3 small text-secondary"><?php echo !empty($event['event_date']) ? date('M d, Y', strtotime($event['event_date'])) : 'Date to be announced'; ?></p>
								<a href="event.php?id=<?php echo (int)$event['id']; ?>" class="btn btn-info btn-sm">See full details</a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<div class="col-12">
				<div class="card border rounded-4 p-4">
					<p class="text-secondary mb-0">No events have been posted yet, Add events from the admin dashboard to populate this section</p>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php renderSiteLayoutEnd(); ?>
