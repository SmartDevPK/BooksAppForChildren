<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Start the session
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
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

$query = "SELECT * FROM books WHERE id = $book_id LIMIT 1";
$result = mysqli_query($db, $query);
if (!$result || mysqli_num_rows($result) === 0) {
    die("Book not found.");
}
$book = mysqli_fetch_assoc($result);

// Fetch prices from the database
$usd_price = $book['price_usd'] ?? 10.00; // Default to $10.00 if not set
$ngn_price = $book['price_ngn'] ?? 5000;  // Default to ₦5000 if not set
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment for <?php echo htmlspecialchars($book['title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .payment-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            margin: 10px 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h1>Payment for <?php echo htmlspecialchars($book['title']); ?></h1>
        <p>Price (USD): $10.00<?php echo htmlspecialchars($usd_price); ?></p>
        <p>Price (NGN): ₦5000<?php echo htmlspecialchars($ngn_price); ?></p>

        <!-- PayPal Button Container -->
        <div id="paypal-button-container"></div>

        <!-- Currency Selection Form -->
        <form method="POST" action="../views/process.payment.php">
            <input type="hidden" name="book_title" value="<?php echo htmlspecialchars($book['title']); ?>">
            <label for="currency">Select Currency:</label>
            <select id="currency" name="currency" required>
                <option value="USD">USD</option>
                <option value="NGN">NGN</option>
            </select>
            <br><br>
            <button type="submit">Pay with Debit Card</button>
        </form>
    </div>

    <!-- PayPal SDK Script -->
    <!-- <script src="https://www.paypal.com/sdk/js?$client_id =$client_secret&currency=USD&enable-funding=card"></script> -->
    <!-- <script>
        // Render PayPal Button
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $usd_price; ?>' // Dynamic price from PHP
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);
                    // Redirect to success page
                    window.location.href = 'success.php';
                });
            },
            onError: function(err) {
                console.error('Payment failed:', err);
                alert('Payment failed. Please try again.');
            }
        }).render('#paypal-button-container'); -->
    </script>
</body>
</html>