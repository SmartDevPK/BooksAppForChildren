<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('location: login.php');
    exit();
}

// echo "Session Email: " . $_SESSION['email']; // Debugging

// Database connection
$db = mysqli_connect('localhost', 'root', '', 'BooksAppForChildren');
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch user data from the database
$email = mysqli_real_escape_string($db, $_SESSION['email']);
$query = "SELECT * FROM registration WHERE email='$email' LIMIT 1";

$result = mysqli_query($db, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($db)); // Debugging
}

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    // echo "<pre>";
    // print_r($user); // Debugging
    // echo "</pre>";
} else {
    die("User not found in the database."); // Debugging
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
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .profile-container h2 {
            text-align: center;
        }
        .profile-container img {
            display: block;
            margin: 0 auto;
            border-radius: 50%;
            width: 150px;
            height: 150px;
        }
        .profile-container p {
            font-size: 18px;
            margin: 10px 0;
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
        <a href="edit_profile.php">Edit Profile</a> | <a href="./logins/logout.php">Logout</a>
    </div>
</body>
</html>