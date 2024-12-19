<?php
// include '../includes/user_sidebar.php';
include '../config/Database.php';
require_once __DIR__ . '/../../app/Controllers/FileController.php';
require_once __DIR__ . '/../../app/Controllers/GenContentController.php';




// if (isset($_GET['id'])) {
//     $_SESSION['file_id'] = intval($_GET['id']);
// } else {
//     // Handle the case where no file_id is passed
//     $_SESSION['file_id'] = null; // or set a default value
// }
// //set current page to update sidebar status
// $current_page = 'My Note';
// $file_id = isset($_GET['id']) ? intval($_GET['id']) : null;

// if ($file_id !== null) {
//   $sql = "SELECT content FROM files WHERE id=$file_id";
//   $result = $conn->query($sql);

//   if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//       $content = $row["content"];
//     }
//   } else {
//     $content = "No content found.";
//   }
// } else {
//   $content = "Invalid file ID.";
// }
// $folder_sql = "SELECT folder_id FROM files WHERE id = $file_id";
// $folder_result = $conn->query($folder_sql);

// if ($folder_result->num_rows > 0) {
//   $folder_row = $folder_result->fetch_assoc();
//   $folder_id = $folder_row['folder_id'];

//   echo "Folder ID: " . $folder_id;
// } else {
//   echo "No folder found for the provided file ID.";
// }

//----------------------------------------------------------------------------------------------------
@session_start();

$fileController = new FileController();

// Set current page to update sidebar status
$current_page = 'My Note';

// Get file ID from the query parameter
$file_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$_SESSION['file_id'] = $file_id;

// Retrieve file content
if ($file_id !== null) {
  $content = $fileController->getFileContent($file_id);
  if (!$content) {
    $content = "No content found.";
  }
} else {
  $content = "Invalid file ID.";
}

// Retrieve folder ID
$folder_id = $file_id !== null ? $fileController->getFolderId($file_id) : null;

if ($folder_id !== null) {
  echo "Folder ID: " . $folder_id;
} else {
  echo "No folder found for the provided file ID.";
}

$user_id = $_SESSION['UserID'];
echo $user_id;
//-----------------------------------------------------------------------------------------------------

// Disable strict mode temporarily
//$conn->query("SET sql_mode = ''");



error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../vendor/autoload.php';

// <?php

use GuzzleHttp\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Initialize Logger
$logger = new Logger('gemini_logger');
$logger->pushHandler(new StreamHandler(__DIR__ . '/logs/app.log', Logger::DEBUG));

//Initialize the HTTP client
$client = new Client();

// Text to summarize
$text = $content;
$_SESSION['text'] = $text;
// Initialize summary variable
$summary = "";

// Handle the form submission for generating the summary
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
//   // Construct the prompt for summarization
//   $prompt = "summarize the following text: " . $text;

//   try {
//     // Make the API request to the Node.js service
//     $response = $client->request('POST', 'http://localhost:3000/summarize', [
//       'json' => [
//         'prompt' => $prompt
//       ]
//     ]);
//     $data = json_decode($response->getBody(), true);
//     //echo json_encode($data); 
//     // Output the summary
//     $summary = $data['summary'] ?? 'No summary available';
//   } catch (Exception $e) {
//     echo "Error: " . $e->getMessage();
//     $logger->error('Error in summarization', ['message' => $e->getMessage()]);
//   }
// }
// if (isset($_POST['edit']) && isset($file_id)) {
//   // Redirect to the speech.php page with the file_id parameter
//   header("Location: ../Views/speech.php?id=" . $file_id);

//   exit(); // Ensure no further processing occurs
// }


//mcq and flashcards


$mcq = "";
$qa = "";

// Handle the form submission for generating multiple-choice questions
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_mcq'])) {
//   $mcq_prompt = "Generate many multiple-choice questions and their answers based on the following text: " . $text;
//   try {
//     $response = $client->request('POST', 'http://localhost:3000/summarize', [
//       'json' => [
//         'prompt' => $mcq_prompt
//       ]
//     ]);
//     $data = json_decode($response->getBody(), true);
//     $mcq = $data['summary'] ?? 'No multiple-choice questions available';

//     // Store the MCQs in the session
//     $_SESSION['mcq'] = $mcq;
//     var_dump($_SESSION['mcq']); // Debug to check the data being stored
//     header('Location: mcqquiz.php');
//   } catch (Exception $e) {
//     echo "Error: " . $e->getMessage();
//     $logger->error('Error in generating MCQs', ['message' => $e->getMessage()]);
//   }
// }

