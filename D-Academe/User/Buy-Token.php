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
            margin-left: 200px;
            margin-right: 200px;
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
                class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 rounded-lg hover:from-green-600 hover:to-teal-600 transition transform hover:scale-105" onclick="toggleloader()">
                Buy Tokens
            </button>

            <!-- Button for Esewa Payment  -->
            <button
            id="eSewaPaymentButton"
            class="w-full mt-4 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 rounded-lg hover:from-green-600 hover:to-teal-600 transition transform hover:scale-105">
            Pay via eSewa
        </button>

             <!-- Token Price Section -->
        <div class="bg-green-800 p-8 mt-20 rounded-lg shadow-lg mx-auto max-w-md">
            <h2 class="text-3xl font-bold text-blue-400 text-center mb-6">Token Price</h2>
            <div class="text-center">
                <p class="text-lg">1000 Token = <span id="tokenPrice" class="font-bold text-green-400">1.00</span> ETH</p>
                <p class="text-lg">1 Token = <span id="tokenPrice" class="font-bold text-green-400">50</span> NRS</p>
            </div>
        </div>
    </div>
        </form>
    </div>

    <div id="loader" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-green-500"></div>
    </div>

    

    <!-- Wallet Info Section -->
    <div id="walletInfo" class="bg-gray-800 p-8 mt-10 rounded-lg shadow-lg mx-auto max-w-md text-center">
        <h2 class="text-3xl font-bold text-green-400 text-center mb-6">Available Tokens</h2>
        <p class="font-bold text-3xl text-white"><strong id="availableTokenBalance">0</strong></p>
        <p id="walletAddress" class="text-green-400 text-sm mt-4"></p> <!-- Show wallet address here -->
    </div>

    
<!-- Loader (Spinner) -->
<div id="loader" class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center hidden z-50">
    <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-opacity-80"></div>
