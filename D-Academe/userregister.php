<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Update as needed
$password = "";     // Update as needed
$dbname = "my_database"; // Update as needed

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative m-4' role='alert'>
            <strong class='font-bold'>Connection Failed!</strong>
            <span class='block sm:inline'> " . htmlspecialchars($conn->connect_error) . "</span>
          </div>");
}

// Initialize variables for feedback messages
$successMessage = "";
$errorMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $contact = htmlspecialchars(trim($_POST['contact'])) ;

    // Handle file upload
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Create directory if it doesn't exist
    }

    $uploadFile = $uploadDir . basename($_FILES["picture"]["name"]);
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    $uniqueFilename = $uploadDir . uniqid() . '.' . $imageFileType;

    // Validate file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $allowedTypes)) {
        if ($_FILES["picture"]["size"] <= 5 * 1024 * 1024) { // Max 5MB
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $uniqueFilename)) {
                // Prepare and execute database insertion
                $stmt = $conn->prepare("INSERT INTO users (name, email, contact, picture) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $email, $contact, $uniqueFilename);

                if ($stmt->execute()) {
                    // Save success message to a session variable
                    session_start();
                    $_SESSION['successMessage'] = "Record saved successfully!";
                    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to prevent form resubmission
                    exit;
                } else {
                    $errorMessage = "Error saving record: " . htmlspecialchars($stmt->error);
                }
                $stmt->close();
            } else {
                $errorMessage = "Error uploading file.";
            }
        } else {
            $errorMessage = "File size exceeds the 5MB limit.";
        }
    } else {
        $errorMessage = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
    }
}

// Handle success message after redirection
session_start();
if (isset($_SESSION['successMessage'])) {
    $successMessage = $_SESSION['successMessage'];
    unset($_SESSION['successMessage']);
}

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Form Submission</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full items-center mt-24">
        <h2 class="text-3xl font-semibold text-center text-indigo-600 mb-6">Register Your Details</h2>

        <!-- Success Message -->
        <?php if ($successMessage): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline"><?php echo htmlspecialchars($successMessage); ?></span>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if ($errorMessage): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?php echo htmlspecialchars($errorMessage); ?></span>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="name" id="name" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" id="email" required
                       placeholder="e.g., example@domain.com"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Contact Number -->
            <div>
                <label for="contact" class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="tel" name="contact" id="contact" required pattern="[0-9]{10}"
                       placeholder="e.g., 98********"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Upload Picture -->
            <div>
                <label for="picture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                <input type="file" name="picture" id="picture" accept="image/*" required
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-700">
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center">
                <button type="submit"
                        class="py-2 px-6 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                    Register
                </button>
            </div>
        </form>
    </div>
</body>

</html>