// Handle the form submission for generating questions and answers
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_qa'])) {
//   $qa_prompt = "Generate questions and answers from the following text: " . $text . "\nPlease format the output as follows: \nQuestion 1: <question text>\nAnswer 1: <answer text>\nQuestion 2: <question text>\nAnswer 2: <answer text>";

//   try {
//     $response = $client->request('POST', 'http://localhost:3000/summarize', [
//       'json' => [
//         'prompt' => $qa_prompt
//       ]
//     ]);
//     $data = json_decode($response->getBody(), true);
//     $qa = $data['summary'] ?? 'No questions and answers available';
//     $_SESSION['qa'] = $qa;
//     var_dump($_SESSION['qa']); // Debug to check the data being stored
//     header('Location: NEWflashcards.php');
//   } catch (Exception $e) {
//     echo "Error: " . $e->getMessage();
//     $logger->error('Error in generating Q&A', ['message' => $e->getMessage()]);
//   }
// }
//------------------------------------------------------------------------------------------------------
$GenController = new FileGenController();

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//   if (isset($_POST['generate_summary'])) {
//       $summary = $GenController->generateSummary($text);
//   } elseif (isset($_POST['generate_mcqs'])) {
//       $mcqs = $GenController->generateMCQs($text);
//   } elseif (isset($_POST['generate_qa'])) {
//       $qa = $GenController->generateQA($text);
//   } elseif (isset($_POST['save_summary'])) {
//       $GenController->saveSummary($fileId, $_POST['summary']);
//       $message = "Summary saved successfully.";
//   }
// }
$message = '';


// if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['save']))) {

//   $message = $GenController->save();
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['save'])) {
      $message = $GenController->save();
  } elseif (isset($_POST['generate'])) {
      $summary = $GenController->generateSummary($text);
  } elseif (isset($_POST['generate_mcq'])) {
      $GenController->generateMCQ($text);
  } elseif (isset($_POST['generate_qa'])) {
      $GenController->generateQA($text);
  } elseif (isset($_POST['edit']) && isset($_POST['file_id'])) {
      $file_id = htmlspecialchars($_POST['file_id']);
      header("Location: ../Views/speech.php?id=" . $file_id);
      exit();
  }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link href="../../public/assets/images/notes.png" rel="icon">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/user_style.css">
  <!-- <script src="../assets/js/sidebar.js"></script> -->
  <script src="../assets/js/Note.js"></script>
  <link rel='stylesheet' href='https://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.css'>
  <!-- <link rel="stylesheet" href="./style.css"> -->
  <link rel="stylesheet" href="../../public/assets/css/Note.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,400;0,700;1,400&display=swap"
    rel="stylesheet">


  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- CSS Files -->
  <link href="../../public/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../../public/assets/css/now-ui-dashboard.css" rel="stylesheet" />
  <link href="../../public/assets/css/demo.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../public/assets/css/user_style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <title>Document</title>
</head>