</div>


    <script>
        let isConnected = false;
        let tokenABI = [];
        const tokenContractAddress = "0x7A2450f1bF6BB66AD0FA75716752Ad903C8482C9";
        const rate = 1000; // Rate from the smart contract

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

        // Initialize Wallet Connection
        async function initializeWallet() {
            console.log("Initializing wallet...");
            if (typeof window.ethereum === 'undefined') {
                alert("MetaMask is not installed. Please install MetaMask to use this feature.");
                updateWalletDisconnected();
                return;
            }

            try {
                const accounts = await window.ethereum.request({ method: 'eth_accounts' });
                if (accounts.length > 0) {
                    const account = accounts[0];
                    console.log("Wallet connected:", account);
                    localStorage.setItem('account', account);
                    updateWalletConnected(account);
                    await fetchBalances(account);
                } else {
                    updateWalletDisconnected();
                }
            } catch (error) {
                console.error("Error initializing wallet:", error);
                updateWalletDisconnected();
            }
        }

        // Fetch Token Balances
        async function fetchBalances(account) {
            console.log("Fetching balances for:", account);
            try {
                const web3 = new Web3(window.ethereum);
                const tokenContract = new web3.eth.Contract(tokenABI, tokenContractAddress);

                // Fetch token balance in smallest unit (e.g., Wei)
                const rawTokenBalance = await tokenContract.methods.balanceOf(account).call();
                console.log("Token balance retrieved (raw):", rawTokenBalance);

                // Fetch token decimals to properly convert balance
                const tokenDecimals = await tokenContract.methods.decimals().call();
                console.log("Token decimals:", tokenDecimals);

                // Convert rawTokenBalance and tokenDecimals to BigInt before performing division
                const balanceInTokens = BigInt(rawTokenBalance) / BigInt(10 ** parseInt(tokenDecimals));

                // Update the available token balance (convert to string if necessary)
                document.getElementById('availableTokenBalance').textContent = balanceInTokens.toString();
            } catch (error) {
                console.error("Error fetching balances:", error);
                document.getElementById('availableTokenBalance').textContent = "0";
                alert("Failed to retrieve balances. Please try again.");
            }
        }

        // Update UI for Wallet Connected
        function updateWalletConnected(account) {
            console.log("Updating wallet connected state.");
            isConnected = true;
            // document.getElementById('walletAddress').textContent = `Connected Wallet: ${account}`;
        }

        // Update UI for Wallet Disconnected
        function updateWalletDisconnected() {
            console.log("Updating wallet disconnected state.");
            isConnected = false;
            document.getElementById('walletAddress').textContent = "No Wallet Connected";
            document.getElementById('availableTokenBalance').textContent = "0";
            localStorage.removeItem('account');
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

    // Convert tokens to buy into ETH based on the rate
    const ethToSend = (tokensToBuy * 1) / rate; // Adjust this formula as needed

    const loader = document.getElementById('loader');
    const buyButton = event.target.querySelector('button[type="submit"]');
    buyButton.disabled = true;

    try {
        // Show the loader
        loader.classList.remove('hidden');

        const web3 = new Web3(window.ethereum);
        const tokenContract = new web3.eth.Contract(tokenABI, tokenContractAddress);

        // Call the buyTokens function with the required ETH
        const receipt = await tokenContract.methods
            .buyTokens()
            .send({ from: account, value: web3.utils.toWei(ethToSend.toString(), 'ether') });

        console.log("Purchase receipt:", receipt);
        alert(`Successfully purchased ${tokensToBuy} tokens!`);
        await fetchBalances(account); // Refresh the balance
    } catch (error) {
        console.error("Error purchasing tokens:", error);
        alert("Token purchase failed. Please check the transaction details and try again.");
    } finally {
        // Hide the loader and re-enable the button
        loader.classList.add('hidden');
        buyButton.disabled = false;
    }
}

         // Handle eSewa Payment
         async function handleESewaPayment(event) {
        event.preventDefault(); // Prevent any default behavior

        const tokensToBuy = document.querySelector('input[name="tokensToBuy"]').value;
        if (!tokensToBuy || tokensToBuy <= 0) {
            alert("Please enter a valid amount of tokens to buy.");
            return;
        }

        const amountInNPR = tokensToBuy * 50; // Example rate: 1 token = 50 NPR
        const esewaURL = `https://uat.esewa.com.np/epay/main`;
        const params = new URLSearchParams({
            amt: amountInNPR,
            psc: 0,
            pdc: 0,
            txAmt: 0,
            tAmt: amountInNPR,
            pid: `TOKEN_PURCHASE_${Date.now()}`,
            scd: 'EPAYTEST',
            su: 'http://yourwebsite.com/success', // Success URL
            fu: 'http://yourwebsite.com/failure'  // Failure URL
        });

        // Change the location to open the eSewa URL in the same page
        window.location.href = `${esewaURL}?${params.toString()}`;
    }
        
    // eSewa Payment Button
    document.getElementById('eSewaPaymentButton').addEventListener('click', handleESewaPayment);

        // Initialize Application
        window.onload = async () => {
            await loadABI();
            await initializeWallet();

            // Attach form submit event listener
            const form = document.querySelector('form');
            form.addEventListener('submit', handleTokenPurchase);

            // Listen for account or chain changes
            window.ethereum.on('accountsChanged', async (accounts) => {
                console.log("Accounts changed:", accounts);
                if (accounts.length === 0) {
                    updateWalletDisconnected();
                } else {
                    const account = accounts[0];
                    localStorage.setItem('account', account);
                    updateWalletConnected(account);
                    await fetchBalances(account);
                }
            });

            window.ethereum.on('chainChanged', async () => {
                console.log("Chain changed. Reloading balances.");
                const account = localStorage.getItem('account');
                if (isConnected && account) {
                    await fetchBalances(account);
                }
            });
        };
    </script>
</body>
</html>
