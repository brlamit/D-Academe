<?php
session_start();
include('dbconnection.php');  // Assuming this file contains your database connection code

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if user is not logged in
    header("Location: ./login/admin_login.html");
    exit();
}

// Get the email from the POST request
if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Ensure that the email matches the session email (extra security)
    if ($email !== $_SESSION['email']) {
        echo "Unauthorized request!";
        exit();
    }

    // SQL query to delete the user from the database
    $sql = "DELETE FROM admin_login WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);

    if ($stmt->execute()) {
        // Destroy the session and redirect to the login page
        session_destroy();
        header("Location: ./login/admin_login.html");
        exit();
    } else {
        echo "Error deleting account: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Email not provided.";
}

$conn->close();
?>
