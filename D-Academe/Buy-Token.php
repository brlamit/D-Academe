<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Tokens</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/web3/dist/web3.min.js"></script>
    <style>
        body {
            margin-top: 100px;
        }
        header {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            transition: background-color 0.3s ease;
            z-index: 50;
        }
        .container {
            margin-top: 120px;
        }
    </style>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">
    <!-- Token Purchase Section -->
    <div class="bg-gray-800 p-8 mt-20 rounded-lg shadow-lg mx-auto max-w-md">
        <h2 class="text-3xl font-bold text-green-400 text-center mb-6">Buy Tokens</h2>
        <form method="POST" class="space-y-4" id="buyTokensForm">
            <div>
                <input
                    type="number"
                    id="tokensToBuy"
                    name="tokensToBuy"
                    class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                    placeholder="Enter amount of tokens"
                    min="1"
                    required
                >
            </div>
            <button
                type="submit"
                class="w-full bg-gradient-to-r from-green-500 to-teal-500 text-white font-bold py-3 rounded-lg hover:from-green-600 hover:to-teal-600 transition transform hover:scale-105">
                Buy Tokens
            </button>
        </form>
    </div>

    <!-- Wallet Info Section -->
    <div id="walletInfo" class="bg-gray-800 p-8 mt-20 rounded-lg shadow-lg mx-auto max-w-md text-center">
        <h2 class="text-3xl font-bold text-green-400 text-center mb-6">Available Tokens</h2>
        <p class="font-bold text-3xl text-white"><strong id="availableTokenBalance">0</strong></p>
        <p id="walletAddress" class="text-green-400 text-sm mt-4"></p> <!-- Show wallet address here -->
        <button 
            id="connectWalletBtn" 
            class="mt-4 bg-green-600 px-4 py-2 rounded-lg text-white font-bold">
            Connect Wallet
        </button>
    </div>

    <script>
    let isConnected = false;
    let tokenABI = [];
    const tokenContractAddress = "0xD7De1bCcD32b38907851821535308057F718eb32";

    // Load ABI
    async function loadABI() {
        try {
            const response = await fetch('./constants/ABI-Token.json');
            if (!response.ok) throw new Error(`Failed to fetch ABI: ${response.statusText}`);
            tokenABI = await response.json();
            console.log("ABI loaded successfully.");
        } catch (error) {
            console.error("Error loading ABI:", error);
            alert("Failed to load contract ABI. Please try again.");
        }
    }

    // Connect Wallet
    async function connectWallet() {
        if (typeof window.ethereum === 'undefined') {
            alert("MetaMask is not installed. Please install MetaMask to use this feature.");
            return;
        }

        try {
            const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
            if (accounts.length > 0) {
                isConnected = true;
                const account = accounts[0];
                localStorage.setItem('isConnected', 'true');
                localStorage.setItem('account', account);

                document.getElementById('walletAddress').textContent = `Connected Wallet: ${account}`;
                await fetchBalances(account);  // Fetch and display token balance
            } else {
                alert("Wallet connection failed. Please try again.");
            }
        } catch (error) {
            console.error("Error connecting wallet:", error);
            alert("Failed to connect wallet. Please try again.");
        }
    }

    // Fetch Token Balances
    async function fetchBalances(account) {
        const web3 = new Web3(window.ethereum);
        try {
            const tokenContract = new web3.eth.Contract(tokenABI, tokenContractAddress);
            const tokenBalance = await tokenContract.methods.balanceOf(account).call();

            // Update the available token balance
            document.getElementById('availableTokenBalance').textContent = tokenBalance;
            
        } catch (error) {
            console.error("Error fetching balances:", error);
            alert("Failed to retrieve balances. Please try again.");
        }
    }

   // Handle Token Purchase
   async function handleTokenPurchase(event) {
    event.preventDefault();

    const account = localStorage.getItem('account');
    if (!isConnected || !account) {
        alert("Please connect your wallet first.");
        return;
    }

    const tokensToBuy = document.querySelector('input[name="tokensToBuy"]').value;
    if (!tokensToBuy || tokensToBuy <= 0) {
        alert("Please enter a valid amount of tokens to buy.");
        return;
    }

    // Reference to the buy button (assumes the button is part of the form)
    const buyButton = event.target.querySelector('button[type="submit"]');
    
    try {
        // Disable the buy button to prevent multiple clicks during transaction
        if (buyButton) buyButton.disabled = true;

        const web3 = new Web3(window.ethereum);
        const tokenContract = new web3.eth.Contract(tokenABI, tokenContractAddress);

        // Call the smart contract's purchase function
        const receipt = await tokenContract.methods.buyTokens(tokensToBuy).send({ from: account });

        if (!receipt || !receipt.transactionHash) {
            throw new Error('Transaction failed');
        }

        // Successful purchase alert
        alert(`Successfully purchased ${tokensToBuy} tokens! Transaction Hash: ${receipt.transactionHash}`);
        
        // Update token balance after purchase
        await fetchBalances(account);

    } catch (error) {
        console.error("Error purchasing tokens:", error);
        alert("Token purchase failed. Please try again.");
    } finally {
        // Re-enable the buy button regardless of success or failure
        if (buyButton) buyButton.disabled = false;
    }
}

    // Restore Wallet State
    function restoreWalletState() {
        const storedIsConnected = localStorage.getItem('isConnected');
        const storedAccount = localStorage.getItem('account');

        if (storedIsConnected === 'true' && storedAccount) {
            isConnected = true;
            document.getElementById('walletAddress').textContent = `Connected Wallet: ${storedAccount}`;
            fetchBalances(storedAccount).catch(error => {
                console.error("Error restoring wallet state:", error);
            });
        } else {
            document.getElementById('availableTokenBalance').textContent = "0";
        }
    }

    // Initialize
    window.onload = async () => {
        await loadABI();
        restoreWalletState();

        // Attach form submit event listener
        const form = document.querySelector('form');
        form.addEventListener('submit', handleTokenPurchase);

        // Attach connect wallet button listener
        document.getElementById('connectWalletBtn').addEventListener('click', connectWallet);
    };

    // Detect wallet disconnection
    window.ethereum.on('accountsChanged', function(accounts) {
        if (accounts.length === 0) {
            isConnected = false;
            document.getElementById('availableTokenBalance').textContent = "0"; // Set balance to 0 if disconnected
            document.getElementById('walletAddress').textContent = ""; // Remove wallet address
        } else {
            const account = accounts[0];
            isConnected = true;
            document.getElementById('walletAddress').textContent = `Connected Wallet: ${account}`;
            fetchBalances(account);  // Update balance if wallet is connected
        }
    });

    // Handle wallet disconnection by detecting 'chainChanged' event
    window.ethereum.on('chainChanged', () => {
        const account = localStorage.getItem('account');
        if (isConnected && account) {
            fetchBalances(account);  // Fetch balance on network change
        }
    });

    </script>

</body>
</html>
