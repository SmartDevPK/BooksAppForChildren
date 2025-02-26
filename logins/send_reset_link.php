<?php

// send_reset_link.php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_post['email'];
}

// check if the email exist in the database
$stmt = $conn->prepare('SELECT id FROM registration  WHERE email= ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Generate a unique token
    $token = bin2hex(random_bytes(50));
    //Token expired in 1hour
    $expiry = date("Y-m-d H:i:s", time() + 3600);

    // store the token in the database
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expiry, $email);
    $stmt->execute();

    // Send the reset link to the user email
    $reset_link = "http://yourwebsit.com/reset_password.php?token=$token";
    $subject = "Password Reset Request";
    $message = "Click the following link to reset your password: $reset_link";
    $headers = "From: Book App For Children";


    if (mail($email, $subject, $message, $headers)) {
        $_SESSION['message'] = "A password reset link has been sent to your email.";
    } else {
        $_SESSION['error'] = "Failed to send the reset link. Please try again.";
    }
} else {
    $_SESSION['error'] = "No account found with that email address.";
}

header("Location: ../logins/forgot_password.php");
exit();
?>