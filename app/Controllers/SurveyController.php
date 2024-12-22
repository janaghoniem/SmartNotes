<?php
namespace App\Controllers;
use App\Models\Survey;
require_once __DIR__ . '/../Models/Survey.php';
require_once __DIR__ . '/../Config/Database.php';

class SurveyController
{
    public function getSurveyQuestions()
    {
        // Fetch the questions and their options using the Survey model
        $questions = Survey::getQuestions();
        return $questions;
    }

    public function getUserAnswers($user_id)
    {
        // Fetch the user's answers for the survey
        $answers = Survey::getUserAnswers($user_id);
        return $answers;
    }

    public function checkIfUserCompletedSurvey($user_id)
    {
        // Check if the user has already completed the survey
        return Survey::seenSurvey($user_id);
    }

    public function submitAnswer($user_id, $question_id, $option_id)
    {
        // Submit the answer for the user
        if (Survey::submitSurveyAnswer($user_id, $question_id, $option_id)) {
            return "Answer submitted successfully.";
        }
        return "Failed to submit answer.";
    }

    public function updateAnswer($user_id, $question_id, $selected_option)
    {
        // Update an existing answer for the user
        return Survey::updateUserAnswer($user_id, $question_id, $selected_option); 
    }
}
?>
