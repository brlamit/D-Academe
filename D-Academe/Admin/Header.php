<?php
session_start();

// Ensure the session data exists before using it
if (isset($_SESSION['email'], $_SESSION['name'], $_SESSION['profile_picture'])) {
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    // Adjust the path for profile picture
    $profilePicture = $_SERVER['DOCUMENT_ROOT'] . '/admin/login/uploads/profile_pictures' . $_SESSION['profile_picture'];
    // Check if file exists
    if (!file_exists($profilePicture)) {
        $profilePicture = './assets/default-avatar.png'; // Fallback if file is not found
    }
} else {
    // Default values if session data is not set
    $email = 'Not logged in';
    $name = 'Guest';
    $profilePicture = './assets/default-avatar.png'; // Fallback image
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>
<body class="bg-gray-900 text-white">

<header class="flex rounded-full items-center justify-between py-2 px-6 fixed top-3 left-6 right-4 z-50 bg-gray-900 bg-opacity-80 backdrop-blur-xl text-white">
    <!-- Logo -->
    <div class="flex items-center gap-4">
        <a href="index.php" class="flex items-center gap-3">
            <img src="./assets/logo.png" alt="Logo" class="w-32 h-auto object-contain hover:scale-105 transition-transform duration-300 opacity-80 hover:opacity-100">
        </a>
    </div>
    
    <!-- Navigation -->
    <nav id="mobile-menu" class="hidden md:flex flex-1 justify-center">
        <ul class="flex gap-8">
            <li><a href="?page=home" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Home</a></li>
            <li><a href="?page=live-class" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Live Class</a></li>
            <li><a href="./CourseUpload/my-courses.php" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">My Courses</a></li>
            <li><a href="?page=about" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">About</a></li>
            <li><a href="?page=help" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Help</a></li>
        </ul>
    </nav>

    <!-- Profile Section -->
    <div class="flex items-center gap-6">
        <div id="profileSection" class="relative">
            <button class="flex items-center gap-2" onclick="toggleDropdown()">
                <!-- Profile picture -->
                <img id="profilePicture" 
                     src="<?php echo htmlspecialchars($profilePicture) . '?v=' . time(); ?>" 
                     alt="Profile" 
                     class="w-10 h-10 rounded-full object-cover" 
                     onerror="this.onerror=null; this.src='./assets/default-avatar.png';">
            </button>

            <!-- Dropdown Menu -->
            <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-gray-800 text-white rounded-md shadow-lg hidden">
                <!-- Profile Section -->
                <div class="flex items-center gap-2 px-4 py-2">
                    <img id="dropdownProfilePicture" 
                         src="<?php echo htmlspecialchars($profilePicture) . '?v=' . time(); ?>" 
                         alt="Profile" 
                         class="w-8 h-8 rounded-full object-cover" 
                         onerror="this.onerror=null; this.src='./assets/default-avatar.png';">
                    <span class="text-sm"><?php echo htmlspecialchars($name); ?></span>
                </div>

                <!-- Menu Items -->
                <ul>
                    <li><a href="profile.php" class="block px-4 py-2 hover:bg-gray-700 transition-colors duration-200">View Profile</a></li>
                    <li><a href="../index.php" class="block px-4 py-2 hover:bg-gray-700 transition-colors duration-200">Sign Out</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

<script>
    // Toggle the dropdown menu visibility
    function toggleDropdown() {
        const dropdownMenu = document.getElementById('dropdownMenu');
        dropdownMenu.classList.toggle('hidden');
    }
</script>

</body>
</html>
