<?php
$con = new mysqli("localhost", "root", "", "smartnotes_db");

class Survey
{
    // Fetch all questions with their options
    public static function getQuestions()
    {
        $sql = "SELECT q.id as question_id, q.question_text, o.id as option_id, o.answer_option, o.option_icon 
                FROM survey_questions q
                LEFT JOIN survey_questions_options o ON q.id = o.question_id
                ORDER BY q.id, o.id";
        $result = mysqli_query($GLOBALS['con'], $sql);

        $questions = [];
        if ($result && $result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $questions[$row['question_id']]['text'] = $row['question_text'];
                $questions[$row['question_id']]['options'][] = [
                    'option_id' => $row['option_id'],
                    'answer_option' => $row['answer_option'],
                    'option_icon' => $row['option_icon']
                ];
            }
        }
        return $questions;
    }

    public static function getUserAnswers($user_id)
    {
        // SQL query to fetch user's answers for each question
        $sql = "
                SELECT a.question_id, 
                    o.id, 
                    o.answer_option, 
                    o.option_icon 
                FROM user_survey_answers a
                JOIN survey_questions_options o ON a.option_id = o.id
                WHERE a.user_id = $user_id";

        // Execute the query
        $result = mysqli_query($GLOBALS['con'], $sql);

        // Initialize the array to store answers
        $answers = [];

        // Check if the query returned any results
        if ($result && $result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                // Store the user's answer for each question
                $answers[$row['question_id']] = [
                    'selected_option' => $row['id'], // Store selected option ID
                    'answer_option' => $row['answer_option'], // Store answer text
                    'option_icon' => $row['option_icon'] // Store option icon
                ];
            }
        }

        return $answers;
    }


    // Check if the user has already completed the survey
    public static function seenSurvey($user_id)
    {
        if (!empty($user_id)) {
            $sql = "SELECT 1 FROM user_survey_answers WHERE user_id = $user_id LIMIT 1";
            $result = mysqli_query($GLOBALS['con'], $sql);
            return $result && $result->num_rows > 0;
        }
        return false;
    }

    // Submit a single survey answer
    public static function submitSurveyAnswer($user_id, $question_id, $option_id)
    {
        if (!empty($user_id) && !empty($question_id) && !empty($option_id)) {
            $sql = "INSERT INTO user_survey_answers (user_id, question_id, option_id) VALUES ('$user_id','$question_id', '$option_id')";
            $result = mysqli_query($GLOBALS['con'], $sql);
            return $result;
        }
        return false;
    }

    public static function updateUserAnswer($userId, $questionId, $selectedOption) {
        // Prepare and execute the update query
        $sql = "UPDATE user_survey_answers SET option_id = $selectedOption WHERE user_id = $userId AND question_id = $questionId";
        $result = mysqli_query($GLOBALS['con'], $sql);

        return $result;
    }
}

?>