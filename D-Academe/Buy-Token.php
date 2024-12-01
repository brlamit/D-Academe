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
             <!-- Token Price Section -->
        <div class="bg-green-800 p-8 mt-20 rounded-lg shadow-lg mx-auto max-w-md">
            <h2 class="text-3xl font-bold text-blue-400 text-center mb-6">Token Price</h2>
            <div class="text-center">
                <p class="text-lg">1000 Token = <span id="tokenPrice" class="font-bold text-green-400">1.00</span> ETH</p>
            </div>
        </div>
    </div>
        </form>
    </div>
    

    <!-- Wallet Info Section -->
    <div id="walletInfo" class="bg-gray-800 p-8 mt-20 rounded-lg shadow-lg mx-auto max-w-md text-center">
        <h2 class="text-3xl font-bold text-green-400 text-center mb-6">Available Tokens</h2>
        <p class="font-bold text-3xl text-white"><strong id="availableTokenBalance">0</strong></p>
        <p id="walletAddress" class="text-green-400 text-sm mt-4"></p> <!-- Show wallet address here -->
    </div>
</body>
</html>
