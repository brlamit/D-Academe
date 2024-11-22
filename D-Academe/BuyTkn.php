<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tokensToBuy = $_POST['tokensToBuy'] ?? null;
    $error = null;

    // Validate the input
    if (!$tokensToBuy || !is_numeric($tokensToBuy) || $tokensToBuy <= 0) {
        $error = "Please enter a valid token amount greater than 0.";
    } else {
        try {
            // Simulate the token purchase process
            $transactionDetails = [
                'from' => 'user_wallet_address', // Replace with actual data
                'to' => 'contract_address', // Replace with actual data
                'value' => (float)$tokensToBuy,
                'transactionHash' => uniqid('txn_', true),
                'blockNumber' => rand(100000, 999999),
            ];

            // Simulate saving transaction (e.g., save to a database)
            // Here, you could integrate database logic if required.

            $successMessage = "Tokens purchased successfully! Transaction Hash: " . $transactionDetails['transactionHash'];
        } catch (Exception $e) {
            $error = "Error buying tokens: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Tokens</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #374151, #1F2937);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: linear-gradient(to right, #4CAF50, #00897B);
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }
        .input-field, .button {
            display: block;
            width: 100%;
            margin: 1rem 0;
            padding: 1rem;
            border: none;
            border-radius: 5px;
        }
        .input-field {
            background: #1E293B;
            color: white;
            text-align: center;
        }
        .button {
            background: linear-gradient(to right, #38B2AC, #319795);
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: scale(1.05);
        }
        .error {
            color: #F87171;
        }
        .success {
            color: #68D391;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Buy Tokens</h2>
        <form method="POST">
            <input
                type="number"
                name="tokensToBuy"
                class="input-field"
                placeholder="Enter amount of tokens"
                min="1"
                required
            />
            <button type="submit" class="button">Buy Tokens</button>
        </form>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php elseif (!empty($successMessage)): ?>
            <p class="success"><?= htmlspecialchars($successMessage) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
