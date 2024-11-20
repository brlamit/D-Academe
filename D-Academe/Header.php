

<?php
// Start session only if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define the getAccountAddress function only if it’s not already defined
if (!function_exists('getAccountAddress')) {
    function getAccountAddress() {
        return $_SESSION['account'] ?? null;
    }
}

// Define the displayBalance function only if it’s not already defined
if (!function_exists('displayBalance')) {
    function displayBalance($balance) {
        return number_format((float)$balance, 4, '.', '');
    }
}

$account = getAccountAddress();
$tokenBalance = $_SESSION['tokenBalance'] ?? '0';
$ethBalance = $_SESSION['ethBalance'] ?? '0';
?>
<header class="w-full flex items-center justify-between py-4 px-6 fixed top-0 left-0 right-0 z-50 bg-gray-900 bg-opacity-80 backdrop-blur-lg text-white">
    <!-- Logo -->
    <h1 class="text-3xl font-bold tracking-wide hover:scale-105 transition-transform duration-300">D-Academe</h1>
    
    <!-- Navigation (Hamburger menu on small screens) -->
    <nav class="hidden md:flex flex-1 justify-center">
        <ul class="flex gap-8">
            <li><a href="?page=home" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Home</a></li>
            <li><a href="?page=buy-token" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Buy Token</a></li>
            <li><a href="?page=buy-course" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Buy Course</a></li>
            <li><a href="?page=live-class" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Live Class</a></li>
            <li><a href="?page=enrolled-course" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Enrolled Course</a></li>
            <li><a href="?page=about" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">About</a></li>
            <li><a href="?page=help" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Help</a></li>
        </ul>
    </nav>

    <!-- Mobile Menu Button (Hamburger icon) -->
    <div class="md:hidden flex items-center">
        <button id="hamburger" class="text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Wallet Info and Connect Button -->
    <div class="flex items-center gap-6">
        <?php if ($account): ?>
            <div class="text-right">
                <p class="font-medium">Tokens: <strong><?= $tokenBalance ?></strong></p>
                <p class="font-medium">Address: <strong><?= substr($account, 0, 6) . '....' . substr($account, -4) ?></strong></p>
                <p class="font-medium">Balance: <strong><?= displayBalance($ethBalance) ?> ETH</strong></p>
            </div>
            <form action="disconnect.php" method="POST">
                <button class="bg-red-500 text-white py-2 px-4 rounded-full hover:bg-red-700 transition-colors duration-200 shadow-md hover:shadow-lg">Disconnect</button>
            </form>
        <?php else: ?>
            <button onclick="connectWallet(event)" class="bg-green-600 text-white py-2 px-4 rounded-full hover:bg-green-700 transition-colors duration-200 shadow-md hover:shadow-lg">Connect Wallet</button>
        <?php endif; ?>
    </div>
</header>

<script>
    // Toggle mobile menu visibility
    document.getElementById('hamburger').addEventListener('click', function() {
        var mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>

<style>
    /* Ensure the header is sticky and adjusts properly when scrolling */
    header {
        background-color: rgba(0, 0, 0, 0.8); /* Darker background when scrolled */
        backdrop-filter: blur(10px); /* Add blur for glass effect */
        transition: background-color 0.3s ease;
    }
    /* Enhance hover and color contrast for navigation links */
    header ul li a {
        color: #ffffff;
    }
    header ul li a:hover {
        color: #00d1b2;
    }
    /* Button hover effect */
    button:hover {
        transform: scale(1.05);
    }
</style>






<!-- JavaScript for connecting the wallet and handling AJAX -->
<script src="https://cdn.jsdelivr.net/npm/web3/dist/web3.min.js"></script>
<script>
    async function connectWallet(event) {
    event.preventDefault();  // Prevent form submission
    if (window.ethereum) {
        try {
            const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
            const account = accounts[0];
            document.cookie = `account=${account}`;
            location.reload();  // Reload the page after setting the cookie
        } catch (error) {
            console.error("Error connecting to wallet:", error);
            alert("Failed to connect wallet. Please try again.");
        }
    } else {
        alert('Please install MetaMask.');
    }
}


    function saveAccountToSession(account) {
        // Send AJAX request to server to set session
        fetch('save_account.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ account: account })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Account saved to session.");
            } else {
                console.error("Failed to save account to session.");
            }
        })
        .catch(error => console.error("Error:", error));
    }
</script>


<style>
/* Additional styling to enhance the header */
header {
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
    backdrop-filter: blur(5px); /* Adds a blur effect */
}


header h1:hover {
    color: #ffffff;
    transform: scale(1.05);
}

header ul li a:hover {
    color: #ffffff;
}

button:hover {
    transform: scale(1.05);
}
</style>
