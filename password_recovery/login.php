<?php include "app.login.php";?>

<html lang="en">
<head>
        <meta charset="UTF-8">
        <title>Password Reset PHP</title>
        <link rel="stylesheet" href="../css/main.css">

</head>
<body>
        <form class="login-form" action="login.php" method="post">
                <h2 class="form-title">Login</h2>
                <!-- form validation messages -->
                <?php include('messages.php'); ?>
                <div class="form-group">
                        <label> Email</label>
                        <input type="text"  name="email">
                </div>
                <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password">
                </div>
                <div class="form-group">
                        <button type="submit" name="login_user" class="login-btn">Login</button>
                </div>
                <p><a href="../password_recovery/enter_Email.php">Forgot your password?</a></p>
        </form>
</body>
</html>