<?php
session_start(); // Start session for authentication (if needed)

error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = new mysqli("localhost", "root", "", "reminder_db");
if ($mysqli->connect_error) {
    die("Database Connection Failed: " . $mysqli->connect_error);
}

// Debugging: Check if ID is being received
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Reminder ID is missing or invalid.");
}

$id = intval($_GET['id']);

// Debugging: Print received ID
echo "Processing reminder ID: " . $id . "<br>";

// Check if the reminder exists
$result = $mysqli->query("SELECT * FROM reminders WHERE id = $id");
if ($result->num_rows == 0) {
    die("Error: Reminder not found in database.");
}

// Prepare and execute update query
$query = "UPDATE reminders SET status = 'completed' WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Reminder successfully marked as completed!<br>";
    header("Location: index.php"); // Redirect back
    exit();
} else {
    die("Error updating status: " . $stmt->error);
}

$stmt->close();
$mysqli->close();
?>
