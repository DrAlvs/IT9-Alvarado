<?php
$mysqli = new mysqli("localhost", "root", "", "reminder_db");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch the reminder to edit
if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
    $result = $mysqli->query("SELECT * FROM reminders WHERE id = $id");
    $reminder = $result->fetch_assoc();
}

// Update the reminder
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) $_POST["id"];
    $title = $mysqli->real_escape_string($_POST["title"]);
    $description = $mysqli->real_escape_string($_POST["description"]);

    $query = "UPDATE reminders SET title='$title', description='$description' WHERE id=$id";
    
    if ($mysqli->query($query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $mysqli->error;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Reminder</title>
</head>
<body>
    <h2>Edit Reminder</h2>
    <form action="edit.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($reminder['id']) ?>">
        <input type="text" name="title" value="<?= htmlspecialchars($reminder['title']) ?>" required>
        <textarea name="description" required><?= htmlspecialchars($reminder['description']) ?></textarea>
        <button type="submit">Update Reminder</button>
    </form>
</body>
</html>
