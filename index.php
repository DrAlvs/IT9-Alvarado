<?php

$mysqli = new mysqli("localhost", "root", "", "reminder_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$query = "SELECT * FROM reminders ORDER BY created_at DESC";
$result = $mysqli->query($query);

if (!$result) {
    die("Query failed: " . $mysqli->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" href="data:image/png;base64,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"> 
    <title>Reminder App</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color:rgb(150, 65, 65);
            margin: 0;
            padding: 0;
        }
        .container { 
            width: 50%; 
            margin: auto; 
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 50px;
        }
        .reminder { 
            border: 1px solid #ccc; 
            padding: 10px; 
            margin: 10px 0; 
            background: #fff;
            border-radius: 5px;
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
        a { 
            text-decoration: none; 
            color: #007bff; 
        }
        a:hover { 
            text-decoration: underline; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Schedule</h2>
        <form action="add.php" method="POST">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <button type="submit">Add Schedule</button>
        </form>
        
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="reminder">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reminders found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $mysqli->close(); ?>
