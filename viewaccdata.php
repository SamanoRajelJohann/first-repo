<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style/signup.css">
    <title>Account Creation</title>
</head>
<body>
    <header class="header">
        <nav class="nav">
            <a href="index.php">Account Creation</a>
        </nav>
    </header>
    <div class="container">
        <?php
        include 'common.php';
        $conn = connectToDatabase();
        // Handle form submissions
        login($conn);
        // Display Data
        displayAccountData($conn);
        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>
</html>
