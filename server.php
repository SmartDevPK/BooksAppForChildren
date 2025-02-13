<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Initializing variables
$name = "";
$email = "";
$phone_number = "";
$country = "";
$errors = []; 

// Database connection
$db = mysqli_connect('localhost', 'root', '', 'BooksAppForChildren');

if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Function to validate password strength
function isStrongPassword($password) {
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+=\[\]:;"\'<>,.?\/\\|{}-]).{8,}$/';
    return preg_match($pattern, $password);
}

// REGISTER USER
if (isset($_POST['reg_user'])) {
    // Sanitize and escape user inputs
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $phone_number = mysqli_real_escape_string($db, $_POST['phone_number']);
    $country = mysqli_real_escape_string($db, $_POST['country']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password']);

    // Form validation: Ensure all fields are filled
    if (empty($name)) { array_push($errors, "Name is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($phone_number)) { array_push($errors, "Phone Number is required"); }
    if (empty($country)) { array_push($errors, "Country is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }

    // Validate email format and domain
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    } else {
        $domain = substr(strrchr($email, "@"), 1); 
        if (!empty($domain)) {
            if (!function_exists('getmxrr')) {
                $errors[] = "MX record validation is not supported on this server.";
            } else {
                $mxhosts = [];
                if (getmxrr($domain, $mxhosts) === false) {
                    $errors[] = "Invalid email domain.";
                }
            }
        }
    }

    // Check if email already exists
    $user_check_query = "SELECT * FROM registration WHERE email='$email' LIMIT 1";
    $existingEmail = mysqli_query($db, $user_check_query);

    if (!$existingEmail) {
        die("Query failed: " . mysqli_error($db)); 
    }

    $user = mysqli_fetch_assoc($existingEmail);
    if ($user) { 
        array_push($errors, "Email already exists");
    }

    // Validate password strength
    if (!isStrongPassword($password_1)) {
        array_push($errors, "Password must be at least 8 characters long and include uppercase, lowercase, a number, and a special character.");
    }

    // If there are errors, display them and stop execution
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>"; 
        }
        exit();
    }

    // Hash the password before storing in the database
    $password = password_hash($password_1, PASSWORD_DEFAULT);

    // Insert user into the database
    $query = "INSERT INTO registration (`name`, `email`, `country`, `password`, `phone_number`) 
              VALUES ('$name', '$email', '$country', '$password', '$phone_number')";

    $result = mysqli_query($db, $query);
    if (!$result) {
        echo "Database Error: " . mysqli_error($db);
    } else {
        $_SESSION['name'] = $name;
        $_SESSION['success'] = "You are now logged in";
        header('location: logins/login.php');
        exit();
    }
}
?>