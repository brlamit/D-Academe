<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['name'], $_SESSION['profile_picture']);
if ($isLoggedIn) {
    $name = $_SESSION['name'];
    $profilePicture = $_SESSION['profile_picture'];

    // Construct the full path
    $profilePicturePath = $_SERVER['DOCUMENT_ROOT'] . '/D-Academe/D-Academe/admin/login/' . $profilePicture;

    // Check if the file exists
    if (file_exists($profilePicturePath)) {
        $profilePictureUrl = '/D-Academe/D-Academe/admin/login/' . $profilePicture;
    } else {
        $profilePictureUrl = './assets/default-avatar.png'; // Fallback image
    }
} else {
    $profilePictureUrl = './assets/default-avatar.png'; // Default avatar for non-logged-in users
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
<!-- <body class="bg-gray-900 text-white"> -->

<header class=" rounded-lg shadow-lg w-full items-center justify-between py-2 flex px-6 fixed top-0 left-0 right-0 z-50 bg-gray-900 bg-opacity-80 backdrop-blur-xl text-white">
    <!-- Logo -->
    <div class="flex items-center gap-4">
        <a href="index.php" class="flex items-center gap-3">
            <img src="./assets/logo.png" alt="Logo" class="w-24 h-auto object-contain hover:scale-105 transition-transform duration-300 opacity-80 hover:opacity-100">
        </a>
    </div>
    
    <!-- Navigation -->
    <nav id="mobile-menu" class="hidden md:flex flex-1 justify-center">
        <ul class="flex gap-8">
            <li><a href="?page=home" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Home</a></li>
            <li><a href="?page=live-class" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Live Class</a></li>
            <li><a href="?page=courses" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Courses</a></li>
            <li><a href="?page=about" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">About</a></li>
            <li><a href="?page=help" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Help</a></li>
        </ul>
    </nav>

    <!-- Profile Section -->
<div class="flex items-center gap-6">
    <?php if ($isLoggedIn): ?>
        <!-- Logged-in user profile -->
        <div id="profileSection" class="relative">
            <button class="flex items-center gap-2" onclick="toggleDropdown()">
                <img src="<?php echo htmlspecialchars($profilePictureUrl); ?>" 
                     alt="Profile" 
                     class="w-10 h-10 rounded-full object-cover" 
                     onerror="this.onerror=null; this.src='./assets/default-avatar.png';">
            </button>

            <!-- Dropdown Menu -->
            <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-gray-800 text-white rounded-md shadow-lg hidden">
                <div class="flex items-center gap-2 px-4 py-2">
                    <img src="<?php echo htmlspecialchars($profilePictureUrl); ?>" 
                         alt="Profile" 
                         class="w-10 h-10 rounded-full object-cover" 
                         onerror="this.onerror=null; this.src='./assets/default-avatar.png';">
                    <span class="text-sm"><?php echo htmlspecialchars($name); ?></span>
                </div>
                <ul>
                    <li><a href="?page=view_profile" class="block px-4 py-2 hover:bg-gray-700 transition-colors duration-200">View Profile</a></li>
                    <li><a href="./login/logout.php" class="block px-4 py-2 hover:bg-gray-700 transition-colors duration-200">Sign Out</a></li>
                    </ul>
            </div>
        </div>
    <?php else: ?>
        <!-- Login button for non-logged-in users -->
        <a href="./login/admin_login.html" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors duration-200">
            Login
        </a>
    <?php endif; ?>
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
