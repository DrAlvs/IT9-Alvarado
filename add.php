<?php
session_start();
var_dump($_SESSION); 

$mysqli = new mysqli("localhost", "root", "", "reminder_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (!isset($_SESSION["user_id"])) {
    die("Error: User is not logged in.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $mysqli->real_escape_string($_POST["title"]);
    $description = $mysqli->real_escape_string($_POST["description"]);
    $status = "ongoing";  

    $query = "INSERT INTO reminders (user_id, title, description, created_at, status) 
              VALUES (?, ?, ?, NOW(), ?)";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        die("Error preparing statement: " . $mysqli->error);
    }
    $stmt->bind_param("isss", $_SESSION["user_id"], $title, $description, $status);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$mysqli->close();
?>
