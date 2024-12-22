<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $age = htmlspecialchars($_POST['age']);
    $photo = $_FILES['photo'];

    // Handle file upload
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($photo['name']);

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($photo['tmp_name'], $uploadFile)) {
        echo "<h1>Registration Successful!</h1>";
        echo "<p>Name: $name</p>";
        echo "<p>Email: $email</p>";
        echo "<p>Age: $age</p>";
        echo "<p>Photo: <img src='$uploadFile' alt='User Photo' style='max-width:150px;'></p>";
    } else {
        echo "<h1>Error uploading photo. Please try again.</h1>";
    }
} else {
    echo "<h1>Invalid Request</h1>";
}
?>
