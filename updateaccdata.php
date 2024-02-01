<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style/signup.css">
    <title>Update</title>
</head>
<body>
<header class="header">
        <nav class="nav">
            <a href="index.php">Account Creation</a>
            <a href="viewaccdata.php">View Account Data</a>
        </nav>
    </header>
    <div class="container">
       <div class="form-container" id="signup-form">
        <form method="POST" id="accform" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h2>UPDATE</h2>
            <label for="new-username">Old Username</label>
            <input type="text" id="UserName" name="UserName" required>
            <label for="new-username">New Username</label>
            <input type="text" id="newUN" name="newUN" required>
            <label for="new-email">Email</label>
            <input type="text" id="Email" name="Email" required>
            <label for="new-password">Password</label>
            <input type="password" id="Password" name="Password" required>
            <br>
            <input type="hidden" name="action" value="update">
            <button type="submit" value="submit">Update</button>
        </form>
       </div>
    <?php
    // Include common functions and connect to the database
    include 'common.php';
    $conn = connectToDatabase();

    // Handle form submissions
    login($conn);

    // Close the database connection
    $conn->close();
    ?>
    </div>
</body>
</html>
