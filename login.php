<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "reminder_db");

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $mysqli->real_escape_string($_POST["username"]);
    $pin = $_POST["pin"]; 

    // Check if table exists
    $checkTable = $mysqli->query("SHOW TABLES LIKE 'users'");
    if ($checkTable->num_rows == 0) {
        die("Error: Table 'users' does not exist.");
    }

    // Secure query using prepared statements
    $query = "SELECT id, username, pin FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("Query preparation failed: " . $mysqli->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Compare PIN directly (remove password_verify)
        if ($pin === $row["pin"]) { 
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"]; 

            header("Location: index.php"); 
            exit();
        } else {
            echo "<script>alert('Invalid PIN!');</script>";
        }
    } else {
        echo "<script>alert('Invalid username!');</script>";
    }

    $stmt->close();
}
$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background: url('background.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }
        .login-container h2 {
            color: #1877f2;
            margin-bottom: 20px;
        }
        .login-container input {
            width: 90%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background: #1877f2;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .login-container button:hover {
            background: #145dbf;
        }
        .register-btn {
            width: 100%;
            padding: 12px;
            background: #42b72a;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .register-btn:hover {
            background: #36a420;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="pin" placeholder="4-digit PIN" required>
        <button type="submit">Login</button>
    </form>
    <button class="register-btn" onclick="window.location.href='register.php'">Create Account</button>
</div>

</body>
</html>
