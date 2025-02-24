<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Database Connection (Corrected)
$db = mysqli_connect('localhost', 'root', '', 'BooksAppForChildren');

if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

$message = ""; // Variable to store messages

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $title = $_POST['title'];
    $uploadDir = "uploads/books/"; // Folder to store files

    // ✅ Fix: Check if the directory exists correctly
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create folder if not exists
    }

    $fileName = basename($_FILES["file"]["name"]);
    $filePath = $uploadDir . $fileName;
    $fileType = pathinfo($filePath, PATHINFO_EXTENSION);

    // Allow only specific file types (PDF, EPUB, DOCX, etc.)
    $allowedTypes = ['pdf', 'epub', 'docx'];

    if (in_array(strtolower($fileType), $allowedTypes)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
            // Store file path in database using procedural MySQLi
            $stmt = mysqli_prepare($db, "INSERT INTO books (title, file_path) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ss", $title, $filePath);

            if (mysqli_stmt_execute($stmt)) {
                $insertId = mysqli_insert_id($db);
                $message = "✅ Book uploaded successfully! <a href='read.php?id=$insertId'>Read Now</a>";
            } else {
                $message = "❌ Error: " . mysqli_error($db);
            }
        } else {
            $message = "❌ File upload failed.";
        }
    } else {
        $message = "❌ Invalid file type. Only PDF, EPUB, and DOCX allowed.";
    }
}

mysqli_close($db);
?>
