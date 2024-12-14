<?php
include '../includes/config.php';
include '../includes/FileContent_class.php';
// include '../includes/user_sidebar.php'; 
$current_page = 'Speech To Text';
include_once '../includes/session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../assets/css/user_style.css">
    <!-- <script src="../assets/js/Speech-detection.js" defer></script> -->
    <script src="../assets/js/sidebar.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script
        src="https://cdn.jsdelivr.net/npm/microsoft-cognitiveservices-speech-sdk@latest/distrib/browser/microsoft.cognitiveservices.speech.sdk.bundle.js">
        </script>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,400;0,700;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel='stylesheet' href='https://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.css'>
    <link rel="stylesheet" href="../assets/css/speech.css">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- CSS Files -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/now-ui-dashboard.css" rel="stylesheet" />
    <link href="../assets/css/demo.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.15.349/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>


    <title>Word Editor</title>
</head>

<body>
    <div class="wrapper">
        <?php include '../includes/sidebar.php'; ?>
        <div class="page-title">
            <h2>English - Arabic Speech to Text</h2>
        </div>
        <div class="textArea-container">
            <div class="toolbar">
                <div class="head">
                    <input type="text" placeholder="Filename" value="untitled" id="filename">
                    <select onchange="fileHandle(this.value); this.selectedIndex=0">
                        <option value="" selected="" hidden="" disabled="">File</option>
                        <option value="new">New file</option>
                        <option value="txt">Save as txt</option>
                        <option value="pdf">Save as pdf</option>
                    </select>
                    <select onchange="formatDoc('formatBlock', this.value); this.selectedIndex=0;">
                        <option value="" selected="" hidden="" disabled="">Format</option>
                        <option value="h1">Heading 1</option>
                        <option value="h2">Heading 2</option>
                        <option value="h3">Heading 3</option>
                        <option value="h4">Heading 4</option>
                        <option value="h5">Heading 5</option>
                        <option value="h6">Heading 6</option>
                        <option value="p">Paragraph</option>
                    </select>
                    <select id="fontSelect" onchange="formatDoc('fontName', this.value); this.selectedIndex=0;">
                        <option value="" selected="" hidden="" disabled="">Font</option>
                        <option value="Arial">Arial</option>
                        <option value="Courier New">Courier New</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Times New Roman">Times New Roman</option>
                        <option value="Verdana">Verdana</option>
                    </select>

                    <select onchange="formatDoc('fontSize', this.value); this.selectedIndex=0;">
                        <option value="" selected="" hidden="" disabled="">Font size</option>
                        <option value="1">Extra small</option>
                        <option value="2">Small</option>
                        <option value="3">Regular</option>
                        <option value="4">Medium</option>
                        <option value="5">Large</option>
                        <option value="6">Extra Large</option>
                        <option value="7">Big</option>
                    </select>
                    <div class="color">
                        <span>Color</span>
                        <input type="color" oninput="formatDoc('foreColor', this.value); this.value='#000000';">
                    </div>
                    <div class="color">
                        <span>Background</span>
                        <input type="color" oninput="formatDoc('hiliteColor', this.value); this.value='#000000';">
                    </div>
                    <!-- <button id="start-recognition" class="color">Start</button>
                <button id="stop-recognition" class="color" disabled>Stop</button> -->
                    <button id="update-content" class="color">Save</button>
                </div>
                <div class="btn-toolbar">
                    <button onclick="formatDoc('undo')"><i class='bx bx-undo'></i></button>
                    <button onclick="formatDoc('redo')"><i class='bx bx-redo'></i></button>
                    <button onclick="formatDoc('bold')"><i class='bx bx-bold'></i></button>
                    <button onclick="formatDoc('underline')"><i class='bx bx-underline'></i></button>
                    <button onclick="formatDoc('italic')"><i class='bx bx-italic'></i></button>
                    <button onclick="formatDoc('strikeThrough')"><i class='bx bx-strikethrough'></i></button>
                    <button onclick="formatDoc('justifyLeft')"><i class='bx bx-align-left'></i></button>
                    <button onclick="formatDoc('justifyCenter')"><i class='bx bx-align-middle'></i></button>
                    <button onclick="formatDoc('justifyRight')"><i class='bx bx-align-right'></i></button>
                    <button onclick="formatDoc('justifyFull')"><i class='bx bx-align-justify'></i></button>
                    <button onclick="formatDoc('insertOrderedList')"><i class='bx bx-list-ol'></i></button>
                    <button onclick="formatDoc('insertUnorderedList')"><i class='bx bx-list-ul'></i></button>
                    <button onclick="addLink()"><i class='bx bx-link'></i></button>
                    <button onclick="formatDoc('unlink')"><i class='bx bx-unlink'></i></button>
                    <!-- <button id="show-code" data-active="false">&lt;/&gt;</button> -->
                </div>
            </div>
            <input type="hidden" id="userid" value="<?php echo $user_id; ?>">
            <div id="content" contenteditable="true" spellcheck="false">
                <p> <?php include '../includes/FileContent_class.php';
                echo $content; ?></p>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
            integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="../assets/js/word-editor.js" defer></script>




        <script src="../assets/js/sidebar.js"></script>
        <script src="../assets/js/word-editor.js"></script>
        <!--   Core JS Files   -->
        <script src="../assets/js/core/jquery.min.js"></script>
        <script src="../assets/js/core/popper.min.js"></script>
        <script src="../assets/js/core/bootstrap.min.js"></script>
        <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
        <!-- Chart JS -->
        <script src="../assets/js/plugins/chartjs.min.js"></script>
        <!--  Notifications Plugin    -->
        <script src="../assets/js/plugins/bootstrap-notify.js"></script>
        <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="../assets/js/now-ui-dashboard.min.js?v=1.5.0" type="text/javascript"></script>
</body>

</html>