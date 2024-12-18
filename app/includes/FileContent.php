<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1); 
$con = new mysqli("localhost", "root", "", "smartnotes_db");

// include '../includes/config.php';
//include '../includes/session.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '../Models/file_class.php';
//require_once __DIR__ . '/../Models/Page.php';



require_once __DIR__ . '/../Controllers/FileController.php';

@session_start();

$fileController = new FileController();
$user_id = $_SESSION['UserID'] ?? null;

// Fetch file content
$file_id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$content = $fileController->getFileContent($file_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newContent = $_POST['content'] ?? '';
    $folder_id = intval($_POST['folder_id']);
    $file_type = intval($_POST['file_type']);
    $file_id = intval($_POST['file_id'] ?? 0);

    if ($file_id) {
        $fileController->saveFileContent($file_id, $newContent);
        header("Location: ../Views/Note.php?id=$file_id");

        exit();
    } else {
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








