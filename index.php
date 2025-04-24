<?php
require_once 'config/db.php';
require_once 'classes/event.php';

$db = new Database();
$conn = $db->getConnection();

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
    $stmt = $conn->prepare("SELECT * FROM events WHERE name LIKE ? ORDER BY id DESC");
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM events ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Acara | Event Organizer</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="fontawesome-free-6.6.0-web/fontawesome-free-6.6.0-web/css/all.css">
</head>
<body>
<header>
    <div class="logo"><i class="fa-solid fa-dragon"></i> DavNfy</div>
    <nav>
        <a href="index.php" class="home-text"><i class="fa-solid fa-house"></i> Home</a>
        <form action="create.php" method="get" style="display:inline;">
            <button type="submit" class="btn-blue"><i class="fa-solid fa-plus"></i> Create Event</button>
        </form>
    </nav>
</header>

<section class="hero">
    <h1>Discover and Create Amazing Events</h1>
    <p>Find the perfect event or share your own with our community</p>
    <div class="hero-btns">
    <div class="hero-actions">
        <form method="GET" action="index.php" class="search-form">
            <input type="text" name="search" placeholder="Cari nama event..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn-blue"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
        </form> 
        <form action="create.php" method="get">
            <button class="btn-blue" type="submit"><i class="fa-regular fa-calendar"></i> Create Event</button>
        </form>
    </div>
</div>


</section>
<section class="recent-events" id="recent">
    <h2>Recent Events</h2>
    <div class="event-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="event-card">
                <?php if ($row['image']): ?>
                    <img src="uploads/<?= $row['image'] ?>" alt="<?= $row['name'] ?>" class="event-img">
                <?php endif; ?>
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p><strong><i class="fa-solid fa-location-dot"></i> Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
                <p><strong><i class="fa-solid fa-calendar-days"></i> Date:</strong> <?= $row['event_date'] ?></p>
                <p><strong><i class="fa-solid fa-audio-description"></i> Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
                <p><strong><i class="fa-solid fa-money-bill"></i> Price:</strong> Rp <?= number_format($row['fee'], 0, ',', '.') ?></p>
                <form action="edit.php" method="get" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button class="btn-outline edit" type="submit"> <i class="fa-solid fa-pen"></i> Edit</button>
                </form>
                <form action="delete.php" method="get" onsubmit="return confirm('Hapus event ini?')" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button class="btn-outline delete" type="submit"><i class="fa-solid fa-trash"></i> Delete</button>
                </form>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">Tidak ada event yang ditemukan.</p>
        <?php endif; ?>
    </div>
</section>

<section class="why-acara">
    <h2>Why Choose DavNfy</h2>
    <div class="why-cards">
        <div class="why-card">
            <div class="why-icon">📅</div>
            <h3>Easy Event Creation</h3>
            <p>Create professional event listings in minutes with our intuitive interface.</p>
        </div>
        <div class="why-card">
            <div class="why-icon">🔍</div>
            <h3>Discover Events</h3>
            <p>Find events that match your interests from our curated collection.</p>
        </div>
        <div class="why-card">
            <div class="why-icon">👥</div>
            <h3>Community</h3>
            <p>Join a community of event enthusiasts and organizers from all over Indonesia.</p>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-box">
            <h3><span class="footer-icon">📅</span> DavNfy</h3>
            <p>DavNfy is the premier platform for event management and discovery in Indonesia. We connect event organizers with attendees for unforgettable experiences.</p>
        </div>
        <div class="footer-box">
            <h4>Quick Links</h4>
            <ul>
                <li>
                    <form action="index.php" method="get">
                        <button type="submit" class="btn">Home</button>
                    </form>
                </li>
                <li>
                    <form action="index.php#recent" method="get">
                        <button type="submit" class="btn">Browse Events</button>
                    </form>
                </li>
                <li>
                    <form action="create.php" method="get">
                        <button type="submit" class="btn">Create Event</button>
                    </form>
                </li>
            </ul>
        </div>
        <div class="footer-box">
            <h4>Contact</h4>
            <p>Email: info@davnfy.id</p>
            <p>Phone: +62 21 1234 5678</p>
            <p>Address: Malang, Indonesia</p>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2025 David. All rights reserved.
    </div>
</footer>
</body>
</html>
