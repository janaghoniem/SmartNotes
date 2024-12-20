<?php
require_once __DIR__ . '/../Controllers/FileController.php'; 
require_once __DIR__ . '/session.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . '../Models/file_class.php';
//require_once __DIR__ . '/../Models/Page.php';


global $UserObject;

$fileController = new FileController();
$user_id = $UserObject->id ?? null;

ini_set('display_errors', 1);
error_reporting(E_ALL);

$response = ["status" => false, "message" => "No file uploaded."];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileType = $_FILES['file']['type'];
        $rename = $_POST['rename'] ?? $fileName; // Get the rename value or use the original file name
        $extractedText = $_POST['content'] ?? ''; // Extracted text sent from the frontend

        // Prepare to use FileController to save the file details
        $fileController = new FileController();

        // User and folder IDs (you can adjust these as needed)
        // $userId = 47; // Replace with actual user ID
        $folderId = 1; // Default folder
        $fileType = 1; // Fixed file type for PDFs (or change according to your needs)

        // Call the createFile method from FileController to save the file content to the database
        $fileCreated = $fileController->createFile($rename, $user_id, $folderId, $extractedText, $fileType);

        if ($fileCreated) {
            $response = ["status" => true, "message" => "File uploaded and content saved successfully!"];
        } else {
            $response = ["status" => false, "message" => "Failed to save file data."];
        }
    } else {
        $response = ["status" => false, "message" => "File upload error: " . $_FILES['file']['error']];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
