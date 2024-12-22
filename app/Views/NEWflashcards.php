<?php
@session_start();
include '../config/Database.php';
include '../Models/file_class.php';
require_once __DIR__ . '/../../app/Controllers/FileController.php';
require_once __DIR__ . '/../../app/Controllers/FileGenController.php';
use App\Controllers\FileController;
$fileController = new FileController();
$GenController = new FileGenController();

$current_page = 'Generated Flash Cards';
// require '../includes/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use GuzzleHttp\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


$file_id = isset($_SESSION['file_id']) ? $_SESSION['file_id'] : null;
$folder_id = isset($_SESSION['folder_id']) ? $_SESSION['folder_id'] : null;
$content = isset($_SESSION['content']) ? $_SESSION['content'] : "No content available.";
$user_id = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['saveQA'])) {
      $message = $GenController->saveQA();}}


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['regenerate_qa'])) {
    $text = $_SESSION['text'] ?? "No text available."; // Get text from session

    // Construct prompt for Q&A generation
    $qa_prompt = "Generate questions and answers from the following text: " . $text . "\nPlease format the output as follows: \nQuestion 1: <question text>\nAnswer 1: <answer text>\nQuestion 2: <question text>\nAnswer 2: <answer text>";

    $client = new Client();
    $logger = new Logger('gemini_logger');
    $logger->pushHandler(new StreamHandler(__DIR__ . '/logs/app.log', Logger::DEBUG));

    try {
        // API request to generate Q&A
        $response = $client->request('POST', 'http://localhost:3000/summarize', [
            'json' => [
                'prompt' => $qa_prompt
            ]
        ]);
        $data = json_decode($response->getBody(), true);

        // Extract Q&A from the response
        $qa = $data['summary'] ?? 'No questions and answers available';

        if ($qa === 'No questions and answers available') {
            echo "Error: Generated Q&A is empty.";
        }

        // Store the generated Q&A in the session
        $_SESSION['qa'] = $qa;
    } catch (Exception $e) {
        // Handle errors
        echo "Error during Q&A regeneration: " . $e->getMessage();
        $logger->error('Error in regenerating Q&A', ['message' => $e->getMessage()]);
    }
}

// Check if file_id is set in session
if (isset($_SESSION['file_id']) && $_SESSION['file_id'] !== null) {
    $file_id = $_SESSION['file_id'];
    // Proceed with the SQL query
} else {
    echo "File ID is missing. Please go back and select a valid file.";
    exit; // Exit if file ID is missing
}


if (isset($_SESSION['qa'])) {
    $qa = $_SESSION['qa'];
} else {
    echo "No Q&A data foundhhhhhhhhhhhhhhhh.";
    exit;
}



$qa_lines = explode("\n", $qa);

$flashcards = [];

$card_number = 1; 

