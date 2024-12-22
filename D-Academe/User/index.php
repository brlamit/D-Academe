<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D-Academe</title>
   <style>
    body{
        background: linear-gradient(to bottom,  #203f43, #2c8364);
           /* background-image: url('assets/hero2.webp'); */
           background-size: cover;
            background-position: center;
    }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex flex-col min-h-screen bg-green-100">
    <?php include_once 'header.php'; ?>

    <!-- Main content wrapper -->
    <main class="flex-grow">
        <?php 
        $page = $_GET['page'] ?? 'home';
        
        // Use a safe approach to include files to prevent potential security risks
        $allowedPages = ['home','buy-token', 'myLearnings', 'buy-course', 'solidity-basic', 'live-class', 'cart', 'about', 'courses' , 'help', 'Solidity-Disc', 'Solidity-Course','userregister','Clarity_course']; // Add allowed pages here
        if (in_array($page, $allowedPages)) {
            include $page . '.php';
        } else {
            include 'home.php';
        }
        ?>
    </main>

    

    <!-- Footer -->
    <?php include_once 'footer.php'; ?>
</body>
</html>


   

    <!-- <script src="https://cdn.jsdelivr.net/npm/web3/dist/web3.min.js"></script>
    <script>
        async function connectWallet() {
            if (window.ethereum) {
                const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
                const account = accounts[0];
                document.cookie = `account=${account}`;
                location.reload();
            } else {
                alert('Please install MetaMask.');
            }
        }
    </script> -->
<!-- </body>
</html> -->
