<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "reminder_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
// Assign user_id now that we are sure it exists
$user_id = $_SESSION["user_id"];

$query_ongoing = "SELECT * FROM reminders WHERE status = 'ongoing' AND user_id = ? ORDER BY created_at DESC";
$stmt_ongoing = $mysqli->prepare($query_ongoing);
$stmt_ongoing->bind_param("i", $user_id);
$stmt_ongoing->execute();
$result_ongoing = $stmt_ongoing->get_result();


$query_completed = "SELECT * FROM reminders WHERE status = 'completed' AND user_id = ? ORDER BY created_at DESC";
$stmt_completed = $mysqli->prepare($query_completed);
$stmt_completed->bind_param("i", $user_id);
$stmt_completed->execute();
$result_completed = $stmt_completed->get_result();


if (!$result_ongoing || !$result_completed) {
    die("Query failed: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder App</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background: #343a40;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .menu {
            position: relative;
            display: inline-block;
        }
        .menu-icon {
            font-size: 24px;
            cursor: pointer;
            color: white;
        }
        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            width: 120px;
        }
        .dropdown a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
        }
        .dropdown a:hover {
            background: #f1f1f1;
        }
        .menu:hover .dropdown {
            display: block;
        }
        .main-container {
            width: 80%;
            margin: 30px auto;
            display: flex;
            gap: 20px;
            justify-content: space-between;
        }
        .container {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .reminder {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            background: #fff;
            border-radius: 5px;
        }
        .reminder h3 {
            margin: 0;
            color: #333;
        }
        .reminder p {
            color: #666;
        }
        .reminder .actions {
            margin-top: 10px;
        }
        .reminder a {
            text-decoration: none;
            color: #007bff;
            margin-right: 10px;
        }
        .reminder a:hover {
            text-decoration: underline;
        }
        .add-form {
            width: 50%;
            background: white;
            padding: 20px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        input, textarea, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        
    </style>
</head>
<body>

    <div class="header">
        <h1>Reminder App</h1>
        <div class="menu">
            <span class="menu-icon">☰</span>
            <div class="dropdown">
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="add-form">
        <h2>Add New Schedule</h2>
        <form action="add.php" method="POST">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <button type="submit">Add Schedule</button>
        </form>
    </div>

    <div class="main-container">
        <div class="container">
            <h2>Ongoing Reminders</h2>
            <?php if ($result_ongoing->num_rows > 0): ?>
                <?php while ($row = $result_ongoing->fetch_assoc()): ?>
                    <div class="reminder">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                        <div class="actions">
                            <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
                            <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a> |
                            <a href="mark_reminder.php?id=<?= $row['id']; ?>">Mark as Done</a>

                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No ongoing reminders.</p>
            <?php endif; ?>
        </div>

        <div class="container">
            <h2>Finished Reminders</h2>
            <?php if ($result_completed->num_rows > 0): ?>
                <?php while ($row = $result_completed->fetch_assoc()): ?>
                    <div class="reminder" style="background: #d4edda;">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                        <div class="actions">
                            <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No completed reminders.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>

<?php $mysqli->close(); ?>