for ($i = 0; $i < count($qa_lines); $i++) {
    if (strpos($qa_lines[$i], 'Question') === 0) {

        $question = trim(preg_replace('/^Question \d+:/', '', $qa_lines[$i]));

        $answer = isset($qa_lines[$i + 1]) ? trim(preg_replace('/^Answer \d+:/', '', $qa_lines[$i + 1])) : "No answer available";

        
        $flashcards[] = [
            'question' => $question,
            'answer' => $answer,
            'name' => 'Flashcard ' . $card_number 
        ];

        $card_number++; 
        $i++; 
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Note cards</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../public/assets/css/user_style.css">
    
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
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
    	body{margin-top:20px;}
        .container {
        margin-left: 100px; 
        float: right;
        display: flex;
        flex-direction: column; 
        justify-content: space-between; 
        height: 100%; 
        position: relative; 
        padding: 20px;
        box-sizing: border-box;
    }
.card-big-shadow {
    max-width: 320px;
    position: relative;
}

.coloured-cards .card {
    margin-top: 30px;
}

.card[data-radius="none"] {
    border-radius: 0px;
}
.card {
    border-radius: 8px;
    box-shadow: 0 2px 2px rgba(204, 197, 185, 0.5);
    background-color: #FFFFFF;
    color: #252422;
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
}


.card[data-background="image"] .title, .card[data-background="image"] .stats, .card[data-background="image"] .category, .card[data-background="image"] .description, .card[data-background="image"] .content, .card[data-background="image"] .card-footer, .card[data-background="image"] small, .card[data-background="image"] .content a, .card[data-background="color"] .title, .card[data-background="color"] .stats, .card[data-background="color"] .category, .card[data-background="color"] .description, .card[data-background="color"] .content, .card[data-background="color"] .card-footer, .card[data-background="color"] small, .card[data-background="color"] .content a {
    color: #FFFFFF;
}
.card.card-just-text .content {
    padding: 50px 65px;
    text-align: center;
}
.card .content {
    padding: 20px 20px 10px 20px;
}
.card[data-color="blue"] .category {
    color: #7a9e9f;
}

.card .category, .card .label {
    font-size: 14px;
    margin-bottom: 0px;
}
.card-big-shadow:before {
    background-image: url("http://static.tumblr.com/i21wc39/coTmrkw40/shadow.png");
    background-position: center bottom;
    background-repeat: no-repeat;
    background-size: 100% 100%;
    bottom: -12%;
    content: "";
    display: block;
    left: -12%;
    position: absolute;
    right: 0;
    top: 0;
    z-index: 0;
}
h4, .h4 {
    font-size: 1.5em;
    font-weight: 600;
    line-height: 1.2em;
}
h6, .h6 {
    font-size: 0.9em;
    font-weight: 600;
    text-transform: uppercase;
}
.card .description {
    font-size: 16px;
    color: #66615b;
}
.content-card{
    margin-top:30px;    
}
a:hover, a:focus {
    text-decoration: none;
}

/*======== COLORS ===========*/
.card[data-color="blue"] {
    background: #b8d8d8;
}
.card[data-color="blue"] .description {
    color: #506568;
}


/* hena */

.col-md-4.col-sm-6.content-col {
    flex: 0 0 calc(33.333% - 20px); 
    max-width: calc(33.333% - 20px); 
    box-sizing: border-box;
}

.card-big-shadow {
    max-width: 600px; 
    margin: 15px auto; 
    position: relative;
    perspective: 1000px; 
}

.card-flip {
    transform-style: preserve-3d;
    transition: transform 0.6s;
    position: relative;
    width: 100%;
  
}

.card-flip.is-flipped {
    transform: rotateY(180deg);
}
.card-front, .card-back {
    backface-visibility: hidden;
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 10px; 
}

.card-front {
    background-color: #b8d8d8; 
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #506568;
}

.card-back {
    background-color: #f4f4f4; 
    transform: rotateY(180deg);
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #333;
}

h6.category {
    font-size: 14px;
    text-transform: uppercase;
    margin-bottom: 5px;
    color: #7a9e9f;
}

p.description, .card-back p {
    font-size: 16px;
    margin: 0;
    color: inherit;
}

.row {
    

    flex-grow: 1; 
    display: flex; 
    flex-wrap: wrap; 
    gap: 15px; 
}
#saveFormQnA, form[method="POST"] {
    display: inline-block; 
    margin: 10px 10px 0; 
    width: 100%; 
    text-align: center;
}

button {
    width: 40%; 
    max-width: 200px; 
    padding: 8px 16px; 
    font-size: 14px; 
    margin: 0 5px; 
    border: none;
    border-radius: 5px;
    background-color: black;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.buttons-container {
    display: flex;
    justify-content: center; 
    gap: 0px; 
    margin-top: 20px;
}

@media (max-width: 768px) {
    .card-big-shadow {
        max-width: 100%; 
    }
}

    </style>
</head>
<body>
<?php include '../includes/sidebar.php'; ?>


<div class="container bootstrap snippets bootdeys">
    <div class="row">
        <?php 
        
        foreach ($flashcards as $card):
        ?>
            <div class="col-md-4 col-sm-6 content-col">
                <div class="card-big-shadow">
                    <div class="card-flip">
                        <!-- Front -->
                        <div class="card card-just-text" data-background="color" data-color="blue" data-radius="none">
                            <div class="content">
                                <h6 class="category"><?php echo htmlspecialchars($card['name']); ?></h6>
                                <p class="description">
                                    <?php echo htmlspecialchars($card['question']); ?>
                                </p>
                            </div>
                        </div>
                        <!-- Back -->
                        <div class="card card-back">
                            <h6 class="category">Answer</h6>
                            <p>
                                <?php echo htmlspecialchars($card['answer']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="buttons-container">
        <!-- <form id="saveFormQnA">
            <button type="submit" name="save" id="saveQnAButton" data-summary="<?= htmlspecialchars($qa) ?>">
                Save Q&A
            </button>
        </form>
         -->

    <!-- Feedback Message -->

    <!-- Save button with AJAX submit -->
    
    <form method="POST" id="saveQAform" action="">
  <input type="hidden" name="name" value="Habibaazzz Q&A">
  <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
  <input type="hidden" name="folder_id" value="<?= htmlspecialchars($folder_id) ?>">
  <input type="hidden" name="content" value='<?= htmlspecialchars($qa) ?>'>

  <input type="hidden" name="file_type" value="3"> <!-- Assuming file_type 3 represents Q&A -->
  <button type="submit" name="saveQA" id="saveQA">Save Q&A</button>
</form>

    


        <form method="POST">
            <button type="submit" name="regenerate_qa">
                Regenerate Q&A
            </button>
        </form>
    </div>

</div>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script type="text/javascript">
	
</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const cardContainers = document.querySelectorAll(".card-big-shadow");

    cardContainers.forEach(container => {
        container.addEventListener("click", function () {
            const cardFlip = this.querySelector(".card-flip");
            cardFlip.classList.toggle("is-flipped");
        });
    });
});




</script>
<script src="../../public/assets/js/sidebar.js"></script>
<script src="../../public/assets/js/flashcards.js"></script>

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



