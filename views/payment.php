<?php
// payment.php
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
if (!isset($_GET['book_id'])) {
    die("Book ID not provided.");
}
$book_id = intval($_GET['book_id']);

// Fetch book details
$query = "SELECT * FROM books WHERE id = $book_id LIMIT 1";
$result = mysqli_query($db, $query);
if (!$result || mysqli_num_rows($result) === 0) {
    die("Book not found.");
}
$book = mysqli_fetch_assoc($result);

// Handle payment processing (this is a placeholder)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulate a successful payment
    $user_id = $_SESSION['user_id']; // Ensure you store user_id in the session
    $payment_status = 'completed';
    $query = "INSERT INTO user_payments (user_id, book_id, payment_status) VALUES ($user_id, $book_id, '$payment_status')";
    if (mysqli_query($db, $query)) {
        header('location: ..views/payment.php'); // Redirect to paid books page
        exit();
    } else {
        die("Payment failed: " . mysqli_error($db));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
</head>
<body>
    <h1>Payment for <?php echo htmlspecialchars($book['title']); ?></h1>
    <p>Price: $<?php echo htmlspecialchars($book['price']); ?></p>
    <form method="POST">
        <label for="card_number">Card Number:</label>
        <input type="text" id="card_number" name="card_number" required>
        <br>
        <label for="expiry_date">Expiry Date:</label>
        <input type="text" id="expiry_date" name="expiry_date" required>
        <br>
        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv" required>
        <br>
        <button type="submit">Pay Now</button>
    </form>
</body>
</html>