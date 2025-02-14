<?php
// API endpoint to fetch all countries
$api_url = "https://restcountries.com/v3.1/all";

// Fetch data from the API
$response = file_get_contents($api_url);

if ($response === FALSE) {
    die("Failed to fetch data from the API.");
}

// Decode the JSON response
$countries = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Failed to decode JSON response.");
}

// Extract country names
$country_names = [];
foreach ($countries as $country) {
    $country_names[] = $country['name']['common']; // Get the common name of the country
}

// Sort country names alphabetically
sort($country_names);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOK APP FOR CHILDREN</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="header">
        <h1>BOOK APP FOR CHILDREN REGISTRATION PAGE</h1>
    </div>

    <form method="post" action="../server.php">
        <div class="input-group">
            <label>NAME</label>
            <input type="text" name="name" placeholder="Name">
        </div>

        <div class="input-group">
            <label>EMAIL</label>
            <input type="text" name="email" placeholder="Email">
        </div>

        <div class="input-group">
            <label>PHONE NUMBER</label>
            <input type="number" name="phone_number" placeholder="Phone Number">
        </div>

        <div class="input-group">
            <label>SELECT COUNTRY:</label>
            <select name="country" required>
                <option value="" disabled selected>Select your country</option>
                <?php
                // Populate the dropdown with country names
                foreach ($country_names as $country) {
                    echo "<option value='$country'>$country</option>";
                }
                ?>
            </select>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Password">
        </div>

        <div class="input-group">
            <button type="submit" class="btn" name="reg_user">Register</button>
        </div>

        <p>
            Already a member? <a href="../logins/login.php">Sign in</a>
        </p>
    </form>
</body>
</html>