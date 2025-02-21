<?php
session_start();
require_once '../connect.php';

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

// Check if token exists and is not expired
$sql = "SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW() LIMIT 1";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Invalid or expired token.");
}

$email = $row['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

    // Update user's password
    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $new_password, $email);
    $stmt->execute();

    // Remove used token
    $sql = "DELETE FROM password_resets WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $_SESSION['message'] = "Password updated successfully. Please log in.";
    header("Location: ../logins/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Reset Password</h2>
    <?php if (isset($_SESSION['message'])) { echo "<p style='color: green'>" . $_SESSION['message'] . "</p>"; unset($_SESSION['message']); } ?>
    
    <form method="post">
        <input type="password" name="password" placeholder="Enter new password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
