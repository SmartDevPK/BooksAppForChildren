<?php
// update_password.php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Password do not match";
        header("Location: reset_password.php?token=$token");
        exit();
    }

    // Check if the token is valid and not expired
    $stmt = $conn->prepare("SELECT id FROM registration WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update the password and clear the reset token
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $hashed_password, $token);
        $stmt->execute();

        $_SESSION['message'] = "Your password has been reset successfully.";
        header("Location: ../logins/login.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid or expired token.";
        header("Location: forgot_password.php");
        exit();
    }
}
?>