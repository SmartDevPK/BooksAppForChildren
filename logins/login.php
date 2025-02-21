<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../connect.php'; 

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Fetch user from database
    $sql = "SELECT id, password FROM users WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Login successful, store session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $email;

        // Check if the user has made a payment
        $sql = "SELECT payment_id FROM purchases WHERE user_id = ? LIMIT 1";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $purchase = $result->fetch_assoc();

        if ($purchase) {
            // User has paid, redirect to readbook page
            header("Location: ../paypal/readbook.php");
            exit;
        } else {
            // User has not paid, redirect to profile page
            header("Location: ../views/user_profile.php");
            exit;
        }
    } else {
        echo "<script>alert('Invalid email or password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOK APP FOR CHILDREN</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="header">
        <h1>BOOK APP FOR CHILDREN LOGIN PAGE</h1>
    </div>

    <form method="post" action="../login.server.php">
        
        <div class="input-group">  
            <label for="email">EMAIL</label>
            <input type="text" id="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="input-group">
            <label for="password">PASSWORD</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <input type="checkbox" id="showPassword">
        </div>

        <div class="input-group">
            <button type="submit" class="btn" name="login_user">Login</button>
        </div>

        <p>
            Don't have an account? <a href="../register/register.php">Sign up</a>
        </p>

        <p>
            <a href="forget_password.php">Forgot Password?</a>
        </p>
        
    </form>

    <script>
        document.getElementById('showPassword').addEventListener('change', function () {
            let passwordField = document.getElementById('password');
            passwordField.type = this.checked ? 'text' : 'password';
        });
    </script>

</body>
</html>
