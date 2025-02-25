<?php
// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BooksAppForChildren";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $type = !empty($_POST['type']) ? $_POST['type'] : 'Unknown';
    $summary = !empty($_POST['summary']) ? trim($_POST['summary']) : 'No summary available';

    // Upload directories
    $uploadDir = "uploads/upload/image.jpg";
    $protectedDir = "protected/";

    // Ensure directories exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    if (!file_exists($protectedDir)) {
        mkdir($protectedDir, 0777, true);
    }

    // Handle Cover Image Upload
    $coverImagePath = "";
    if (!empty($_FILES["cover_image"]["name"])) {
        $coverImageName = basename($_FILES["cover_image"]["name"]);
        $coverImagePath = $uploadDir . $coverImageName;



        if (!move_uploaded_file($_FILES["cover_image"]["tmp_name"], $coverImagePath)) {
            die("❌ Error: Failed to upload cover image.");
        }
    } else {
        die("❌ Error: No cover image uploaded.");
    }

    // Handle PDF Upload
    $bookPdfPath = "";
    if (!empty($_FILES["book_pdf"]["name"])) {
        $bookPdfName = basename($_FILES["book_pdf"]["name"]);
        $bookPdfPath = $uploadDir . $bookPdfName;

        // Validate File Type (Only PDF)
        if ($_FILES["book_pdf"]["type"] !== "application/pdf") {
            die("❌ Error: Invalid file type. Only PDFs are allowed.");
        }

        if (!move_uploaded_file($_FILES["book_pdf"]["tmp_name"], $bookPdfPath)) {
            die("❌ Error: Failed to upload book PDF.");
        }

        // Move PDF to protected directory
        $protectedPdfPath = $protectedDir . $bookPdfName;
        if (!rename($bookPdfPath, $protectedPdfPath)) {
            die("❌ Error: Failed to move PDF to protected folder.");
        }
        $bookPdfPath = $protectedPdfPath; // Update path
    } else {
        die("❌ Error: No book PDF uploaded.");
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO books (title, author, cover_image, book_pdf, type, summary) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $author, $coverImagePath, $bookPdfPath, $type, $summary);

    if ($stmt->execute()) {
        $insertId = $conn->insert_id;
        echo "✅ Book uploaded successfully! <a href='/BooksAppForChildren/paypal/readbook.php?id=$insertId'>Read Now</a>";
    } else {
        echo "❌ Error inserting into database: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        input,
        select,
        button,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            text-align: left;
        }

        button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }

        button:hover {
            background: #218838;
        }

        input[type="file"] {
            border: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Upload a Book</h1>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Enter Book Title" required>
            <input type="text" name="author" placeholder="Enter Author Name" required>

            <select name="type" required>
                <option value="">Select Book Type</option>
                <option value="Fiction">Fiction</option>
                <option value="Non-Fiction">Non-Fiction</option>
                <option value="Educational">Educational</option>
            </select>

            <label>Upload Cover Image:</label>
            <input type="file" name="cover_image" accept="image/*" required>

            <label>Upload PDF Book:</label>
            <input type="file" name="book_pdf" accept="application/pdf" required>

            <textarea name="summary" placeholder="Enter book summary" required></textarea>

            <button type="submit" name="submit">Upload</button>
        </form>
    </div>
</body>

</html>