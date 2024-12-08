<?php
include_once 'survey_class.php';

if (!isset($_SESSION['UserID'])) {
    die('User not logged in.');
}

$seenSurvey = Survey::seenSurvey($_SESSION['UserID']);
$questions = Survey::getQuestions();

if (isset($_POST['submitSurvey'])) {
    $user_id = $_SESSION['UserID'];

    foreach ($questions as $question_id => $question) {
        if (isset($_POST["question-$question_id"])) {
            $option_id = $_POST["question-$question_id"];
            $valid_option = false;
            foreach ($question['options'] as $option) {
                if ($option['option_id'] == $option_id) {
                    $valid_option = true;
                    break;
                }
            }

            if ($valid_option) {
                Survey::submitSurveyAnswer($user_id, $question_id, $option_id);
            } else {
                die('Invalid survey submission.');
            }
        }
    }

    exit;
}

// Only display the survey if the user hasn't seen it yet
if (!$seenSurvey) {
    $colors = ['#8eb0f0', '#f2e982', '#c6408a'];

    echo "<div id='survey-overlay' class='survey-overlay'>
    <div class='survey-popup'>
    <div class='progress-indicator' id='progress-indicator'>";

    // for ($i = 0; $i < count($questions) + 1; $i++) {
    //     echo "<span class='dot" . ($i == 0 ? " dotactive " : "") . "'></span>";
    // }

    echo "</div>
    <form method='POST' action='' id='form'>
    <div class='survey-page img-div' id='page-0'>
      <div class='right-div'>
          <div class='column-text right'>
              <p>Welcome to SmartNotes</p>
              <h6>Empowering Your Productivity, One Note at a Time.</h6>
              <input class='btn-survey-primary' id='continue-btn' value='Continue' type='button'>
          </div>
      </div>
    </div>";

    foreach ($questions as $question_id => $question) {
        echo "<div class='survey-page hidden' id='page-$question_id'>
                <div class='row'>
                    <div class='column-text left'>
                        <p class='title'>Customize Your SmartNotes Experience</p>
                        <h6 class='question'>{$question['text']}</h6>
                        <p class='message'>Pick the one that best describes your usage. <br> You can change your mind later.</p>
                    </div>
                    <div class='right-div choices'>";

        $i = 0;
        // Render options
        foreach ($question['options'] as $option) {
            echo "<div class='choice'>
                    <div class='choice-radio-button'>
                        <i class='{$option['option_icon']}' style='color: $colors[$i]; font-size: 35px'></i>
                        <span>{$option['answer_option']}</span>
                    </div>
                    <div class='radio-btn'>
                        <input type='radio' name='question-$question_id' value='{$option['option_id']}'>
                    </div>
                  </div>";
            $i++;
        }
        echo "</div>
        </div>
      </div>";
    }

    echo "<input id='finish-button' class='hidden btn-survey-primary' value='Submit' type='submit' name='submitSurvey'>
    </form>
  </div>
</div>";
}

?>