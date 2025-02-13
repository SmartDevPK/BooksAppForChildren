<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('location: login.php');
    exit();
}

// Database connection
$db = mysqli_connect('localhost', 'root', '', 'BooksAppForChildren');

if (!$db) {
    die("Database connection failed: " . mysqli_escape_string($db, mysqli_connect_error()));
}

// Fetch user data from the database
$email = $_SESSION['email'];
$query = "SELECT * FROM registration WHERE email='$email' LIMIT 1";
$result = mysqli_query($db, $query);

if ($result && mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
} else {
    die("User not found.");
}
?>