<?php
$mysqli = new mysqli("localhost", "root", "", "reminder_db");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $mysqli->real_escape_string($_POST["title"]);
    $description = $mysqli->real_escape_string($_POST["description"]);

    $query = "INSERT INTO reminders (title, description) VALUES ('$title', '$description')";
    
    if ($mysqli->query($query)) {
        header("Location: index.php"); // Redirect back to main page
        exit;
    } else {
        echo "Error: " . $mysqli->error;
    }
}

$mysqli->close();
?>
