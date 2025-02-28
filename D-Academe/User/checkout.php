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
$user_wallet_address = $_SESSION['account'];

// Fetch balances from session
$sessionAccount = isset($_SESSION['account']) ? $_SESSION['account'] : '';
$sessionEthBalance = isset($_SESSION['ETH Balance']) ? $_SESSION['ETH Balance'] : '0';
$sessionTokenBalance = isset($_SESSION['Token Balance']) ? $_SESSION['Token Balance'] : '0';

// Get course details from URL parameters with validation
$course_details = [
    'id' => isset($_GET['course_id']) ? (int) $_GET['course_id'] : 0,
    'name' => isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Unknown',
    'description' => isset($_GET['description']) ? htmlspecialchars($_GET['description']) : 'No description available.',
    'price_in_tokens' => isset($_GET['token_price']) ? (float) $_GET['token_price'] : 0
];

// It's better to fetch course details from the database using the course_id for validation
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/web3/1.7.3/web3.min.js"></script>
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
</head>
<body class="bg-gray-100">
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
    <div class="container mx-auto my-10">
        <form class="row g-3 w-50 mt-4" id="paymentForm" action="" method="POST">
            <div class="p-6 rounded-lg shadow-lg max-w-2xl mx-auto ">
                <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Course Details</h1>
                <div class="mb-2">
                    <strong class="block text-gray-700">Course Name:</strong>
                    <?php echo $course_details['name']; ?>
                </div>
                <div class="mb-2">
                    <strong class="block text-gray-700">Description:</strong>
                    <?php echo $course_details['description']; ?>
                </div>
                <div class="mb-2">
                    <strong class="block text-gray-700">Price in Tokens:</strong>
                    <?php echo number_format($course_details['price_in_tokens'], 2); ?>
                </div>
            </div>
            
            <!-- Hidden fields to pass data to request-pay.php -->
            <input type="hidden" name="course_id" value="<?php echo $course_details['id']; ?>">
            <input type="hidden" name="course_name" value="<?php echo $course_details['name']; ?>">
            <input type="hidden" name="course_price" value="<?php echo $course_details['price_in_tokens']; ?>">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="user_name" value="<?php echo $user_name; ?>">
            <input type="hidden" name="user_email" value="<?php echo $user_email; ?>">
            
            <!-- Pay with Khalti Button -->
            <button type="button" id="khaltiButton" 
    onclick="payWithKhalti(<?php echo (int)$course_details['id']; ?>, '<?php echo htmlspecialchars($course_details['name'], ENT_QUOTES, 'UTF-8'); ?>', <?php echo (float)$course_details['price_in_tokens']; ?>)"
    class="mt-4 bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded w-full">
    Pay with Khalti
</button>

        </form>
    </div>
</body>
<script>
// Khalti Checkout Script
function payWithKhalti(id, name, price) {
    window.location.href = `request-pay.php?course_id=${id}&name=${encodeURIComponent(name)}&token_price=${encodeURIComponent(price)}`;

    
}
</script>
</html>
