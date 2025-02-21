<?php
session_start();
require_once '../connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if the email exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Generate a unique reset token
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", time() + 3600); // Expires in 1 hour

        // Store token in database
        $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE token=?, expires_at=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssss", $email, $token, $expiry, $token, $expiry);
        $stmt->execute();

        // Send email with reset link
        $reset_link = "http://localhost/BooksAppForChildren/logins/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Click this link to reset your password: $reset_link";
        $headers = "From: Book App For Children.com\r\n";

        if (mail($email, $subject, $message, $headers)) {
            $_SESSION['message'] = "Check your email for the reset link.";
        } else {
            $_SESSION['error'] = "Failed to send email. Try again.";
        }
    } else {
        $_SESSION['error'] = "No account found with that email.";
    }

    header("Location: ../logins/forgot_password.php");
    exit;
}
?>

<style>
    /* General styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    text-align: center;
    margin: 0;
    padding: 0;
}

/* Container for the form */
.container {
    width: 100%;
    max-width: 400px;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    margin: 50px auto;
}

/* Heading */
h2 {
    color: #333;
    margin-bottom: 20px;
}

/* Input field */
input[type="email"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Submit button */
button {
    width: 100%;
    padding: 12px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
    transition: background 0.3s ease;
}

button:hover {
    background-color: #3498db;838;
}

/* Messages */
p {
    font-size: 14px;
    margin-top: 10px;
}

p[style*="color: green"] {
    color: #28a745;
}

p[style*="color: red"] {
    color: #dc3545;
}


</style>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
</head>
<body>
    <h2>Forgot Password</h2>
    <?php if (isset($_SESSION['message'])) { echo "<p style='color: green'>" . $_SESSION['message'] . "</p>"; unset($_SESSION['message']); } ?>
    <?php if (isset($_SESSION['error'])) { echo "<p style='color: red'>" . $_SESSION['error'] . "</p>"; unset($_SESSION['error']); } ?>
    
    <form method="post">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
