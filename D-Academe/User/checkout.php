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
    <style>
         body {
            background: linear-gradient(to bottom, #203f43, #2c8364);
            background-size: cover;
            background-position: center;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="">
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
    <div class="container mx-auto my-10 py-16 relative">
        
    <form class="w-full max-w-2xl mx-auto mt-6 p-6  rounded-2xl shadow-xl border border-gray-700 relative" id="paymentForm" action="" method="POST">
    <button type="button" onclick="goBack()" class="absolute top-4 left-4 bg-transparent underline text-white py-2 px-4 text-sm rounded-lg font-semibold hover:text-teal-500 transition-all shadow-lg">
    Go Back
</button>   
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

    <!-- Hidden Fields -->
    <input type="hidden" name="course_id" value="<?php echo $course_details['id']; ?>">
    <input type="hidden" name="course_name" value="<?php echo $course_details['name']; ?>">
    <input type="hidden" name="course_price" value="<?php echo $course_details['price_in_tokens']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
    <input type="hidden" name="user_name" value="<?php echo $user_name; ?>">
    <input type="hidden" name="user_email" value="<?php echo $user_email; ?>">

    <!-- Pay with Khalti Button -->
    <div class="text-center mt-6">
        <button type="button" id="khaltiButton" 
            onclick="payWithKhalti(<?php echo (int)$course_details['id']; ?>, '<?php echo htmlspecialchars($course_details['name'], ENT_QUOTES, 'UTF-8'); ?>', <?php echo (float)$course_details['price_in_tokens']; ?>)"
            class="bg-purple-600 hover:bg-purple-700 text-white text-lg font-bold py-3 px-6 rounded-xl shadow-lg w-full transition-all">
            Pay with Khalti
        </button>
    </div>
</form>

    </div>
</body>
<script>
// Khalti Checkout Script
function payWithKhalti(id, name, price) {
    window.location.href = `request-pay.php?course_id=${id}&name=${encodeURIComponent(name)}&token_price=${encodeURIComponent(price)}`;

    
}
function goBack() {
  window.history.back();
}   
</script>
</html>

<?php include('footer.php'); ?>
