<?php
session_start();
include('dbconnection.php'); // Database connection
include('header.php'); // Header

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    die("You need to be logged in to buy a course.");
}

// Get user details from session
$user_id = $_SESSION['id'];
$user_email = $_SESSION['email'];
$user_name = $_SESSION['name'];
$user_wallet_address = $_SESSION['wallet_address'];

// Fetch balances from session
$sessionAccount = isset($_SESSION['wallet_address']) ? $_SESSION['wallet_address'] : '';
$sessionEthBalance = isset($_SESSION['ethBalance']) ? $_SESSION['ethBalance'] : '0';
$sessionTokenBalance = isset($_SESSION['tokenBalance']) ? $_SESSION['tokenBalance'] : '0';

// Get course details from URL parameters with validation
$course_details = [
    'id' => isset($_GET['course_id']) ? (int) $_GET['course_id'] : 0,
    'name' => isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Unknown',
    'description' => isset($_GET['description']) ? htmlspecialchars($_GET['description']) : 'No description available.',
    'price_in_tokens' => isset($_GET['token_price']) ? (float) $_GET['token_price'] : 0
];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <style>
        body {
            background: linear-gradient(to bottom, #203f43, #2c8364);
            background-size: cover;
            background-position: center;
            font-family: 'Inter', sans-serif;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/web3/1.7.3/web3.min.js"></script>
</head>
<body class="">
    <div class="container flex  items-center justify-center mx-auto my-10 relative">
    <div class=" p-8 rounded-xl shadow-2xl w-full max-w-4xl  relative ">
        <button type="button" onclick="goBack()" class="absolute top-4 left-4 bg-transparent underline text-white py-2 px-4 text-sm rounded-lg font-semibold hover:text-teal-800 transition-all shadow-md">
        Go Back
    </button>
            <h1 class="text-3xl font-bold text-gray-500 mb-4 text-center">Course Details</h1>
            <div class="mb-2"><strong class="block text-gray-400">Course Name:</strong> <?php echo $course_details['name']; ?></div>
            <div class="mb-2"><strong class="block text-gray-400">Description:</strong> <?php echo $course_details['description']; ?></div>
            <div class="mb-2">
                <strong class="block text-gray-400">Price in Tokens:</strong>
                <span id="coursePrice"><?php echo $course_details['price_in_tokens']; ?></span>
            </div>

            <!-- Buy Course Button -->
            <button id="buyButton" 
                onclick="buyCourse('<?php echo $course_details['price_in_tokens']; ?>', 
                                   '<?php echo $user_name; ?>', 
                                   '<?php echo $user_email; ?>', 
                                   '<?php echo $user_wallet_address; ?>', 
                                   '<?php echo $course_details['id']; ?>')"
                class="mt-4 w-full py-3 mt-4 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-all shadow-lg">
                Buy Course
            </button>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <p class="font-medium">Tokens: <strong id="tokenBalance"><?php echo $sessionTokenBalance; ?></strong></p>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
       let isConnected = false;
let tokenABI = [];
const tokenContractAddress = "0x7A2450f1bF6BB66AD0FA75716752Ad903C8482C9";
const contractAddress = "0x9755388F192bcDe112764674799Df74f3F5bF590";
let userAccount = "";

document.addEventListener("DOMContentLoaded", async () => {
    await loadABI();
    await initializeWallet();
});

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

async function initializeWallet() {
    console.log("Initializing wallet...");
    if (typeof window.ethereum === 'undefined') {
        alert("MetaMask is not installed. Please install MetaMask to use this feature.");
        return;
    }

    try {
        const accounts = await window.ethereum.request({ method: 'eth_accounts' });
        if (accounts.length > 0) {
            userAccount = accounts[0]; // Store user account
            console.log("Wallet connected:", userAccount);
            localStorage.setItem('account', userAccount);
            isConnected = true;
            fetchBalances(userAccount);
        }
    } catch (error) {
        console.error("Error initializing wallet:", error);
    }
}

async function fetchBalances(account) {
    try {
        const web3 = new Web3(window.ethereum);
        const tokenContract = new web3.eth.Contract(tokenABI, tokenContractAddress);

        const rawTokenBalance = await tokenContract.methods.balanceOf(account).call();
        const tokenDecimals = await tokenContract.methods.decimals().call();
        const balanceInTokens = (BigInt(rawTokenBalance) / BigInt(10 ** parseInt(tokenDecimals))).toString();

        document.getElementById('tokenBalance').textContent = balanceInTokens;
    } catch (error) {
        console.error("Error fetching balances:", error);
        alert("Failed to retrieve balances. Please try again.");
    }
}

async function buyCourse(priceInTokens, userName, userEmail, userWalletAddress, courseId) {
    if (!window.ethereum) {
        alert("MetaMask is not installed. Please install MetaMask.");
        return;
    }

    const web3 = new Web3(window.ethereum);

    if (!userAccount) {
        alert("Please connect your wallet.");
        return;
    }

    try {
        const tokenAmount = web3.utils.toBN(Math.floor(priceInTokens * 10 ** 18));

        // Fetch contract ABIs
        const [courseABI, tokenABI] = await Promise.all([
            fetch('./constants/ABI-CourseBuy.json').then(res => res.json()),
            fetch('./constants/ABI-Token.json').then(res => res.json())
        ]);

        // Initialize contracts
        const courseContract = new web3.eth.Contract(courseABI, contractAddress);
        const tokenContract = new web3.eth.Contract(tokenABI, tokenContractAddress);

        // Ensure the user has enough tokens
        const balance = await tokenContract.methods.balanceOf(userAccount).call();
        if (web3.utils.toBN(balance).lt(tokenAmount)) {
            alert("Insufficient token balance. Please buy more tokens.");
            return;
        }

        const buyButton = document.getElementById("buyButton");
        buyButton.disabled = true;
        buyButton.innerText = "Processing...";
        buyButton.classList.add("bg-gray-400", "cursor-not-allowed");

        // Check if the user has given allowance to the contract
        const allowance = await tokenContract.methods.allowance(userAccount, contractAddress).call();
        if (web3.utils.toBN(allowance).lt(tokenAmount)) {
            await tokenContract.methods.approve(contractAddress, tokenAmount.toString()).send({ from: userAccount });
            alert("Approval transaction sent. Please confirm and retry the purchase.");
            buyButton.disabled = false;
            buyButton.innerText = "Buy Course";
            buyButton.classList.remove("bg-gray-400", "cursor-not-allowed");
            return;
        }

        // Call buyCourse function from the smart contract
        const transaction = await courseContract.methods.buyCourse(
            courseId, // Pass courseId
            userName, // Student name
            userWalletAddress, // User's wallet address
            userEmail, // Email
            "1234567890" // Placeholder contact info, replace with dynamic data if available
        ).send({
            from: userAccount
        });

        alert("Course purchased successfully!");
        console.log("Transaction:", transaction);

        buyButton.disabled = false;
        buyButton.innerText = "Buy Course";
        buyButton.classList.remove("bg-gray-400", "cursor-not-allowed");

    } catch (error) {
        console.error("Transaction failed:", error);
        alert("Purchase failed. Please try again.");

        const buyButton = document.getElementById("buyButton");
        buyButton.disabled = false;
        buyButton.innerText = "Buy Course";
        buyButton.classList.remove("bg-gray-400", "cursor-not-allowed");
    }
}

window.onload = initializeWallet;


    </script>

</body>
</html>

<?php include('footer.php'); ?>
