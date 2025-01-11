<?php
require_once 'dbconnection.php'; // Include the database connection

// Start the session to access the logged-in user's data
session_start();

// Check if the user is logged in by checking the session email
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    // If no session email, redirect the user to login page
    header("Location: admin_login.html");
    exit;
}

$email = $_SESSION['email']; // Get the logged-in user's email

// Debugging: Log the email being used for deletion
error_log("Attempting to delete user with email: " . $email);

try {
    // Prepare the DELETE SQL statement using PDO
    $stmt = $pdo->prepare("DELETE FROM admin_login WHERE email = :email");

    // Bind the email parameter using PDO
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    // Execute the query
    if ($stmt->execute()) {
        // Commit the transaction
        $pdo->commit();
        // Destroy the session to log the user out
        session_destroy();

        // Redirect to the login page with a success message
        header("Location: index.php?message=deleted");
        exit;
    } else {
        // If query fails, rollback transaction
        $pdo->rollBack();
        error_log("Failed to execute delete query for email: " . $email);

        // Redirect with an error message
        header("Location: index.php?message=error");
        exit;
    }
} catch (PDOException $e) {
    // Rollback the transaction if an exception occurs
    $pdo->rollBack();
    error_log("Error deleting user: " . $e->getMessage());

    // Redirect to index with an exception error message
    header("Location: index.php?message=exception");
    exit;
}
?>
