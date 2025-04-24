<?php
require_once 'config/db.php';
require_once 'classes/event.php';

$db = new Database();
$conn = $db->getConnection();
$event = new Event($conn);

class Event {
    private $conn;
    private $table = "events";

    public function __construct($db) { 
        $this->conn = $db;
    }

    public function create($name, $event_date, $location, $description, $fee, $image) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (name, event_date, location, description, fee, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssds", $name, $event_date, $location, $description, $fee, $image);
        return $stmt->execute();
    }
}

$event = new Event($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $fee = $_POST['fee'];

    $image = $_FILES['image']['name'];
    $target = "uploads/" . uniqid() . "_" . basename($image);
    $imageType = mime_content_type($_FILES['image']['tmp_name']);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($imageType, $allowedTypes)) {
        die("File type not allowed.");
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $filename = basename($target);
        if ($event->create($name, $event_date, $location, $description, $fee, $filename)) {
            header("Location: index.php?success=1");
            exit;
        } else {
            echo "Gagal menambahkan event.";
        }
    } else {
        echo "Upload gambar gagal.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
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

<div class="form-container">
    <form action="index.php" method="get" style="margin-bottom: 1rem;">
        <button type="submit" class="btn-back"><i class="fa-solid fa-backward"></i></button>
    </form>

    <h2>Create New Event</h2>
    <form method="POST" enctype="multipart/form-data">
        <p class="create"> <i class="fa-regular fa-calendar"></i> Name Event</p>
        <input type="text" name="name" placeholder="Name Event" required>
        <p  class="create"><i class="fa-solid fa-calendar-days"></i> Date Event </p>
        <input type="date" name="event_date" required>
        <p  class="create"><i class="fa-solid fa-location-dot"></i> Location</p>
        <input type="text" name="location" placeholder="Lokasi" required>
        <p  class="create"><i class="fa-solid fa-audio-description"></i> Description</p>
        <textarea name="description" placeholder="Deskripsi" required></textarea>
        <p  class="create"><i class="fa-solid fa-money-bill"></i> Registration Fee</p>
        <input type="number" name="fee" placeholder="Biaya Pendaftaran" step="0.01" required>
        <p  class="create"><i class="fa-solid fa-image"></i> Image</p>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" class="btn-blue"><i class="fa-solid fa-plus"></i> Create Event</button>
    </form>
</div>

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
