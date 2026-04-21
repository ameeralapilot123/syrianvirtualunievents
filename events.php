<?php
$pageTitle = 'Svu Events - Events';
require_once 'db.php';

// Get search and filter parameters
$search = trim($_GET['search'] ?? '');
$filter = trim($_GET['filter'] ?? ($_GET['category'] ?? 'all'));
if ($filter === '') {
	$filter = 'all';
}

// Fetch categories for filter dropdown
$quickCategories = ['Culture', 'Sports', 'Music', 'Family', 'Food', 'Community'];
$categories = $quickCategories;
$categorySql = "
	SELECT DISTINCT category
	FROM events
	WHERE category IS NOT NULL AND category <> ''
	ORDER BY category ASC
";

// Merge quick categories with database categories
$categoryResult = $conn->query($categorySql);
if ($categoryResult) {
	while ($row = $categoryResult->fetch_assoc()) {
		$categoryName = trim((string)$row['category']);
		if ($categoryName !== '' && !in_array($categoryName, $categories, true)) {
			$categories[] = $categoryName;
		}
	}
}

// Define date based filter labels
$dateFilters = [
	'newest' => 'Newest First',
	'oldest' => 'Oldest First',
];

$knownFilters = ['all', 'newest', 'oldest'];
$normalizedFilter = strtolower($filter);

// Determine if the filter is a known filter or a category
if (in_array($normalizedFilter, $knownFilters, true)) {
	$filter = $normalizedFilter;
} else {
	$matchedCategory = null;
	foreach ($categories as $categoryName) {
		if (strtolower($categoryName) === $normalizedFilter) {
			$matchedCategory = $categoryName;
			break;
		}
	}

	$filter = $matchedCategory ?: 'all';
}

$isCategoryFilter = in_array($filter, $categories, true);

$activeFilterLabel = 'All Events';
if ($isCategoryFilter) {
	$activeFilterLabel = 'Category: ' . $filter;
} elseif (isset($dateFilters[$filter])) {
	$activeFilterLabel = $dateFilters[$filter];
}

$sql = "
	SELECT id, title, description, category, location, event_date, image
	FROM events
	WHERE 1=1
";

if ($search !== '') {
	$safeSearch = $conn->real_escape_string($search);
	$sql .= " AND (title LIKE '%$safeSearch%' OR description LIKE '%$safeSearch%' OR location LIKE '%$safeSearch%' OR category LIKE '%$safeSearch%')";
}

if ($isCategoryFilter) {
	$safeCategory = $conn->real_escape_string($filter);
	$sql .= " AND category = '$safeCategory'";
}

if ($filter === 'upcoming') {
	$sql .= " AND event_date >= CURDATE() ORDER BY event_date ASC, id DESC";
} elseif ($filter === 'oldest') {
	$sql .= " ORDER BY event_date ASC, id ASC";
} else {
	$sql .= " ORDER BY event_date DESC, id DESC";
}

$events = [];
$eventsResult = $conn->query($sql);
if ($eventsResult) {
	while ($row = $eventsResult->fetch_assoc()) {
		$events[] = $row;
	}
}

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

	return 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80';
}
// Shorten text for event cards
function shortText($text, $max = 120)
{
	$value = trim((string)$text);
	if ($value === '') {
		return 'No description available yet';
	}

	if (strlen($value) <= $max) {
		return $value;
	}

	return substr($value, 0, $max - 3) . '...';
}

require_once 'include/layout.php';
renderSiteLayoutStart($pageTitle);
?>

<!-- Hero section  -->
<section class="mb-4 events-hero-wrap">
	<div class="rounded-4 border events-hero-surface p-4 p-md-5">
		<div class="row g-4 align-items-end">
			<div class="col-lg-8">
				<p class="text-uppercase small fw-semibold mb-2 events-kicker">Explore</p>
				<h1 class="h2 mb-2">All Events</h1>
				<p class="text-secondary mb-3">Browse what is happening and narrow results quickly by date, category, or keywords.</p>
				<div class="d-flex flex-wrap gap-2">
					<span class="badge event-accent-badge"><?php echo htmlspecialchars($activeFilterLabel); ?></span>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="events-hero-note p-3 rounded-3">
					<p class="small text-uppercase mb-1">Quick Tip</p>
					<p class="mb-0 small text-secondary">Use category filter for focused discovery, then refine by location keywords.</p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Filter section -->
<section class="mb-4">
	<form method="get" class="card border-0 shadow-sm p-3 p-md-4 events-filter-card">
		<div class="row g-3 align-items-end">
			<div class="col-lg-5">
				<label for="search" class="form-label mb-1">Search</label>
				<input type="search" id="search" name="search" class="form-control" placeholder="Search title, location, category..." value="<?php echo htmlspecialchars($search); ?>">
			</div>
			<div class="col-lg-4">
				<label for="filter" class="form-label mb-1">Filter</label>
				<select id="filter" name="filter" class="form-select">
					<?php if (!empty($categories)): ?>
						<optgroup label="Categories">
							<?php foreach ($categories as $category): ?>
								<option value="<?php echo htmlspecialchars($category); ?>" <?php echo $filter === $category ? 'selected' : ''; ?>><?php echo htmlspecialchars($category); ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endif; ?>
					<option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All Events</option>
					<option value="newest" <?php echo $filter === 'newest' ? 'selected' : ''; ?>>Newest First</option>
					<option value="oldest" <?php echo $filter === 'oldest' ? 'selected' : ''; ?>>Oldest First</option>
				</select>
			</div>
			<div class="col-lg-3 d-grid gap-2">
				<button type="submit" class="btn btn-info">Apply Filters</button>
				<a href="events.php" class="btn btn-outline-secondary">Clear</a>
			</div>
		</div>
	</form>
</section>

<!-- Display events -->
<section class="mb-4 events-grid-wrap">
	<div class="row g-4">
		<?php if (!empty($events)): ?>
			<?php foreach ($events as $event): ?>
				<div class="col-md-6 col-xl-4">
					<div class="card h-100 border-0 shadow-sm overflow-hidden event-list-card">
						<div class="event-image-wrap">
							<img src="<?php echo htmlspecialchars(eventImageUrl($event['image'])); ?>" class="card-img-top latest-image" alt="<?php echo htmlspecialchars($event['title']); ?>">
						</div>
						<div class="card-body d-flex flex-column">
							<div class="d-flex flex-wrap gap-2 mb-2">
								<span class="badge event-muted-badge"><?php echo htmlspecialchars($event['category'] ?: 'General'); ?></span>
								<span class="badge event-accent-badge"><?php echo !empty($event['event_date']) ? date('M d, Y', strtotime($event['event_date'])) : 'Date TBD'; ?></span>
							</div>
							<h2 class="h5 card-title mb-2"><?php echo htmlspecialchars($event['title']); ?></h2>
							<p class="small text-secondary mb-2"><i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($event['location'] ?: 'City Venue'); ?></p>
							<p class="card-text text-secondary mb-3"><?php echo htmlspecialchars(shortText($event['description'], 130)); ?></p>
							<div class="mt-auto">
								<a href="event.php?id=<?php echo (int)$event['id']; ?>" class="btn btn-info btn-sm">Open Details</a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<div class="col-12">
				<div class="card border rounded-4 p-4">
					<h2 class="h5 mb-2">No events found</h2>
					<p class="text-secondary mb-0">Try changing your search text or filter selection</p>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php renderSiteLayoutEnd(); ?>
