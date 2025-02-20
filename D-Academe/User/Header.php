<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['name'], $_SESSION['profile_picture']);
if ($isLoggedIn) {
    $name = $_SESSION['name'];
    $profilePicture = $_SESSION['profile_picture'];

    // Construct the full path
    $profilePicturePath = $_SERVER['DOCUMENT_ROOT'] . '/D-Academe/D-Academe/user/login/' . $profilePicture;

    // Check if the file exists
    if (file_exists($profilePicturePath)) {
        $profilePictureUrl = '/D-Academe/D-Academe/user/login/' . $profilePicture;
    } else {
        $profilePictureUrl = './assets/default-avatar.png'; // Fallback image
    }
} else {
    $profilePictureUrl = './assets/default-avatar.png'; // Default avatar for non-logged-in users
}
?>
<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Store balance and account in session for display
function displayBalance($balance) {
    return number_format((float)$balance, 4, '.', '');
}

// Retrieve wallet data from session if available
$sessionAccount = isset($_SESSION['account']) ? $_SESSION['account'] : '';
$sessionEthBalance = isset($_SESSION['ethBalance']) ? $_SESSION['ethBalance'] : '0 ETH';
$sessionTokenBalance = isset($_SESSION['tokenBalance']) ? $_SESSION['tokenBalance'] : '0';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D-Academe</title>
    <script src="https://cdn.jsdelivr.net/npm/web3/dist/web3.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">

<header class=" rounded-lg shadow-lg w-full items-center justify-between py-2 flex px-6 fixed top-0 left-0 right-0 z-50 bg-gray-900 bg-opacity-80 backdrop-blur-xl text-white">
     <!-- Logo -->
    <div class="flex items-center gap-4">
        <a href="?page=home" class="flex items-center gap-3">
            <img src="../assets/logo.png" alt="D-Academe Logo" class="w-24 h-auto object-contain hover:scale-105 transition-transform duration-300 opacity-80 hover:opacity-100">
        </a>
    </div>

    <!-- Navigation (Hamburger menu on small screens) -->
    <nav id="mobile-menu" class="hidden md:flex flex-1 justify-center">
        <ul class="flex gap-8">
            <li><a href="index.php?page=home" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Home</a></li>
            <li><a href="index.php?page=buy-token" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Buy Token</a></li>
            <li><a href="index.php?page=buy-course" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Buy Course</a></li>
            <li><a href="index.php?page=live-class" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Live Class</a></li>
            <li><a href="index.php?page=enrolled-course" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Enrolled Course</a></li>
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

    <!-- Wallet Info and Profile Section -->
    <div class="flex items-center gap-7">
        <!-- Wallet Info -->
        <div id="walletInfo" class="text-right hidden">
            <p class="font-medium">Tokens: <strong id="tokenBalance">0</strong></p>
            <p class="font-medium">Address: <strong id="account"></strong></p>
            <p class="font-medium">Balance: <strong id="ethBalance">0 ETH</strong></p>
        </div>
        
        <?php if ($isLoggedIn): ?>
        <!-- Wallet Button (Only for logged-in users) -->
        <button id="walletButton" 
                class="bg-green-600 text-white py-2  px-4 rounded-full hover:bg-green-700 transition-colors duration-200 shadow-md hover:shadow-lg" 
                onclick="toggleWallet()">Connect Wallet
        </button>
        <?php endif; ?>
    

    <!-- Profile Section -->
    <div class="flex items-center gap-4">
        <?php if ($isLoggedIn): ?>
            <!-- Logged-in user profile -->
            <div id="profileSection" class="relative">
                <button class="flex items-center gap-2" onclick="toggleDropdown()">
                    <img src="<?php echo htmlspecialchars($profilePicture); ?>" 
                         alt="Profile" 
                         class="w-10 h-10 rounded-full object-cover" 
                         onerror="this.onerror=null; this.src='./assets/default-avatar.png';">
                </button>

                <!-- Dropdown Menu -->
                <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-gray-800 text-white rounded-md shadow-lg hidden">
                    <div class="flex items-center gap-2 px-4 py-2">
                        <img src="<?php echo htmlspecialchars($profilePicture); ?>" 
                             alt="Profile" 
                             class="w-10 h-10 rounded-full object-cover" 
                             onerror="this.onerror=null; this.src='./assets/default-avatar.png';">
                        <span class="text-sm"> <?php echo htmlspecialchars($name); ?> </span>
                    </div>
                    <ul>
                        <li><a href="?page=view_profile" class="block px-4 py-2 hover:bg-gray-700 transition-colors duration-200">View Profile</a></li>
                        <li><a href="./login/logout.php" class="block px-4 py-2 hover:bg-gray-700 transition-colors duration-200">Sign Out</a></li>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <!-- Login button for non-logged-in users -->
            <a href="/D-Academe/D-Academe/user/login/user_login.html" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors duration-200">
                Login
            </a>
        <?php endif; ?>
    </div>
    </div>
