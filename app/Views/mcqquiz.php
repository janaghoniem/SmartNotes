<?php
@session_start();
include '../config/Database.php';
include '../Models/file_class.php';
require_once __DIR__ . '/../../app/Controllers/FileController.php';
require_once __DIR__ . '/../../app/Controllers/FileGenController.php';
use App\Controllers\FileController;
$fileController = new FileController();
$GenController = new FileGenController();

// require '../includes/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';


use GuzzleHttp\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$current_page = 'Quiz';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['regenerate_mcq'])) {
    $text = $_SESSION['text'] ?? "No text available.";

    $mcq_prompt = "Generate multiple-choice questions and their answers based on the following text: " . $text . " Format: 
    **Question x:** 
    - Question text 
    a) Option A 
    b) Option B 
    c) Option C 
    d) Option D 
    **Answer: c)**";

    $client = new Client();
    $logger = new Logger('gemini_logger');
    $logger->pushHandler(new StreamHandler(__DIR__ . '/logs/app.log', Logger::DEBUG));
    
    try {
        $response = $client->request('POST', 'http://localhost:3000/summarize', [
            'json' => [
                'prompt' => $mcq_prompt
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        $mcq = $data['summary'] ?? 'No multiple-choice questions available';
        
        // Store the MCQs in the session
        $_SESSION['mcq'] = $mcq;
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        $logger->error('Error in generating MCQs', ['message' => $e->getMessage()]);
    }
  }

if (isset($_SESSION['file_id']) && $_SESSION['file_id'] !== null) {
    $file_id = $_SESSION['file_id'];
} else {
    echo "File ID is missing. Please go back and select a valid file.";
    exit;
}

if (isset($_SESSION['mcq'])) {
    $mcq = $_SESSION['mcq'];
    unset($_SESSION['mcq']);
} else {
    echo "No mcq data found.";
    exit;
}

// Assuming $mcq is a string that contains your MCQs
$mcq_lines = explode("\n", $mcq);
$questions = [];
$question = '';
$answers = [];
$correct_answer = '';

$question_pattern = '/^\*\*Question \d+:\s*(.*)$/';
$answer_pattern = '/^([a-d])\)\s*(.*)$/';
$correct_answer_pattern = '/^\*\*Answer:\s*([a-d])\)/i';

foreach ($mcq_lines as $line) {
    $line = trim($line);

    if (empty($line)) {
        continue; // Skip empty lines
    }

    if (preg_match($question_pattern, $line, $matches)) {
        // If a new question starts, save the current one
        if (!empty($question)) {
            $questions[] = [
                'question' => $question,
                'answers' => $answers,
                'correct_answer' => $correct_answer,
            ];
        }
        $question = trim($matches[1], "*");
        $answers = [];
        $correct_answer = '';
    } elseif (preg_match($answer_pattern, $line, $matches)) {
        $answers[$matches[1]] = $matches[2];
    } elseif (preg_match($correct_answer_pattern, $line, $matches)) {
        $correct_answer = $matches[1];
    }
}

if (!empty($question)) {
    $questions[] = [
        'question' => $question,
        'answers' => $answers,
        'correct_answer' => $correct_answer,
    ];
}

// Debugging: Check the parsed output
// echo "<pre>";
// echo htmlspecialchars($mcq); // Escape HTML for readability
// echo "</pre>";

// echo "<pre>Parsed Questions:\n";
// print_r($questions);
// echo "</pre>";

// // Display parsed questions and answers
// foreach ($questions as $q) {
//     echo "Question: " . htmlspecialchars($q['question']) . "<br>";
//     echo "Correct Answer: " . htmlspecialchars($q['correct_answer']) . "<br>";
// }

?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MCQ Quiz</title>
    <link href="../../public/assets/css/mcqquiz.css" rel="stylesheet">
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- CSS Files -->
    <link href="../../public/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../public/assets/css/user_style.css">

    <link href="../../public/assets/css/now-ui-dashboard.css" rel="stylesheet" />
    <link href="../../public/assets/css/demo.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        button:not(:enabled) {
            background-color: #e0e0e0;
            color: #777;
            cursor: not-allowed; 
            opacity: 0.6;
            border: none;
        }
        .inactive-btn:disabled {
            background-color: #e0e0e0;
            color: #888;
        }

        .dark-placeholder::placeholder {
            color: black !important; 
            opacity: 1;
        }
        .buttons-container {
            text-align: center; /* Center-align the button */
            margin-bottom: 20px; /* Add some space below the button */
        }
    </style>
</head>

<body>
    <?php include '../includes/sidebar.php'; ?>

   

    <div class="quiz-container">
        <?php 
        $counter = 1;
        foreach ($questions as $question) {
            $isActive = ($counter === 1) ? 'active' : ''; 
        ?>
            <div class="quiz-box <?= $isActive ?>" id="question<?= $counter ?>" data-correct-answer="<?= htmlspecialchars($question['correct_answer']) ?>">
                <div class="text-center pb-4">
                    <h5 class="font-weight-bold">Question <?= $counter ?> of <?= count($questions) ?></h5>
                </div>
                <h4 class="font-weight-bold"><?= htmlspecialchars($question['question']) ?></h4>
                <form>
                    <?php foreach ($question['answers'] as $key => $answer): ?>
                        <div class="answer-options-container">
                            <label class="answer-options">
                                <input type="radio" name="option<?= $counter ?>" value="<?= htmlspecialchars($answer) ?>" data-key="<?= htmlspecialchars($key) ?>">
                                <?= htmlspecialchars($answer) ?>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </form>
                <div class="d-flex justify-content-between mt-3">
                    <?php if ($counter > 1): ?>
                        <button type="button" class="btn btn-primary" onclick="navigateQuestion(-1)">Previous</button>
                    <?php endif; ?>
                    <?php if ($counter < count($questions)): ?>
                        <button type="button" class="btn btn-primary" onclick="navigateQuestion(1)">Next</button>
                    <?php else: ?>
                        <button type="button" class="btn btn-success" onclick="submitQuiz()">Submit</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php 
            $counter++;
        } 
        ?>
    </div>

    <div class="buttons-container">
        <form method="POST">
            <button type="submit" name="regenerate_mcq" class="btn btn-primary">
                Regenerate
            </button>
        </form>
    </div>
</body>


   



<script src="../../public/assets/js/sidebar.js"></script>
<script src=" ../../Public/assets/js/mcqquiz.js"></script>


<!-- Core JS Files -->
<script src="../../public/assets/js/core/jquery.min.js"></script>
<script src="../../public/assets/js/core/popper.min.js"></script>
<script src="../../public/assets/js/core/bootstrap.min.js"></script>
<script src="../../public/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<!-- Chart JS -->
<script src="../../public/assets/js/plugins/chartjs.min.js"></script>
<!-- Notifications Plugin -->
<script src="../../public/assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Now Ui Dashboard -->
<script src="../../public/assets/js/now-ui-dashboard.min.js?v=1.5.0" type="text/javascript"></script>
</body>
</html>
