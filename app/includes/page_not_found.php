<?php
include_once 'User.php';
session_start();	
if(!$_SESSION['UserID']){
    Header('Location: ../pages/login.php');
}
$user = new User($_SESSION['UserID']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Error</title>
    <base href="/AI-Powered-Note-Taking-Website/">
    
    <!-- Use absolute path for CSS -->
    <link rel="stylesheet" href="assets/css/backend.css?v=1.0.0">
</head>

<body>
    <div id="loading">
        <div id="loading-center"></div>
    </div>
    <div class="wrapper">
        <div class="container">
            <div class="row no-gutters height-self-center">
                <div class="col-sm-12 text-center align-self-center">
                    <div class="iq-error position-relative">
                        <img src="assets/images/404.png" class="img-fluid iq-error-img" alt="">
                        <h2 class="mb-0 mt-4">Oops! This Page is Not Found.</h2>
                        <p>The requested page does not exist.</p>
                        <a class="btn btn-primary d-inline-flex align-items-center mt-3" href="<?php echo "/AI-Powered-Note-Taking-Website/pages/".$user->userType_obj->pages_array[0]->link_address; ?>">
                            <i class="ri-home-4-line"></i>Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Use absolute paths for JS -->
    <script src="assets/js/backend-bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>
