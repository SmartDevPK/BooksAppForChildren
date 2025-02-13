<?php
session_start();
$errors = [];
$user_id = "";

// Connect to database
$db = mysqli_connect('localhost', 'root', '', 'password-reset-php');

// Check connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// LOG USER IN
if (isset($_POST['login_user'])) {
    // Get email and password from login form
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Validate form
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    // If no errors in form, log user in
    if (count($errors) === 0) {
        $password = md5($password); // Hash the password (note: md5 is not secure, consider using password_hash() and password_verify())
        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $results = mysqli_query($db, $sql);

        if (mysqli_num_rows($results) == 1) { // Check if exactly one row matches
            $user = mysqli_fetch_assoc($results);
            $_SESSION['name'] = $user['name']; // Assuming you have a 'name' column in your users table
            $_SESSION['email'] = $email;
            $_SESSION['success'] = "You are now logged in";
            header('location: views/profile.php');
            exit();
        } else {
            array_push($errors, "Invalid email or password");
        }
    }
}
?>