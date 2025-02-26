<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forget password</title>
</head>

<body>
    <!-- forgot_password.php -->
    <form action="../send_reset_link.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>

</html>