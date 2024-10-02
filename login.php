<?php
session_start();
include('config.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the password meets the required constraints
    if (strlen($password) < 9) {
        echo "Password must be at least 8 characters long.";
        exit;
    }

    // Prepare SQL statement
    $sql = "SELECT * FROM login_details WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $hashed_password = $user['password'];
            if (password_verify($password, $hashed_password)) {
                // Password is correct, create session
                $_SESSION['username'] = $username;
                // Redirect to another page
                header("Location:admin.php");
                exit;
            } else {
                header("Location:admin.php");
                exit;
            }
        } else {
            echo "No user found with that username.";
        }

        $stmt->close();
    } else {
        echo "Failed to prepare SQL statement.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/signin.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form action="managerlogin.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
    <script src="javascript/signin.js" defer></script>
</body>
</html>
