<?php
ini_set('memory_limit', '512M');

function connectToDatabase(){
    $servername = "localhost";
    $username = "ITE601";
    $password = "dev";
    $dbname = "attendance monitoring";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
function executeQuery($conn, $sql, $params = []){
    try {
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        // Check if it's a SELECT query
        $isSelectQuery = stripos($sql, 'SELECT') === 0;

        if (!$isSelectQuery && !empty($params)) {
            // For non-SELECT queries, proceed with binding parameters
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();

        if ($isSelectQuery) {
            $result = $stmt->get_result();

            if ($result === FALSE) {
                throw new Exception($stmt->error);
            }

            return $result;
        }

        return true; // For non-SELECT queries
    } catch (Exception $e) {
        // Log the exception or handle it appropriately
        echo "Error: " . $e->getMessage();
    
        // Close the statement if it's not null
        if ($stmt !== null) {
            $stmt->close();
        }
    
        // Close the connection and reconnect
        $conn->close();
        $conn = connectToDatabase();
    
        // Retry the query
        return executeQuery($conn, $sql, $params);
    }    
}
function displayAccountData($conn){
    $sql_select = "SELECT * FROM accountsignup";
    $result = executeQuery($conn, $sql_select);
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<thead>";
        echo "<tr>
                <th>Username</th>
                <th>Email</th>
                <th>Password</th>
                <th>Action</th>
              </tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["UserName"] . "</td>";
            echo "<td>" . $row["Email"] . "</td>";
            echo "<td>" . $row["Password"] . "</td>";
            echo "<td>
                    <form action='updateaccdata.php' method='get'>
                        <input type='hidden' name='UserName' value='" . $row["UserName"] . "'>
                        <input type='hidden' name='fn' value='" . $row["Email"] . "'>
                        <input type='hidden' name='mnd' value='" . $row["Password"] . "'>
                        <input type='submit' value='Update' style='background-color: #007bff; color: #ffffff; border: none; padding: 8px 17px; border-radius: 4px; cursor: pointer;'>
                    </form>
                    <form method='post' action='".$_SERVER['PHP_SELF']."'>
                        <input type='hidden' name='UserName' value='".$row["UserName"]."'>
                        <input type='hidden' name='action' value='delete'>
                        <input type='submit' value='Delete' style='background-color: #dc3545; color: #ffffff; border: none; padding: 8px 19px; border-radius: 5px; cursor: pointer;'>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No records found.";
    }
}
function login($conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $UserName = $_POST["UserName"] ?? '';
        $Email = $_POST["Email"] ?? '';
        $Password = $_POST["Password"] ?? '';
        $action = $_POST["action"] ?? '';

        // Validate the action and handle accordingly
        if ($action == "insert") {
            $sql = "INSERT INTO accountsignup (UserName, Email, Password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $UserName, $Email, $Password);
            if ($stmt->execute()) {
                // Successful insertion
            } else {
                echo "Error inserting record: " . $stmt->error;
            }
            $stmt->close();
        } elseif ($action == "update") {
            $newUN = $_POST["newUN"] ?? '';
            $sql = "UPDATE accountsignup SET UserName=?, Email=?, Password=? WHERE UserName=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss",$newUN, $Email, $Password, $UserName);
            if ($stmt->execute()) {
                // Successful update
            } else {
                echo "Error updating record: " . $stmt->error;
            }
            $stmt->close();
        } elseif ($action == "delete") {
            $sql = "DELETE FROM accountsignup WHERE UserName=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $UserName);
            if ($stmt->execute()) {
                // Successful deletion
            } else {
                echo "Error deleting record: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

function loginuser($conn){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $UserName = $_POST['UserName'];
        $Password = $_POST['Password'];
        // Prepare SQL statement and bind parameter
        $sql = "SELECT * FROM accountsignup WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $UserName);       
        // Execute the statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            // Check if rows are returned
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Directly compare passwords
                if ($Password === $row['Password']) {
                    // Password is correct, redirect to home
                    header("Location: home.html");
                    exit;
                } else {
                    // Incorrect password
                    echo "Invalid password.";
                }
            } else {
                // No rows returned, hence invalid username
                echo "Invalid username.";
            }
        } else {
            // Error executing the SQL statement
            echo "Error: " . $stmt->error;
        }
        // Close the prepared statement
        $stmt->close();
    }
}










