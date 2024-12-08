<?php

$con = new mysqli("localhost", "root", "", "smartnotes_db");

class ChartsData
{
    static public function getDailyUsageHours()
    {
        $query = "
     SELECT 
         DAYNAME(login_time) AS day, 
         SUM(duration_minutes) AS total_minutes
     FROM 
         user_session_duration
     WHERE 
         login_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
     GROUP BY 
         DAYNAME(login_time)
     ORDER BY 
         FIELD(DAYNAME(login_time), 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')";

        $result = mysqli_query($GLOBALS['con'], $query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            // Debugging: Print raw data
            // print_r($row);
            // Convert minutes to hours
            $data[$row['day']] = round($row['total_minutes'] / 60, 1);  // Rounding to 1 decimal place
        }

        // Ensure all days are included in the data
        $daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        $finalData = [];
        foreach ($daysOfWeek as $day) {
            // If no data for the day, set it to 0 hours
            $finalData[] = isset($data[$day]) ? $data[$day] : 0;
        }

        return $finalData;
    }

    static public function getTopFeatures()
    {
        // SQL query to get the top 3 most used file types
        $query = "
            SELECT 
                ft.name AS file_type_name,
                COUNT(f.file_type) AS usage_count
            FROM 
                files f
            JOIN 
                file_types ft ON f.file_type = ft.id
            GROUP BY 
                f.file_type
            ORDER BY 
                usage_count DESC
            LIMIT 3;
        ";

        // Execute the query
        $result = mysqli_query($GLOBALS['con'], $query);

        // Initialize an array to hold the data
        $topFeatures = [];

        // Fetch the result and store it in the array
        while ($row = mysqli_fetch_assoc($result)) {
            $topFeatures[] = [
                'name' => $row['file_type_name'],
                'count' => $row['usage_count']
            ];
        }

        // Return the top features as a JSON-encoded array for use in JavaScript
        return $topFeatures;
    }

    static public function getPeakUsageTimes() {
        // SQL query to get the number of active users per hour for today
        $query = "
            SELECT 
                HOUR(login_time) AS hour,
                COUNT(DISTINCT user_id) AS active_users
            FROM 
                user_session_duration
            WHERE 
                login_time >= CURDATE()  -- Today's data
            GROUP BY 
                HOUR(login_time)
            ORDER BY 
                hour;
        ";
    
        // Execute the query
        $result = mysqli_query($GLOBALS['con'], $query);
    
        // Initialize arrays to hold the data
        $hours = [];
        $activeUsers = [];
    
        // Fetch the result and populate the arrays
        while ($row = mysqli_fetch_assoc($result)) {
            $hours[] = $row['hour'];
            $activeUsers[] = $row['active_users'];
        }
    
        // Ensure we have data for all 24 hours of the day (even if some hours have no users)
        for ($i = 0; $i < 24; $i++) {
            if (!in_array($i, $hours)) {
                $hours[] = $i;
                $activeUsers[] = 0;  // No users active in this hour
            }
        }
    
        // Return the data as a JSON-encoded array for use in JavaScript
        return [
            'hours' => $hours,
            'activeUsers' => $activeUsers
        ];
    }

    static public function getMonthlyActiveAccounts() {
        // SQL query to count active accounts by month
        $query = "
            SELECT 
                MONTH(login_time) AS month, 
                COUNT(DISTINCT user_id) AS active_accounts
            FROM 
                user_session_duration
            WHERE 
                YEAR(login_time) = YEAR(CURDATE())
            GROUP BY 
                MONTH(login_time)
            ORDER BY 
                MONTH(login_time);
        ";
    
        // Execute the query
        $result = mysqli_query($GLOBALS['con'], $query);
    
        // Initialize arrays
        $monthlyData = array_fill(0, 12, 0); // Default 0 for all 12 months
    
        // Populate the monthly data
        while ($row = mysqli_fetch_assoc($result)) {
            $monthIndex = (int)$row['month'] - 1; // Convert month to zero-based index
            $monthlyData[$monthIndex] = (int)$row['active_accounts'];
        }
    
        // Return JSON-encoded data for JavaScript
        return $monthlyData;
    }
    
    


}
?>