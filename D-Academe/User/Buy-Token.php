<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Tokens</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/web3/dist/web3.min.js"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">
<section class="py-16">
    <!-- Token Purchase Section -->
    <div class="bg-gray-800 p-8 mt-20 rounded-lg shadow-lg mx-auto max-w-md">
        <h2 class="text-3xl font-bold text-green-400 text-center mb-6">Buy Tokens</h2>
        <form id="buyTokensForm" class="space-y-4">
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
                class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 rounded-lg hover:from-green-600 hover:to-teal-600 transition transform hover:scale-105">
                Buy Tokens
            </button>

            <!-- Khalti Payment Button -->
            <button 
                type="button" 
                id="khaltiButton" 
                onclick="redirectToKhaltiPayment()" 
                class="mt-4 bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded w-full">
                Pay with Khalti
            </button>

            <!-- eSewa Payment Button -->
            <!-- <button 
                type="button" 
                id="eSewaPaymentButton" 
                class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                Pay with eSewa
            </button> -->

            <!-- Token Price Section -->
            <div class="bg-green-800 p-8 mt-20 rounded-lg shadow-lg mx-auto max-w-md">
                <h2 class="text-3xl font-bold text-blue-400 text-center mb-6">Token Price</h2>
                <div class="text-center">
                    <p class="text-lg">1000 Token = <span class="font-bold text-green-400">1.00</span> ETH</p>
                    <p class="text-lg">1 Token = <span class="font-bold text-green-400">50</span> NRS</p>
                </div>
            </div>
        </form>
    </div>

    <!-- Loader -->
    <div id="loader" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-green-500"></div>
    </div>
</section>

<script>
    let isConnected = false;
    let tokenABI = [];
    const tokenContractAddress = "0x7A2450f1bF6BB66AD0FA75716752Ad903C8482C9";
    const rate = 1000; // 1000 tokens = 1 ETH

    // Load ABI
    async function loadABI() {
        try {
            const response = await fetch('constants/ABI-Token.json'); // Ensure correct path
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
            return;
        }

        try {
            const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
            if (accounts.length > 0) {
                isConnected = true;
                localStorage.setItem('account', accounts[0]);
                console.log("Wallet connected:", accounts[0]);
            } else {
                isConnected = false;
                console.log("No accounts found.");
            }
        } catch (error) {
            console.error("Error initializing wallet:", error);
        }
    }

    // Handle Token Purchase
    async function handleTokenPurchase(event) {
        event.preventDefault();

        if (!isConnected) {
            alert("Please connect your wallet first.");
            return;
        }

        const tokensToBuy = document.getElementById('tokensToBuy').value;
        if (!tokensToBuy || tokensToBuy <= 0) {
            alert("Please enter a valid amount of tokens.");
            return;
        }

        const account = localStorage.getItem('account');
        const ethToSend = (tokensToBuy * 1) / rate;

        const loader = document.getElementById('loader');
        loader.classList.remove('hidden');

        try {
            const web3 = new Web3(window.ethereum);
            const tokenContract = new web3.eth.Contract(tokenABI, tokenContractAddress);

            const receipt = await tokenContract.methods
                .buyTokens()
                .send({ from: account, value: web3.utils.toWei(ethToSend.toString(), 'ether') });

            console.log("Purchase receipt:", receipt);
            alert(`Successfully purchased ${tokensToBuy} tokens!`);
        } catch (error) {
            console.error("Error purchasing tokens:", error);
            alert("Token purchase failed. Please try again.");
        } finally {
            loader.classList.add('hidden');
        }
    }

    // Handle eSewa Payment
    function handleESewaPayment() {
        const tokensToBuy = document.getElementById('tokensToBuy').value;
        if (!tokensToBuy || tokensToBuy <= 0) {
            alert("Please enter a valid amount of tokens.");
            return;
        }

        const amountInNPR = tokensToBuy * 50;
        const esewaURL = `https://uat.esewa.com.np/epay/main?amt=${amountInNPR}&psc=0&pdc=0&txAmt=0&tAmt=${amountInNPR}&pid=TOKEN_PURCHASE_${Date.now()}&scd=EPAYTEST&su=http://yourwebsite.com/success&fu=http://yourwebsite.com/failure`;
        
        window.location.href = esewaURL;
    }

    // Handle Khalti Payment
    function redirectToKhaltiPayment() {
        const tokensToBuy = document.getElementById("tokensToBuy").value;
        if (!tokensToBuy || tokensToBuy <= 0) {
            alert("Please enter a valid amount of tokens.");
            return;
        }

        const pricePerToken = 50;
        const totalPriceNRS = tokensToBuy * pricePerToken; 

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'khalti-payment.php';

        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = 'tokensToBuy';
        tokenInput.value = tokensToBuy;

        const priceInput = document.createElement('input');
        priceInput.type = 'hidden';
        priceInput.name = 'totalPriceNRS';
        priceInput.value = totalPriceNRS;

        form.appendChild(tokenInput);
        form.appendChild(priceInput);

        document.body.appendChild(form);
        form.submit();
    }

    // Initialize Application
    window.onload = async () => {
        await loadABI();
        await initializeWallet();
        document.getElementById('buyTokensForm').addEventListener('submit', handleTokenPurchase);
        document.getElementById('eSewaPaymentButton').addEventListener('click', handleESewaPayment);
    };
</script>

</body>
</html>
