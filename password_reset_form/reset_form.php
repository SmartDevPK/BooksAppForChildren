<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>reset password </title>
</head>

<body>
        <!-- reset_password.php -->
        <form action="../update_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                <label for="password">New Password:</label>
                <input type="password" name="password" id="password" required>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <button type="submit">Reset Password</button>
        </form>
</body>

</html>