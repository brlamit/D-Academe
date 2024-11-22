<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function displayBalance($balance) {
    return number_format((float)$balance, 4, '.', '');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D-Academe</title>
    <script src="https://cdn.jsdelivr.net/npm/web3/dist/web3.min.js"></script>
    <style>
        header {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            transition: background-color 0.3s ease;
        }

        header h1:hover,
        button:hover {
            transform: scale(1.05);
        }

        button {
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        button:active {
            transform: scale(0.95);
        }

        .hidden {
            display: none;
        }

        .visible {
            display: block;
        }
    </style>
</head>
<body>
<header class="w-full flex items-center justify-between py-4 px-6 fixed top-0 left-0 right-0 z-50 bg-gray-900 bg-opacity-80 backdrop-blur-lg text-white">
     <!-- Logo -->
    <h1 class="text-3xl font-bold tracking-wide hover:scale-105 transition-transform duration-300">D-Academe</h1>

    <!-- Navigation (Hamburger menu on small screens) -->
    <nav class="hidden md:flex flex-1 justify-center">
        <ul class="flex gap-8">
            <li><a href="?page=home" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Home</a></li>
            <li><a href="?page=BuyToken" class="text-lg font-medium hover:text-blue-300 transition-colors duration-200">Buy Token</a></li>
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


    <div class="flex items-center gap-6">
        <!-- Wallet Info -->
        <div id="walletInfo" class="text-right hidden">
            <p class="font-medium">Tokens: <strong id="tokenBalance">0</strong></p>
            <p class="font-medium">Address: <strong id="account"></strong></p>
            <p class="font-medium">Balance: <strong id="ethBalance">0 ETH</strong></p>
        </div>
        <!-- Wallet Button -->
        <button id="walletButton" class="bg-green-600 text-white py-2 px-4 rounded-full hover:bg-green-700 transition-colors duration-200 shadow-md hover:shadow-lg" onclick="toggleWallet()">Connect Wallet</button>
    </div>
</header>
<script>
    // Toggle mobile menu visibility
    document.getElementById('hamburger').addEventListener('click', function() {
        var mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
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

    async function toggleWallet() {
        if (isConnected) {
            disconnectWallet();
        } else {
            connectWallet();
        }
    }

    async function connectWallet() {
        if (window.ethereum) {
            try {
                const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
                const account = accounts[0];

                document.getElementById('account').textContent = `${account.substring(0, 6)}....${account.substring(account.length - 4)}`;
                await fetchBalances(account);

                document.getElementById('walletInfo').classList.remove('hidden');
                document.getElementById('walletInfo').classList.add('visible');
                document.getElementById('walletButton').textContent = 'Disconnect Wallet';
                document.getElementById('walletButton').classList.replace('bg-green-600', 'bg-red-500');
                isConnected = true;

                alert(`Connected: ${account}`);
            } catch (error) {
                console.error("Error connecting to wallet:", error);
                alert("Failed to connect wallet. Please try again.");
            }
        } else {
            alert('MetaMask is not installed. Please install MetaMask and try again.');
        }
    }

    async function disconnectWallet() {
        document.getElementById('account').textContent = '';
        document.getElementById('ethBalance').textContent = '0 ETH';
        document.getElementById('tokenBalance').textContent = '0';

        document.getElementById('walletInfo').classList.add('hidden');
        document.getElementById('walletInfo').classList.remove('visible');
        document.getElementById('walletButton').textContent = 'Connect Wallet';
        document.getElementById('walletButton').classList.replace('bg-red-500', 'bg-green-600');
        isConnected = false;

        alert('Wallet disconnected.');
    }

    async function fetchBalances(account) {
        const web3 = new Web3(window.ethereum);
        try {
            const ethBalance = await web3.eth.getBalance(account);
            const ethDisplay = web3.utils.fromWei(ethBalance, 'ether');
            document.getElementById('ethBalance').textContent = `${ethDisplay.substring(0, 6)} ETH`;

            const tokenContract = new web3.eth.Contract(tokenABI, tokenContractAddress);
            const tokenBalance = await tokenContract.methods.balanceOf(account).call();
            document.getElementById('tokenBalance').textContent = tokenBalance;
        } catch (error) {
            console.error("Error fetching balances:", error);
            alert("Failed to retrieve balances. Please try again.");
        }
    }

    // Expose functions to the global scope
    window.toggleWallet = toggleWallet;
</script>


</body>
</html>
