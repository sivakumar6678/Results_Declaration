<?php
session_start();

include('config.php');

// Function to sanitize user input
function sanitizeInput($input) {
    // You can add more validation or sanitization as needed
    return htmlspecialchars(trim($input));
}

// Retrieve user input from the form
$username = sanitizeInput($_POST["username"]);
$password = sanitizeInput($_POST["password"]);

// You may need to hash passwords and compare them in a secure way
// For this example, I'm assuming plaintext passwords for simplicity
$sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Successful login
    $_SESSION["admin"] = $username;
} else {
    // Invalid login
    header("Location: admin.php?error=1"); // Redirect with an error code
    exit;
}

// Close the database connection
$conn->close();

// Redirect back to adminportal.php
header("Location: adminportal.php");
exit();
?>
