<?php
// session_start();
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

// Check if the user has already purchased the course
$stmt = $conn->prepare("SELECT id FROM paid_course_enrollments WHERE user_id = ? AND course_id = ?");
$stmt->bind_param("ii", $user_id, $course_details['id']);
$stmt->execute();
$result = $stmt->get_result();
$is_enrolled = $result->num_rows > 0;
$stmt->close();
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
    <script src="https://khalti.com/static/khalti-checkout.js"></script>
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
</head>
<body>
<?php
if (isset($_SESSION['transaction_msg'])) {
    echo $_SESSION['transaction_msg'];
    unset($_SESSION['transaction_msg']);
}

if (isset($_SESSION['validate_msg'])) {
    echo $_SESSION['validate_msg'];
    unset($_SESSION['validate_msg']);
}
?>
    <br>
    <div class="container flex items-center justify-center mx-auto my-10 relative">
        <div class="p-8 rounded-xl shadow-2xl w-full max-w-4xl relative">
        <button type="button" onclick="goBack()" class="absolute top-4 left-4 bg-transparent underline text-white py-2 px-4 text-sm rounded-lg font-semibold hover:text-teal-500 transition-all shadow-lg">
    Go Back
</button>

<div class="max-w-2xl mx-auto  p-6 rounded-2xl shadow-xl border border-gray-700">
    <h1 class="text-4xl font-extrabold text-teal-400 mb-6 text-center">Course Details</h1>

    <div class="mb-4 p-4  rounded-lg shadow-md">
        <strong class="block text-teal-300 text-lg">Course Name:</strong>
        <p class="text-gray-100 text-base"><?php echo $course_details['name']; ?></p>
    </div>

    <div class="mb-4 p-4  rounded-lg shadow-md">
        <strong class="block text-teal-300 text-lg">Description:</strong>
        <p class="text-gray-100 text-base"><?php echo $course_details['description']; ?></p>
    </div>

    <div class="mb-4 p-4  rounded-lg shadow-md">
        <strong class="block text-teal-300 text-lg">Price in Tokens:</strong>
        <p class="text-gray-100 text-base font-semibold"><?php echo number_format($course_details['price_in_tokens'], 2); ?></p>
    </div>

</div>


            <!-- Show enrollment status message before the button -->
            <?php if ($is_enrolled): ?>
                <!-- Hidden Modal -->
                <div id="enrolledModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
                    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md text-center">
                        <h2 class="text-xl font-semibold text-gray-700">You are already enrolled in this course! ✅</h2>
                        <p class="text-gray-600 mt-2">You can access your enrolled courses anytime.</p>
                        <div class="mt-4">
                            <button onclick="window.location.href='index.php?page=enrolled-course'" class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition">
                                Go to Enrolled Courses
                            </button>
                            <!-- <button onclick="closeModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 ml-2">
                                Close
                            </button> -->
                        </div>
                    </div>
                </div>

                <script>
                    // Function to show the modal
                    function showEnrolledModal() {
                        document.getElementById("enrolledModal").classList.remove("hidden");
                    }

                    // Function to close the modal
                    function closeModal() {
                        document.getElementById("enrolledModal").classList.add("hidden");
                    }

                    // Show the modal automatically when the page loads if enrolled
                    window.onload = function () {
                        showEnrolledModal();
                    };
                </script>
            <?php else: ?>
                <!-- <p class="text-gray-600 mt-2">You can buy this course by clicking the button below.</p> -->
            <?php endif; ?>

           <!-- Buy Course Button -->
<button id="buyButton" 
        onclick="buyCourse('<?php echo $course_details['price_in_tokens']; ?>', 
                           '<?php echo $user_name; ?>', 
                           '<?php echo $user_email; ?>', 
                           '<?php echo $user_wallet_address; ?>', 
                           '<?php echo $course_details['id']; ?>')"
        class="mt-4 w-full py-3 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-all shadow-lg">
    Buy Course
</button>

<!-- Khalti Payment Button as a Button -->
<button 
   onclick="payWithKhalti(<?php echo (int)$course_details['id']; ?>, '<?php echo htmlspecialchars($course_details['name'], ENT_QUOTES, 'UTF-8'); ?>', <?php echo (float)$course_details['price_in_tokens']; ?>)"
    id="btn" 
    class="mt-4 w-full py-3 bg-purple-500 text-white font-bold rounded-lg hover:bg-purple-700 transition-all shadow-lg">
    Buy Now with Khalti
</button>


        </div>
    </div>

    <!-- <div class="text-center mt-4">
        <p class="font-medium">Tokens: <strong id="tokenBalance"><?php echo $sessionTokenBalance; ?></strong></p>
    </div> -->

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
                const courseResponse = await fetch('./constants/ABI-CourseBuy.json');
                if (!response.ok) throw new Error(`Failed to fetch ABI: ${response.statusText}`);
                tokenABI = await response.json();
                courseABI = await courseResponse.json();
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
                const tokenContract = new web3.eth.Contract(tokenABI, tokenContractAddress);
                const courseContract = new web3.eth.Contract(courseABI, contractAddress);

                // Convert price to correct token format
                const tokenDecimals = await tokenContract.methods.decimals().call();
                const tokenAmount = web3.utils.toBN(priceInTokens).mul(web3.utils.toBN(10).pow(web3.utils.toBN(tokenDecimals)));

                // Fetch user balance
                const rawBalance = await tokenContract.methods.balanceOf(userAccount).call();
                const userBalance = web3.utils.toBN(rawBalance);

                if (userBalance.lt(tokenAmount)) {
                    alert("Insufficient token balance. Please buy more tokens.");
                    return;
                }

                // Check allowance
                const rawAllowance = await tokenContract.methods.allowance(userAccount, contractAddress).call();
                const allowance = web3.utils.toBN(rawAllowance);

                if (allowance.lt(tokenAmount)) {
                    alert("Insufficient allowance. Approving now...");
                    await tokenContract.methods.approve(contractAddress, tokenAmount.toString()).send({ from: userAccount });
                    alert("Approval transaction sent. Please confirm and retry the purchase.");
                    return;
                }

                // Disable Buy Button while processing
                const buyButton = document.getElementById("buyButton");
                buyButton.disabled = true;
                buyButton.innerText = "Processing...";
                buyButton.classList.add("bg-gray-400", "cursor-not-allowed");

                // Call buyCourse function from contract
                const transaction = await courseContract.methods.buyCourse(
                    courseId,
                    userName,
                    userWalletAddress,
                    userEmail,
                    "1234567890"
                ).send({ from: userAccount });

                alert("Course purchased successfully!");
                console.log("Transaction:", transaction);

                // Update frontend balance
                const newBalance = userBalance.sub(tokenAmount);
                document.getElementById('tokenBalance').textContent = web3.utils.fromWei(newBalance.toString(), 'ether');

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
    </script>

    <script>
       function payWithKhalti(id, name, price) {
             window.location.href = `request-pay.php?course_id=${id}&name=${encodeURIComponent(name)}&token_price=${encodeURIComponent(price)}`;
        }
    </script>
</body>
</html>

<?php include('footer.php'); ?>
