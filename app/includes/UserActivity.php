<?php
$con = new mysqli("localhost", "root", "", "smartnotes_db");
// require_once "../includes/UserActivity.php";

class UserActivity
{
    public $id;
    public $user_id;
    public $login_time;
    public $logout_time;
    public $duration;

    // public function __construct($session_id) {
    //     $sql = "SELECT * FROM user_session_duration WHERE id = $session_id";
    //     $result = mysqli_query($GLOBALS['con'], $sql);
    //     if ($row = mysqli_fetch_array($result)) {
    //         $this->id = $row["id"];
    //         $this->user_id = $row["user_id"];
    //         $this->login_time = $row["login_time"];
    //         $this->logout_time = $row["logout_time"];
    //         $this->duration = $row["duration"];
    //     }
    // }

    static function startSession($user_id)
    {
        // Check if there is an active session for the user
        $sql_check = "
            SELECT COUNT(*) AS active_sessions 
            FROM user_session_duration 
            WHERE user_id = $user_id AND logout_time IS NULL";
        $result_check = mysqli_query($GLOBALS['con'], $sql_check);
        $row = mysqli_fetch_assoc($result_check);

        if ($row['active_sessions'] > 0) {
            // Active session exists; no need to insert a new one
            return false;
        }

        // Start a new session
        $sql = "INSERT INTO user_session_duration (user_id) VALUES ($user_id)";
        $result = mysqli_query($GLOBALS['con'], $sql);

        if ($result) {
            self::updateActivityScore($user_id); // Update activity score
        }
        return $result;
    }

    static function endSession($user_id)
    {
        $sql = "
            UPDATE user_session_duration
            SET logout_time = CURRENT_TIMESTAMP,
                duration_minutes = TIMESTAMPDIFF(MINUTE, login_time, CURRENT_TIMESTAMP)
            WHERE user_id = $user_id AND logout_time IS NULL
            ORDER BY login_time DESC
            LIMIT 1"; // Ensure only the most recent session is updated
        $result = mysqli_query($GLOBALS['con'], $sql);

        if ($result) {
            self::updateActivityScore($user_id); // Update activity score
        }

        return $result;
    }


    static function getTotalLoginCount($user_id)
    {
        $sql = "SELECT COUNT(*) AS login_count FROM user_session_duration WHERE user_id = $user_id";
        $result = mysqli_query($GLOBALS['con'], $sql);
        if ($row = mysqli_fetch_array($result)) {
            return $row["login_count"];
        }
        return 0;
    }

    static function getNotesCount($user_id)
    {
        $sql = "SELECT COUNT(*) AS notes_count FROM files WHERE user_id = $user_id";
        $result = mysqli_query($GLOBALS['con'], $sql);
        if ($row = mysqli_fetch_array($result)) {
            return $row["notes_count"];
        }
        return 0;
    }

    static public function getMostUsedFeatureType($user_id)
    {
        $sql = "
            SELECT 
                files.file_type, 
                file_types.name, 
                COUNT(*) AS count
            FROM 
                files
            LEFT JOIN 
                file_types
            ON 
                files.file_type = file_types.id
            WHERE 
                files.user_id = $user_id
            GROUP BY 
                files.file_type
            ORDER BY 
                count DESC
            LIMIT 1
        ";
        $result = mysqli_query($GLOBALS['con'], $sql);

        if ($result && $row = mysqli_fetch_array($result)) {
            return $row["name"];
        }

        return null; // Return null if no feature type is found
    }


    static function getSessionDuration($user_id)
    {
        $sql = "
            SELECT SUM(duration_minutes) AS total_duration
            FROM user_session_duration
            WHERE user_id = $user_id";
        $result = mysqli_query($GLOBALS['con'], $sql);
        if ($row = mysqli_fetch_array($result)) {
            return $row["total_duration"];
        }
        return 0;
    }

    public static function calculateActivityScore($user_id)
    {
        $login_count = self::getTotalLoginCount($user_id);
        $notes_count = self::getNotesCount($user_id);
        $total_duration = self::getSessionDuration($user_id); // In minutes

        // Define weights for each user activity
        $weight1 = 3; // Weight for login count
        $weight2 = 2; // Weight for notes count
        $weight3 = 3; // Weight for session duration (adjust as needed)

        // Calculate activity score
        $activity_score = ($login_count * $weight1) + ($notes_count * $weight2) + ($total_duration * $weight3);

        return $activity_score;
    }


    // Function to update the activity score in the database
    public static function updateActivityScore($user_id)
    {
        global $con; 

        // Calculate the activity score
        $activity_score = self::calculateActivityScore($user_id);

        // Check if the user already exists in the table
        $check_query = "SELECT COUNT(*) AS count FROM user_activity WHERE user_id = $user_id";
        $result = mysqli_query($con, $check_query);
        $row = mysqli_fetch_assoc($result);

        if ($row['count'] > 0) {
            // If the user exists, update the activity score
            $sql = "UPDATE user_activity SET activity_score = $activity_score WHERE user_id = $user_id";
        } else {
            // If the user doesn't exist, insert a new row
            $sql = "INSERT INTO user_activity (user_id, activity_score) VALUES ($user_id, $activity_score)";
        }

        return mysqli_query($con, $sql);
    }



    public static function getTopActiveUsers()
    {
        global $con;
        // Fetch top 5 active users based on activity score
        $sql = "SELECT u.id, u.first_name, u.last_name, u.country, 
               MAX(CASE WHEN usa.question_id = 1 THEN sqo.answer_option END) AS usage_option_id,
               MAX(CASE WHEN usa.question_id = 2 THEN sqo.answer_option END) AS age_group_option_id,
               ua.activity_score
        FROM users u
        LEFT JOIN user_activity ua ON u.id = ua.user_id
        LEFT JOIN user_survey_answers usa ON u.id = usa.user_id
        LEFT JOIN survey_questions_options sqo ON usa.option_id = sqo.id
        WHERE usa.question_id IN (1, 2)
        GROUP BY u.id
        ORDER BY ua.activity_score DESC
        LIMIT 5";


        $result = mysqli_query($con, $sql);

        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }

        return $users;
    }
}

?>