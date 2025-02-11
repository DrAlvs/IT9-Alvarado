<?php
$mysqli = new mysqli("localhost", "root", "", "reminder_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $mysqli->real_escape_string($_POST["username"]);
    $pin = $mysqli->real_escape_string($_POST["pin"]);

    // Validate PIN (must be exactly 4 digits)
    if (!preg_match('/^\d{4}$/', $pin)) {
        echo "<script>alert('PIN must be exactly 4 digits.');</script>";
    } else {
        // Generate a random 6-digit numeric ID
        $user_id = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

        // Insert into database
        $query = "INSERT INTO users (id, username, pin) VALUES ('$user_id', '$username', '$pin')";
        if ($mysqli->query($query)) {
            echo "<script>alert('Registration successful! Your User ID is: $user_id');</script>";
        } else {
            echo "<script>alert('Error: " . $mysqli->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        .register-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }
        .register-container h2 {
            color: #42b72a;
            margin-bottom: 20px;
        }
        .register-container input {
            width: 90%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .register-container button {
            width: 100%;
            padding: 12px;
            background: #42b72a;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .register-container button:hover {
            background: #36a420;
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            background: #1877f2;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .login-btn:hover {
            background: #145dbf;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Sign Up</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="pin" placeholder="4-digit PIN" required>
        <button type="submit">Sign Up</button>
    </form>
    <button class="login-btn" onclick="window.location.href='login.php'">Back to Login</button>
</div>

</body>
</html>
