<?php
require_once 'config/db.php'; 
require_once 'classes/event.php';

$db = new Database();
$conn = $db->getConnection();
$event = new App\Models\Event($conn);

$id = $_GET['id'];
$currentEvent = $event->getById($id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $fee = (float) $_POST['fee'];

    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        $image = $currentEvent['image'];
    }

    $event->update($id, $name, $event_date, $location, $description, $fee, $image);

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
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

    <h2>Edit Event</h2>
    <form method="POST" enctype="multipart/form-data">
        <p class="create"> <i class="fa-regular fa-calendar"></i> Name Event</p>
        <input type="text" name="name" value="<?= $currentEvent['name'] ?>" required>
        
        <p class="create"><i class="fa-solid fa-calendar-days"></i> Date Event</p>
        <input type="date" name="event_date" value="<?= $currentEvent['event_date'] ?>" required>
        
        <p class="create"><i class="fa-solid fa-location-dot"></i> Location</p>
        <input type="text" name="location" value="<?= $currentEvent['location'] ?>" required>
        
        <p class="create"><i class="fa-solid fa-audio-description"></i> Description</p>
        <textarea name="description" required><?= $currentEvent['description'] ?></textarea>
        
        <p class="create"><i class="fa-solid fa-money-bill"></i> Registration Fee</p>
        <input type="number" name="fee" value="<?= $currentEvent['fee'] ?>" step="0.01" required>
        
        <p>Current Image:</p>
        <img src="uploads/<?= $currentEvent['image'] ?>" width="150"><br><br>
        
        <input type="file" name="image" accept="image/*">
        
        <button type="submit" class="btn-blue"><i class="fa-solid fa-pen"></i> Update Event</button>
    </form>
</div>

<footer class="footer">
  <div class="footer-content">
    <div class="footer-box">
      <h3><span class="footer-icon">📅</span> Acara</h3>
      <p>Acara is the premier platform for event management and discovery in Indonesia. We connect event organizers with attendees for unforgettable experiences.</p>
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
