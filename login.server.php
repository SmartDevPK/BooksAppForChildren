<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$errors = []; 

// Database connection
$db = mysqli_connect('localhost', 'root', '', 'BooksAppForChildren');

if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

// LOGIN USER
if (isset($_POST['login_user'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);


    // Form validation
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    // If no errors, proceed with login
    if (count($errors) == 0) {
        // Fetch user from the database
        $query = "SELECT * FROM registration WHERE email='$email' LIMIT 1";
        $result = mysqli_query($db, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['success'] = "You are now logged in";

                // Redirect to a dashboard or home page
                header('location: views/user_profile.php');
                exit();
            } else {
                array_push($errors, "Incorrect password");
            }
        } else {
            array_push($errors, "Email not found");
        }
    }

    // Display errors
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
?>