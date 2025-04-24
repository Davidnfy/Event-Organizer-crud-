<?php
require_once 'config/db.php';
require_once 'classes/event.php'; 
use App\Models\Event;
$db = new Database();
$conn = $db->getConnection();
$event = new Event($conn);
$id = $_GET['id'];
$event->delete($id);
header("Location: index.php");
exit();
?>
