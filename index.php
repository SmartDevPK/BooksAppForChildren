

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
        }
        form {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        .message {
            margin-top: 15px;
            font-weight: bold;
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

    <h2>Upload Book File</h2>

    <?php if (!empty($message)): ?>
        <p class="message"><?= $message; ?></p>
    <?php endif; ?>

    <form action="/BooksAppForChildren/uploads/upload.php" method="POST" enctype="multipart/form-data">
        <label for="title">Book Title:</label><br>
        <input type="text" name="title" required>
        <br><br>


        <label for="author">Author:</label><br>
        <input type="text" name="author" required> <!-- Make sure this field exists -->
        <br><br>
        
        <label for="file">Choose File:</label><br>
        <input type="file" name="file" required>
        <br><br>
        
        <button type="submit" name="submit">Upload</button>
    </form>

</body>
</html>
