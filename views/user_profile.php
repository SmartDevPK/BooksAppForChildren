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

// Fetch user data
$email = $_SESSION['email'];
$query = "SELECT * FROM registration WHERE email='$email' LIMIT 1";
$result = mysqli_query($db, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    die("User not found.");
}

// Fetch free books
$free_books_query = "SELECT * FROM books WHERE type='free'";
$free_books_result = mysqli_query($db, $free_books_query);
$free_books = [];
if ($free_books_result) {
    while ($row = mysqli_fetch_assoc($free_books_result)) {
        $free_books[] = $row;
    }
}

// Fetch paid books
$paid_books_query = "SELECT * FROM books WHERE type='paid'";
$paid_books_result = mysqli_query($db, $paid_books_query);
$paid_books = [];
if ($paid_books_result) {
    while ($row = mysqli_fetch_assoc($paid_books_result)) {
        $paid_books[] = $row;
    }
}

// Function to check if the user has made any successful payment
function hasUserPaidForAnyBook($db, $user_id) {
    $query = "SELECT payment_status FROM user_payments WHERE user_id = ? AND payment_status = 'completed' LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

// Check if the user has paid for any book
$has_paid_for_any_book = hasUserPaidForAnyBook($db, $user['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paid Books</title>
    <style>
        .user-info {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .book-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            width: 200px;
            display: inline-block;
        }
        .btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .user-actions {
            margin-top: 10px;
        }
        .user-actions a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .user-actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Display User Information -->
    <div class="user-info">
        <h2>User Information</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
        <p><strong>Nigeria:</strong> <?php echo htmlspecialchars($user['country']); ?></p>
        <p><strong>Account Type:</strong> <?php echo htmlspecialchars($user['account_type']); ?></p>
        <p><strong>Payment Status:</strong> <?php echo $has_paid_for_any_book ? 'Paid' : 'Not Paid'; ?></p>

        <!-- Update Profile and Logout Links -->
        <div class="user-actions">
            <a href="../views/update_profile.php">Update Profile</a>
            <a href="../logins/logout.php">Logout</a>
        </div>
    </div>

    <!-- Display Paid Books -->
    <div class="books-section">
        <h3>Paid Books</h3>
        <div class="book-list">
            <?php if (!empty($paid_books)): ?>
                <?php foreach ($paid_books as $book): ?>
                    <div class="book-item">
                        <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" width="100">
                        <h4><?php echo htmlspecialchars($book['title']); ?></h4>
                        <p><?php echo htmlspecialchars($book['author']); ?></p>
                        <p><?php echo htmlspecialchars($book['summary']); ?></p>
                        <?php if ($has_paid_for_any_book): ?>
                            <!-- User has paid for at least one book, show all paid book content -->
                            <div class="paid-book-content">
                                <p>Book content goes here...</p>
                            </div>
                        <?php else: ?>
                            <!-- User hasn't paid for any book, show payment link -->
                            <a href="../payment.php?book_id=<?php echo $book['id']; ?>" class="btn">Pay to Access</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No paid books available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>