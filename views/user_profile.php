<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('location: login.php');
    exit();
}

// Display success message if set
if (isset($_SESSION['success'])) {
    echo "<div style='color: green; text-align: center;'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']); // Clear the message after displaying it
}

// Database connection
$db = mysqli_connect('localhost', 'root', '', 'BooksAppForChildren');
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch user data from the database
$email = $_SESSION['email'];
$query = "SELECT * FROM registration WHERE email='$email' LIMIT 1";
$result = mysqli_query($db, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    die("User not found.");
}

// Fetch free books from the database (example data)
$free_books_query = "SELECT * FROM books WHERE type='free' LIMIT 5"; // Adjust query as needed
$free_books_result = mysqli_query($db, $free_books_query);
$free_books = [];
if ($free_books_result) {
    while ($row = mysqli_fetch_assoc($free_books_result)) {
        $free_books[] = $row;
    }
}

// Fetch paid books from the database (example data)
$paid_books_query = "SELECT * FROM books WHERE type='paid' LIMIT 5"; // Adjust query as needed
$paid_books_result = mysqli_query($db, $paid_books_query);
$paid_books = [];
if ($paid_books_result) {
    while ($row = mysqli_fetch_assoc($paid_books_result)) {
        $paid_books[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .profile-container h2 {
            text-align: center;
        }
        .profile-container p {
            font-size: 18px;
            margin: 10px 0;
        }
        .books-section {
            margin-top: 30px;
        }
        .books-section h3 {
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
        }
        .book-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        .book-item {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            width: calc(33.33% - 20px);
            box-sizing: border-box;
            text-align: center;
        }
        .book-item img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .book-item h4 {
            margin: 10px 0;
        }
        .book-item p {
            margin: 5px 0;
        }
        .book-item a {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .book-item a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>User Profile</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
        <p><strong>Country:</strong> <?php echo htmlspecialchars($user['country']); ?></p>
        <a href="edit_profile.php">Edit Profile</a> | <a href="../logins/logout.php">Logout</a>

        <!-- Free Books Section -->
        <div class="books-section">
            <h3>Free Books</h3>
            <div class="book-list">
                <?php if (!empty($free_books)): ?>
                    <?php foreach ($free_books as $book): ?>
                        <div class="book-item">
                            <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                            <h4><?php echo htmlspecialchars($book['title']); ?></h4>
                            <p><?php echo htmlspecialchars($book['author']); ?></p>
                            <a href="<?php echo htmlspecialchars($book['download_link']); ?>" target="_blank">Download</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No free books available.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Paid Books Section -->
        <div class="books-section">
            <h3>Paid Books</h3>
            <div class="book-list">
                <?php if (!empty($paid_books)): ?>
                    <?php foreach ($paid_books as $book): ?>
                        <div class="book-item">
                            <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                            <h4><?php echo htmlspecialchars($book['title']); ?></h4>
                            <p><?php echo htmlspecialchars($book['author']); ?></p>
                            <a href="<?php echo htmlspecialchars($book['purchase_link']); ?>" target="_blank">Buy Now</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No paid books available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>