</header>

<!-- Loader (Spinner) -->
<div id="loader" class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center hidden z-50">
    <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-opacity-80"></div>
</div>

<script>
    // Toggle mobile menu visibility
    document.getElementById('hamburger').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });

    // Toggle the dropdown menu visibility
    function toggleDropdown() {
        const dropdownMenu = document.getElementById('dropdownMenu');
        dropdownMenu.classList.toggle('hidden');
    }

    // Handle Web3 wallet connection if needed
    function toggleWallet() {
        if (typeof window.ethereum !== 'undefined') {
            window.ethereum.request({ method: 'eth_requestAccounts' })
                .then(accounts => {
                    const account = accounts[0];
                    document.getElementById('account').textContent = account;
                    document.getElementById('walletInfo').classList.remove('hidden');
                    document.getElementById('walletButton').classList.add('hidden');
                })
                .catch(error => {
                    console.error('Error connecting wallet:', error);
                });
        } else {
            alert('Please install MetaMask or another Ethereum wallet!');
        }
    }
</script>

<script type="module">
    let isConnected = false;
    const tokenContractAddress = '0xD7De1bCcD32b38907851821535308057F718eb32';
    let tokenABI = [];

    async function loadABI() {
        try {
            const response = await fetch('./constants/ABI-Token.json');
            if (!response.ok) {
                throw new Error(`Failed to fetch ABI: ${response.statusText}`);
            }
            tokenABI = await response.json();
        } catch (error) {
            console.error("Error loading ABI:", error);
            alert("Failed to load contract ABI. Please try again.");
        }
    }

    await loadABI();

    function saveWalletState(account, ethBalance, tokenBalance) {
    fetch('saveWalletSession.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ account, ethBalance, tokenBalance })
    }).then(response => {
        if (!response.ok) {
            console.error("Failed to update PHP session");
        }
    });
}


    function clearWalletState() {
        // Clear wallet info from session via AJAX request
        fetch('clearWalletSession.php', {
            method: 'POST'
        });
    }

    function restoreWalletState() {
        const account = "<?php echo $sessionAccount; ?>";
        const ethBalance = "<?php echo $sessionEthBalance; ?>";
        const tokenBalance = "<?php echo $sessionTokenBalance; ?>" ;

        if (account) {
            isConnected = true;
            updateUI(account);
            document.getElementById('ethBalance').textContent = ethBalance;
            document.getElementById('tokenBalance').textContent = tokenBalance;
        }
    }

    // Event listener for account changes
    if (window.ethereum) {
        window.ethereum.on('accountsChanged', async (accounts) => {
            const newAccount = accounts[0];
            if (newAccount) {
                await updateAccount(newAccount); // Automatically switch to new account
            } else {
                await disconnectWallet();
            }
        });
    }

    async function updateAccount(account) {
    const web3 = new Web3(window.ethereum);
    const tokenContract = new web3.eth.Contract(tokenABI, tokenContractAddress);
 // Fetch ETH balance and truncate to first 7 digits
 const ethBalance = await web3.eth.getBalance(account);
    const ethDisplay = web3.utils.fromWei(ethBalance, 'ether').substring(0, 7) + " ETH";

    // Fetch token balance and truncate to first 7 digits
    const rawTokenBalance = await tokenContract.methods.balanceOf(account).call();
    const tokenDecimals = await tokenContract.methods.decimals().call();
    const tokenBalance = (BigInt(rawTokenBalance) / BigInt(10 ** parseInt(tokenDecimals))).toString().substring(0, 7);

    // Update the UI and PHP session
    saveWalletState(account, ethDisplay, tokenBalance.toString());
    document.getElementById('ethBalance').textContent = ethDisplay;
    document.getElementById('tokenBalance').textContent = tokenBalance.toString();
}


    async function toggleWallet() {
     const loader = document.getElementById('loader');
     try {
         loader.classList.remove('hidden');  // Show loader
         if (isConnected) {
             await disconnectWallet();
         } else {
             await connectWallet();
         }
     } finally {
         loader.classList.add('hidden');  // Hide loader
     }
 }

 // Now the function is defined before being used
 document.getElementById('walletButton').onclick = toggleWallet;

 async function connectWallet() {
    if (window.ethereum) {
        try {
            const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
            const account = accounts[0];

            const web3 = new Web3(window.ethereum);

            // Fetch ETH balance
            const ethBalance = await web3.eth.getBalance(account);
            const ethDisplay = web3.utils.fromWei(ethBalance, 'ether').substring(0, 6) + " ETH";

            // Fetch Token Balance
            const tokenContract = new web3.eth.Contract(tokenABI, tokenContractAddress);
            const tokenBalance = await tokenContract.methods.balanceOf(account).call();
            const truncatedBalance = tokenBalance.toString().slice(0, 7);

            // Save session state
            saveWalletState(account, ethDisplay, truncatedBalance);

            isConnected = true;
            updateUI(account);
            document.getElementById('ethBalance').textContent = ethDisplay;
            document.getElementById('tokenBalance').textContent = truncatedBalance;
        } catch (error) {
            console.error(error);
            alert("Error connecting to wallet.");
        }
    } else {
        // Alert with link to MetaMask
        if (confirm("MetaMask is not installed! Would you like to download it?")) {
            window.open("https://metamask.io/download/", "_blank");
        }
    }
}
    async function disconnectWallet() {
        clearWalletState();
        isConnected = false;
        updateUI('');
    }

    function updateUI(account) {
        const walletInfo = document.getElementById('walletInfo');
        const walletButton = document.getElementById('walletButton');
        const accountDisplay = document.getElementById('account');

        if (account) {
            accountDisplay.textContent = account.substring(0, 6) + '...' + account.slice(-4);
            walletInfo.classList.remove('hidden');
            walletButton.textContent = 'Disconnect Wallet';
            walletButton.classList.remove('bg-green-600');
            walletButton.classList.add('bg-red-700');  // Change to red when connected
        } else {
            accountDisplay.textContent = '';
            walletInfo.classList.add('hidden');
            walletButton.textContent = 'Connect Wallet';
            walletButton.classList.remove('bg-red-700');
            walletButton.classList.add('bg-green-600');  // Change back to green when disconnected
        }
    }

    restoreWalletState();
   
    let lastScrollTop = 0;  // To store the last scroll position
const header = document.querySelector('header');  // The header element

// Add scroll event listener
window.addEventListener('scroll', function() {
    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

    // Check if the user is scrolling down or up
    if (currentScroll > lastScrollTop) {
        // Scroll Down: Hide the header
        header.classList.add('hidden');
    } else {
        // Scroll Up: Show the header
        header.classList.remove('hidden');
    }

    // Update last scroll position
    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Prevent negative values
});
</script>

</body>
</html>
