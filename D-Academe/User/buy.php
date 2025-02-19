<?php
session_start();
include('dbconnection.php'); // Database connection
include('header.php'); // Header

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    die("You need to be logged in to buy a course.");
}

// Get user details from session
$user_id = $_SESSION['id'];
$user_email = $_SESSION['email'];
$user_name = $_SESSION['name'];

// Get course details from URL parameters
$course_details = [
    'name' => $_GET['name'],
    'description' => $_GET['description'],
    'price_in_tokens' => $_GET['token_price'],
    'instructor' => 'Unknown', // Default value
    'duration' => 'Unknown'   // Default value
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/web3/1.7.3/web3.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto my-10">
        <div class=" p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Course Details</h1>
            <div class="mb-2"><strong class="block text-gray-700">Course Name:</strong> <?php echo htmlspecialchars($course_details['name']); ?></div>
            <div class="mb-2"><strong class="block text-gray-700">Description:</strong> <?php echo htmlspecialchars($course_details['description']); ?></div>
            <div class="mb-2"><strong class="block text-gray-700">Price in Tokens:</strong> <?php echo htmlspecialchars($course_details['price_in_tokens']); ?></div>
           
            <!-- Buy Course Button -->
            <button onclick="buyCourse('<?php echo $course_details['price_in_tokens']; ?>')"
                class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                Buy Course
            </button>
        </div>
    </div>

    <script>
        async function buyCourse(price) {
            if (typeof window.ethereum !== "undefined") {
                const web3 = new Web3(window.ethereum);
                await window.ethereum.request({ method: "eth_requestAccounts" });

                const userAccount = (await web3.eth.getAccounts())[0];

                // Replace with your actual smart contract address and ABI
                const contractAddress = "YOUR_CONTRACT_ADDRESS";
                const contractABI = []; // Add your contract ABI here

                const contract = new web3.eth.Contract(contractABI, contractAddress);
                
                try {
                    const transaction = await contract.methods.buyCourse().send({
                        from: userAccount,
                        value: web3.utils.toWei(price, "ether"),
                    });

                    alert("Course purchased successfully!");
                    console.log("Transaction:", transaction);
                } catch (error) {
                    console.error("Transaction failed:", error);
                    alert("Purchase failed. Please try again.");
                }
            } else {
                alert("MetaMask is not installed. Please install it to proceed.");
            }
        }
    </script>
</body>
</html>

<?php include('footer.php'); // Footer ?>
