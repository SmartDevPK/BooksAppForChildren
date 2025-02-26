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

// Fetch all books
$books_query = "SELECT * FROM books";
$books_result = mysqli_query($db, $books_query);
$books = [];
if ($books_result) {
    while ($row = mysqli_fetch_assoc($books_result)) {
        $books[] = $row;
    }
}

// Function to check if the user has paid for a specific book
function hasUserPaidForBook($db, $user_id, $book_id)
{
    $query = "SELECT payment_status FROM user_payments WHERE user_id = ? AND book_id = ? AND payment_status = 'completed' LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

$user_id = $user['id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .book-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            width: 250px;
            display: inline-block;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .book-item img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }

        .book-title {
            font-size: 18px;
            font-weight: bold;
        }

        .btn {
            display: block;
            padding: 10px;
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .paid {
            background-color: #ff5733;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>All Books</h2>

        <!-- Display Books -->
        <div class="books-section">
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                    <div class="book-item">
                        <img src="<?php echo htmlspecialchars($book['cover_image']); ?>"
                            alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <p class="book-title"><?php echo htmlspecialchars($book['title']); ?></p>
                        <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                        <p><?php echo htmlspecialchars($book['summary']); ?></p>

                        <?php if ($book['type'] === 'free'): ?>
                            <a href="view_book.php?book_id=<?php echo $book['id']; ?>" class="btn">Read for Free</a>
                        <?php else: ?>
                            <?php if (hasUserPaidForBook($db, $user_id, $book['id'])): ?>
                                <a href="view_book.php?book_id=<?php echo $book['id']; ?>" class="btn">Read Now</a>
                            <?php else: ?>
                                <a href="../views/payment.php?book_id=<?php echo $book['id']; ?>" class="btn paid">Pay to Access</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No books available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>