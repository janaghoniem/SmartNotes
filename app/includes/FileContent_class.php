<?php
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1); // Display errors

include '../includes/config.php';
//include 'session.php';
include_once 'file_class.php';

$user_id = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect POST data
    $content = mysqli_real_escape_string($conn, $_POST['content']); // Sanitizing the input
    $user_id = intval($_POST['user_id']); // User ID passed from JS (make sure it's valid)
    $folder_id = isset($_POST['folder_id']) ? intval($_POST['folder_id']) : null; // Folder ID passed from JS
    $file_type = isset($_POST['file_type']) ? intval($_POST['file_type']) : null; // File type passed from JS
    $file_id = isset($_POST['file_id']) ? intval($_POST['file_id']) : null; // File ID passed from JS

    // Debugging statements
    error_log("Content: $content");
    error_log("User ID: $user_id");
    error_log("Folder ID: $folder_id");
    error_log("File Type: $file_type");
    error_log("File ID: $file_id");

    if ($file_id) {
        // Update the existing file
        $sql = "UPDATE files SET content='$content' WHERE id=$file_id";
        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        // Generate a file name or receive it from the front-end (you can customize this logic as needed)
        $name = "speechToText" . $folder_id;

        // Now call the create function from file_class.php
        $result = file::create($name, $user_id, $folder_id, $content, $file_type);

        if ($result) {
            header("Location: ../pages/Note.php?id=" . $result);
            exit();
        } else {
            echo "Failed to create file";
        }
    }
}






// Fetch the content for the file with id 1
$file_id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$sql = "SELECT content FROM files WHERE id=$file_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $content = $row["content"];
    }
} else {
    $content = "No content found.";
}

// Disable strict mode temporarily
$conn->query("SET sql_mode = ''");

$conn->close();
?>
