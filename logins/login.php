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
    $sql = "SELECT id password FROM registration WHERE email = ?";
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
<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session

// Check if an error message is set in the session
$error = "";
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']); // Clear error after retrieving
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOK APP FOR CHILDREN</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        form {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .input-group input:focus {
            border-color: #007bff;
        }

        .input-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #555;
        }

        a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Display error message if any -->
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" action="../login.server.php">
        <div class="input-group">  
            <label for="email">EMAIL</label>
            <input type="text" id="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="input-group">
            <label for="password">PASSWORD</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <input type="checkbox" id="showPassword">
            <label for="showPassword">Show Password</label>
        </div>

        <div class="input-group">
            <button type="submit" class="btn" name="login_user">Login</button>
        </div>

        <p>
            Don't have an account? <a href="../register/register.php">Sign up</a>
        </p>

        <p>
            <a href="../logins/forgot_password.php">Forgot Password?</a>
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