<body class="Bootstrap-body">

  <!-- <h1 class="text-center">Esm el folder w el note el mafto7a</h1> -->
  <div id="container-fluid">
    <div class="wrapper">
      <?php include '../includes/sidebar.php'; ?>
      <div class="Summary-container">
        <div class="row come-in">

          <h1>Text Summarization Result</h1>

          <p><strong>Original Text:</strong> <?= htmlspecialchars($text) ?></p>

        <!-- Form with Generate button -->
        <form method="POST" id="generateForm" action="">
          <button type="submit" name="generate">Generate Summary</button>
        </form>

        <?php if (!empty($summary)): ?>
          <p><strong>Summary:</strong> <?= htmlspecialchars($summary) ?></p>

          <!-- Feedback Message -->
          <?= $message ?>
          <!-- Save button with an AJAX submit -->
          <form method="POST" id="saveForm" action="">
            <input type="hidden" name="name" value="Habibaazzz Summary">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
            <input type="hidden" name="folder_id" value="<?= htmlspecialchars($folder_id) ?>">
            <input type="hidden" name="content" value='<?= htmlspecialchars(json_encode(["S" => $summary])) ?>'>
            <input type="hidden" name="file_type" value="2">
            <button type="submit" name="save" id="save">Save Summary</button>
          </form>

          <!-- <?php //if (!empty($summary)): ?>
            <p><strong>Summary:</strong> <?php //htmlspecialchars($summary) ?></p>

            <form method="POST" id="saveForm">
              <button type="submit" name="save" id="save" data-summary="<?php //htmlspecialchars($summary) ?>">Save
                Summary</button>
            </form>
          <?php //endif; ?>
        <form method="POST" id="editForm" action="">
          <button type="submit" name="edit">Edit</button>
        </form> -->



          <form method="POST" id="editForm">
            <button type="submit" name="edit">Edit</button>
          </form>



          <div id="message"></div>


          <!-- Form for Generating MCQs -->
          <!-- <form method="POST" id="generateMCQForm">
        <button type="submit" name="generate_mcq">Generate MCQs</button>
    </form> -->

          <!-- <form action="" method="POST" id="generateMCQForm">

            <input type="hidden" name="file_id" value="<? //isset($_SESSION['file_id']) ? $_SESSION['file_id'] : '' ?>">
            <input type="hidden" name="mcq" value="<?// htmlspecialchars($mcq) ?>">
            <input type="hidden" name="text" value="<?// htmlspecialchars($text) ?>">

            <button type="submit" name="generate_mcq">Generate MCQs2</button>
          </form> -->

          <!-- Display MCQs -->
          <!-- <?php // if (!empty($mcq)): ?>
            <p><strong>Multiple-Choice Questions:</strong></p>
            <pre><?// htmlspecialchars($mcq) ?></pre>
          <?php //endif; ?> -->

          <!-- Form for Generating Q&A -->
<!-- 
          <form action="" method="POST" id="generateQnA">
            <input type="hidden" name="file_id" value="<?// isset($_SESSION['file_id']) ? $_SESSION['file_id'] : '' ?>">
            <input type="hidden" name="qa" value="<?// htmlspecialchars($qa) ?>">
            <input type="hidden" name="text" value="<?// htmlspecialchars($text) ?>">


            <button type="submit" name="generate_qa">Generate QnA2</button>
          </form> -->

          <!-- Display Q&A -->
          <!-- <?php // if (!empty($qa)):
            //var_dump($qa);
            ?> -->
<!-- 
            <p><strong>Questions and Answers:</strong></p>
            <pre><?// htmlspecialchars($qa) ?></pre> -->
<!-- 
          <?php // endif; ?>
        </div> -->
        <!-- Form for Generating MCQs -->
        <!-- <form method="POST" id="generateMCQForm">
        <button type="submit" name="generate_mcq">Generate MCQs</button>
    </form> -->

        <form action="" method="POST" id="generateMCQForm" action="">

          <input type="hidden" name="file_id" value="<?= isset($_SESSION['file_id']) ? $_SESSION['file_id'] : '' ?>">
          <input type="hidden" name="mcq" value="<?= htmlspecialchars($mcq) ?>">
          <input type="hidden" name="text" value="<?= htmlspecialchars($text) ?>">

          <button type="submit" name="generate_mcq">Generate MCQs2</button>
        </form>

        <!-- Display MCQs -->
        <?php if (!empty($mcq)): ?>
          <p><strong>Multiple-Choice Questions:</strong></p>
          <pre><?= htmlspecialchars($mcq) ?></pre>
        <?php endif; ?>

        <!-- Form for Generating Q&A -->

        <form action="" method="POST" id="generateQnA" action="">
          <input type="hidden" name="file_id" value="<?= isset($_SESSION['file_id']) ? $_SESSION['file_id'] : '' ?>">
          <input type="hidden" name="qa" value="<?= htmlspecialchars($qa) ?>">
          <input type="hidden" name="text" value="<?= htmlspecialchars($text) ?>">


          <button type="submit" name="generate_qa">Generate QnA2</button>
        </form>

        <!-- Display Q&A -->
        <?php if (!empty($qa)):
          var_dump($qa);
          ?>

          <p><strong>Questions and Answers:</strong></p>
          <pre><?= htmlspecialchars($qa) ?></pre>

        <?php endif; ?>
      </div>
    </div>

  </div>
  </div>

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
<script>

  var sessionUserID = <?php echo json_encode($_SESSION['UserID']); ?>;
  var ID = sessionUserID;
  var folderId = <?php echo json_encode($folder_id); ?>;
</script>

<?php endif; ?>

</html>