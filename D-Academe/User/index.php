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

        $allowedPages = ['home','buy-token', 'learn-more', 'viewcourse', 'myLearnings', 'buy-course', 'buy', 'solidity-basic', 'view_profile', 'live-class','about', 'courses' , 'help', 'Solidity-Disc', 'Solidity-Course','userregister-form','Clarity_course','learnmore','privacypolicy','termofservice', 'enrolled-course'];
       
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
