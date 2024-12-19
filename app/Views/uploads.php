<?php
    require_once '../config/Database.php';
    require_once '../includes/session.php';
    
    require_once __DIR__ . '/../Models/User.php';
    //set current page to update sidebar status
    $current_page = 'Upload File';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../public/assets/css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- CSS Files -->
    <link href="../../public/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../public/assets/css/now-ui-dashboard.css" rel="stylesheet" />
    <link href="../../public/assets/css/demo.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.15.349/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>


    <link href="../../public/assets/css/uploads.css" rel="stylesheet" />

<script>
    // Specify the worker source location for PDF.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.15.349/pdf.worker.min.js";
</script>

</head>

<body>
    <div class="container">
        <?php include '../includes/sidebar.php'; ?>


        <div class="containers">
    <div class="card">
        <h3>Upload Files</h3>
        <div class="drop_box">
            <header>
                <h4>Select File here</h4>
            </header>
            <p>Files Supported: PDF, TEXT, DOC , DOCX</p>
            <input type="file" hidden accept=".doc,.docx,.pdf" id="fileID" style="display:none;">
            <button class="btn">Choose File</button>
        </div>
    </div>
    <!-- Add this status element -->
    <div id="upload-status" style="margin-top: 10px; font-weight: bold;"></div>
</div>


    </div>
   

    <script src="../../public/assets/js/sidebar.js"></script>
    <script src="../../public/assets/js/uploads.js"></script>
    <!--   Core JS Files   -->
    <script src="../../public/assets/js/core/jquery.min.js"></script>
    <script src="../../public/assets/js/core/popper.min.js"></script>
    <script src="../../public/assets/js/core/bootstrap.min.js"></script>
    <script src=".../../public/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!-- Chart JS -->
    <script src="../../public/assets/js/plugins/chartjs.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src=".../../public/assets/js/plugins/bootstrap-notify.js"></script>
    <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../../public/assets/js/now-ui-dashboard.min.js?v=1.5.0" type="text/javascript"></script>
</body>

</html>