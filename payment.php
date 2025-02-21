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
    die("Database connection failed: " . mysqli_connect_error());
}

// Get the book ID from the URL
$book_id = $_GET['book_id'] ?? 0;
if (!$book_id) {
    die("Invalid book ID.");
}

// Fetch user ID from session
$email = $_SESSION['email'];
$query = "SELECT id FROM registration WHERE email='$email' LIMIT 1";
$result = mysqli_query($db, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['id'];
} else {
    die("User not found.");
}

// Simulate payment processing (replace with actual payment gateway integration)
$payment_status = 'completed'; // Assume payment is successful

// Update the database
$query = "INSERT INTO user_payments (user_id, book_id, payment_status) VALUES (?, ?, ?)";
$stmt = $db->prepare($query);
$stmt->bind_param("iis", $user_id, $book_id, $payment_status);
$stmt->execute();

// Redirect back to the profile page
$_SESSION['success'] = "Payment successful! You can now access the book.";
header('location: ../readBook.php');
exit();
?>