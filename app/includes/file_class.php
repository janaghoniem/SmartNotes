<?php
$con = new mysqli("localhost", "root", "", "smartnotes_db");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

class FileType
{
    public $id;
    public $name;

    public function __construct($id)
    {
        global $con;
        $this->id = $id;
        if ($id != 0) {
            $sql = "SELECT * FROM file_types WHERE id = $id";
            $result = mysqli_query($con, $sql);
            if ($row = mysqli_fetch_array($result)) {
                $this->name = $row['name'];
            } else {
                echo "Error: File type not found.<br>";
            }
        }
    }
}
class file
{
    public $id;
    public $name;
    public $user_id;
    public $folder_id;
    public $content;
    public $created_at;
    public $file_type;

    public function __construct($id)
    {
        global $con;
        $this->id = $id;
        if ($id != 0) {
            $sql = "SELECT * FROM files WHERE id = $id";
            $result = mysqli_query($con, $sql);
            if ($row = mysqli_fetch_array($result)) {
                $this->name = $row['name'];
                $this->user_id = $row['user_id'];
                $this->folder_id = $row['folder_id'];
                $this->content = $row['content'];
                $this->created_at = $row['created_at'];
                $this->file_type = $row['file_type'];
            } else {
                echo "Error: File not found.<br>";
            }
        }
    }

    // Create a new file
    public static function create($name, $user_id, $folder_id, $content, $file_type)
    {
        global $con;
        $created_at = date('Y-m-d H:i:s');
        $sql = "INSERT INTO files (name, user_id, folder_id, content, created_at, file_type) 
                VALUES ('$name', $user_id, $folder_id, '$content', '$created_at', $file_type)";
        if (mysqli_query($con, $sql)) {
            $new_id = mysqli_insert_id($con);
            return $new_id;
        } else {
            echo "Error: " . mysqli_error($con) . "<br>";
            return false;
        }
    }

    // Read all files for a user
    public static function readAll($user_id, $folder_id = null)
    {
        global $con;

        // Start building the SQL query
        $sql = "SELECT * FROM files WHERE user_id = $user_id";

        // If a folder ID is provided, filter by folder
        if ($folder_id) {
            $sql .= " AND folder_id = $folder_id";
        }

        // Execute the query
        $result = mysqli_query($con, $sql);

        // Check for results
        if ($result) {
            $files = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $files[] = $row;
            }
            return $files;
        } else {
            echo "Error: " . mysqli_error($con);
            return false;
        }
    }


    // Update a file's content
    public static function update($id, $newName)
    {
        global $con;

        // Clean up newName to ensure it is correctly formatted
        $newName = trim($newName);

        // Check if the parameters are correct
        echo "New Name: " . htmlspecialchars($newName, ENT_QUOTES, 'UTF-8') . "<br>";
        echo "ID: " . intval($id) . "<br>";

        // SQL query to update name
        $sql = "UPDATE files SET name = ? WHERE id = ?";
        echo "SQL: " . $sql . "<br>";  // Debug the SQL query

        // Prepare the statement
        if ($stmt = mysqli_prepare($con, $sql)) {
            echo "Prepared Statement Successful<br>";

            // Bind the parameters
            if (!mysqli_stmt_bind_param($stmt, 'si', $newName, $id)) {
                echo "Error binding parameters: " . mysqli_error($con) . "<br>";
                return false;
            }

            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                echo "Update Successful<br>";
                return true;
            } else {
                echo "Error Executing Query: " . mysqli_stmt_error($stmt) . "<br>";
                return false;
            }
        } else {
            echo "Error Preparing Query: " . mysqli_error($con) . "<br>";
            return false;
        }
    }


    public static function delete($id)
    {
        global $con;
        $sql = "DELETE FROM files WHERE id = $id";
        if (mysqli_query($con, $sql)) {
            return true;
        } else {
            echo "Error: " . mysqli_error($con);
            return false;
        }
    }
    public static function moveToTrash($id) {
        global $con;
        $user_id = $_SESSION['UserID']; // Ensure you have the user ID from session
    
        // Get the file details
        $stmt = $con->prepare("SELECT * FROM files WHERE ID = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($file = $result->fetch_assoc()) {
            echo "File found: " . json_encode($file) . "<br>";
    
            // Assign file details correctly
            $file_name = $file['name'];
            $parent_folder_id = $file['folder_id'];
            $file_content = $file['content'] ?? ''; 
    
            mysqli_begin_transaction($con);
    
            try {
                // Move file to trash, including file content
                $trash_sql = "INSERT INTO trash (file_id, folder_id, name, user_id, file_content, deleted_at) VALUES (?, ?, ?, ?, ?, NOW())";
                $stmt_trash = $con->prepare($trash_sql);
                $stmt_trash->bind_param("iisis", $id, $parent_folder_id, $file_name, $user_id, $file_content);
    
                if (!$stmt_trash->execute()) {
                    throw new Exception("Error moving file to trash: " . $stmt_trash->error);
                }
                echo "Insert into trash successful.<br>";
    
                // Verify insertion into trash
                $last_insert_id = $con->insert_id;
                if ($last_insert_id <= 0) {
                    throw new Exception("Failed to insert into trash.");
                }
    
                // Delete file from files table
                $delete_sql = "DELETE FROM files WHERE ID = ? AND user_id = ?";
                $stmt_delete = $con->prepare($delete_sql);
                $stmt_delete->bind_param("ii", $id, $user_id);
    
                if (!$stmt_delete->execute()) {
                    // Log the error in more detail
                    error_log("Error deleting file: " . $stmt_delete->error . " | ID: $id | User ID: $user_id");
                    // Detailed exception message
                    throw new Exception("Error deleting file: " . $stmt_delete->error . " | ID: $id | User ID: $user_id");
                }
                error_log("DELETE SQL: $delete_sql | ID: $id | User ID: $user_id");
error_log("INSERT SQL: $trash_sql | ID: $id | Name: $file_name");

                echo "Delete from files successful.<br>";
                // Commit transaction
                mysqli_commit($con);
                echo "File moved to trash successfully.<br>";
                return true;
            } catch (Exception $e) {
                mysqli_rollback($con);
                echo "Error during transaction: " . $e->getMessage() . "<br>";
                error_log("Error during transaction: " . $e->getMessage());
                return false;
            }
        } else {
            echo "Error: File not found.<br>";
            return false;
        }
    }
    public static function readFiltered($user_id, $folder_id, $startDate, $endDate) {
        global $con; // Assuming $con is the global mysqli connection
    
        // Prepare the query
        $query = "SELECT * FROM files 
                  WHERE user_id = ? 
                  AND folder_id = ? 
                  AND created_at BETWEEN ? AND ?
                  ORDER BY created_at DESC";
    
        // Use a prepared statement to avoid SQL injection
        $stmt = $con->prepare($query);
        if ($stmt === false) {
            die("Error preparing statement: " . $con->error);
        }
    
        // Bind parameters
        $stmt->bind_param("iiss", $user_id, $folder_id, $startDate, $endDate);
    
        // Execute the statement
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
    
        // Get the result
        $result = $stmt->get_result();
        $files = [];
    
        // Fetch all rows as an associative array
        while ($row = $result->fetch_assoc()) {
            $files[] = $row;
        }
    
        // Close the statement
        $stmt->close();
    
        return $files;
    }
    
    
}