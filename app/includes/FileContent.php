<?php
use App\Controllers\FileController;
error_reporting(E_ALL);
ini_set('display_errors', 1);

$con = new mysqli("localhost", "root", "", "smartnotes_db");

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../Models/file_class.php';
require_once __DIR__ . '/../Controllers/FileController.php';

global $UserObject;

$fileController = new FileController();
$user_id = $UserObject->id ?? null; // Retrieve the ID from the global UserObject

// Fetch file content
$file_id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$content = $fileController->getFileContent($file_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract POST data with defaults
    $newContent = $_POST['content'] ?? '';
    $folder_id = isset($_POST['folder_id']) ? intval($_POST['folder_id']) : 0;
    $file_type = isset($_POST['file_type']) ? intval($_POST['file_type']) : 0;
    $file_id = isset($_POST['file_id']) ? intval($_POST['file_id']) : 0;

    if ($file_id > 0) {
        // Check if file exists before updating
        $existingContent = $fileController->getFileContent($file_id);
        if ($existingContent !== null) {
            try {
                $fileController->saveFileContent($file_id, $newContent);
                header("Location: ../Views/Note.php?id=$file_id");
                exit();
            } catch (Exception $e) {
                echo "Error updating file: " . $e->getMessage();
            }
        } else {
            echo "File with ID $file_id does not exist.";
        }
    } else {
        // Create a new file when no file ID is provided
        $name = "speech" . $folder_id;
        try {
            $newFileId = $fileController->createFile($name, $user_id, $folder_id, $newContent, $file_type);
            if ($newFileId) {
                header("Location: ../Views/Note.php?id=$newFileId");
                exit();
            } else {
                echo "Failed to create file.";
            }
        } catch (Exception $e) {
            echo "Error creating file: " . $e->getMessage();
        }
    }
}
?>
