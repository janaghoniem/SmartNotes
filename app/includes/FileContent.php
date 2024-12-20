<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1); 
$con = new mysqli("localhost", "root", "", "smartnotes_db");

// include '../includes/config.php';
require_once __DIR__ . '/session.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . '../Models/file_class.php';
//require_once __DIR__ . '/../Models/Page.php';



require_once __DIR__ . '/../Controllers/FileController.php';

global $UserObject;

$fileController = new FileController();
$user_id = $UserObject->id ?? null; // Retrieve the ID from the global UserObject

// Fetch file content
$file_id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$content = $fileController->getFileContent($file_id);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newContent = $_POST['content'] ?? '';
    $folder_id = intval($_POST['folder_id']);
    $file_type = intval($_POST['file_type']);
    $file_id = intval($_POST['file_id'] ?? 0);

    // Check if a file ID is provided
    if ($file_id > 0) {
        // Check if the file exists in the database
        $existingContent = $fileController->getFileContent($file_id);
        if ($existingContent !== null) {
            // Update the existing file content
            $fileController->saveFileContent($file_id, $newContent);
            header("Location: ../Views/Note.php?id=$file_id");
            exit();
        } else {
            echo "File with ID $file_id does not exist.";
        }
    } else {
        // No file ID provided, create a new file
        $name = "speechToText" . $folder_id;
        $newFileId = $fileController->createFile($name, $user_id, $folder_id, $newContent, $file_type);
        if ($newFileId) {
            header("Location: ../Views/Note.php?id=$newFileId");
            exit();
        } else {
            echo "Failed to create file.";
        }
    }
}
?>









