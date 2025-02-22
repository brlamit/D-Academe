<?php
// khalti-payment.php

// Get the number of tokens and total price from the form
$tokensToBuy = isset($_POST['tokensToBuy']) ? $_POST['tokensToBuy'] : 0;
$totalPriceNRS = isset($_POST['totalPriceNRS']) ? $_POST['totalPriceNRS'] * 100 : 0; // Convert to paisa (Khalti uses paisa)

if ($tokensToBuy <= 0 || $totalPriceNRS <= 0) {
    echo "Invalid token or price data.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khalti Payment</title>
    <script src="https://khalti.com/static/khalti-checkout.js"></script>
</head>
<body>
    <h2>Proceed with Khalti Payment</h2>
    <p>Number of Tokens: <?php echo $tokensToBuy; ?></p>
    <p>Total Price: <?php echo $totalPriceNRS / 100; ?> NRS</p>
    <button id="khaltiButton" 
                    onclick="payWithKhalti()"
                    class="mt-4 bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded w-full">
                    Pay with Khalti
                </button>

<script>
var tokensToBuy = <?php echo json_encode($tokensToBuy); ?>;
var totalPriceNRS = <?php echo json_encode($totalPriceNRS); ?>;

function payWithKhalti() {
    window.location.href = `token-buy-request.php?token_amt=${tokensToBuy}&total_price=${totalPriceNRS}`;
}
</script>

</body>
</html>
