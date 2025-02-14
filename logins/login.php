<?php
// Process form submission (if needed)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST["password"];
    echo "Entered Password: " . htmlspecialchars($password);
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
