<?php
$mysqli = new mysqli("localhost", "root", "", "reminder_db");


if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
    $query = "DELETE FROM reminders WHERE id = $id";
    
    if ($mysqli->query($query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $mysqli->error;
    }
}

$mysqli->close();
?>
