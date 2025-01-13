<?php
session_start();  // Start the session

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('dbconnection.php');  // Assuming this file contains your database connection code


if (!isset($_SESSION['email'])) {
    // Redirect to login page if user is not logged in
    header("Location: ./login/user_login.html");
    exit();
}
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after displaying it once
}

// Get user details from session
$user_email = $_SESSION['email'];

// Fetch user details from the database (including dob, gender, address)
$sql = "SELECT name, email, phone_number, profile_picture, dob, gender, address FROM user_login WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_email);
$stmt->execute();
$stmt->bind_result($name, $email, $phone_number, $profile_picture, $dob, $gender, $address);
$stmt->fetch();
$stmt->close();

// Fallback to default images if not set
$profile_picture = $profile_picture ?? './assets/default-avatar.png';

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>User Profile</title>
    <script>
        // Function to trigger file input click
        function toggleFileInput(inputId) {
            var fileInput = document.getElementById(inputId);
            fileInput.click();
        }

        // Function to preview the selected image
        function previewImage(input) {
            var file = input.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profileImage').src = e.target.result;
                }
                reader.readAsDataURL(file); // Convert the file to a data URL for preview
            }
        }
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 mt-10 rounded-lg shadow-lg w-full text-center">
        <h1 class="text-3xl font-semibold text-gray-800 mb-8">Profile Details</h1>


        <form action="updateprofile.php" method="POST" enctype="multipart/form-data" class="profile-details space-y-4 text-left">
            
        <!-- Profile Image -->
        <div class="profile-image mb-6">
            <img id="profileImage" src="<?php echo htmlspecialchars($profile_picture);  ?>" 
                 alt="Profile" 
                 class="w-32 h-32 rounded-lg object-cover mx-auto cursor-pointer"
                 onclick="toggleFileInput('profile_picture_input')" 
                 onerror="this.onerror=null; this.src='./assets/default-avatar.png';">
            <input type="file" id="profile_picture_input" name="profile_picture" class="mt-1 p-2 border rounded w-full hidden" onchange="previewImage(this)">
        </div>
            <p class="text-gray-700">
                <strong>Name:</strong>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" class="mt-1 p-2 border rounded w-full" disabled>
            </p>

            <p class="text-gray-700">
                <strong>Email:</strong>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="mt-1 p-2 border rounded w-full" disabled>
            </p>

            <p class="text-gray-700">
                <strong>Phone Number:</strong>
                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" class="mt-1 p-2 border rounded w-full" required>
            </p>

            <p class="text-gray-700">
                <strong>Date of Birth:</strong>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($dob); ?>" class="mt-1 p-2 border rounded w-full" required>
            </p>

            <p class="text-gray-700">
                <strong>Gender:</strong>
                <input type="text" name="gender" value="<?php echo htmlspecialchars($gender); ?>" class="mt-1 p-2 border rounded w-full" disabled>
            </p>

            <p class="text-gray-700">
                <strong>Address:</strong>
                <textarea name="address" class="mt-1 p-2 border rounded w-full" required><?php echo htmlspecialchars($address); ?></textarea>
            </p>

            <button type="submit" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-full">Update Profile</button>

            <p class="text-gray-700">
                <a href="./login/forgot_password-form.php" class="mt-4 px-6 py-2 bg-red-600 text-white rounded-full inline-block">
                    Change Password?
                </a>
            </p>
        </form>

        <p class="text-gray-700">
            <button type="button" onclick="showDeleteModal()" class="mt-4 px-6 py-2 bg-red-600 text-white rounded-full">
                Delete Account?
            </button>
        </p>

        <!-- Modal for Confirming Deletion -->
        <div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg text-center">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Are you sure you want to delete your account?</h2>
                <form action="deleteuser.php" method="POST">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <button type="submit" class="mt-4 px-6 py-2 bg-red-600 text-white rounded-full">Yes, Delete Account</button>
                    <button type="button" class="px-6 py-2 bg-gray-600 text-white rounded-full" onclick="hideDeleteModal()">Cancel</button>
                </form>
            </div>
        </div>
        <!-- Message Modal -->
<?php if (!empty($message)): ?>
    <div id="messageModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg text-center">
            <h2 class="text-xl font-semibold text-gray-800 mb-4"><?php echo $message; ?></h2>
            <button onclick="closeMessageModal()" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-full">OK</button>
        </div>
    </div>
<?php endif; ?>

<script>
    function closeMessageModal() {
        document.getElementById('messageModal').style.display = 'none';
    }
</script>

        <script>
            function showDeleteModal() {
                document.getElementById('deleteModal').classList.remove('hidden');
            }

            function hideDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
            }
        </script>

    </div>

</body>
</html>
