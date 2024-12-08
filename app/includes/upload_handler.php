<?php
include '../includes/config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$response = ["status" => false, "message" => "No file uploaded."];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileType = $_FILES['file']['type'];
        $rename = $_POST['rename'] ?? $fileName; // Get the rename value or use original file name
        $extractedText = $_POST['content'] ?? ''; // Extracted text sent from the frontend

        // Save the file details and content to the database
        $userId = 47; // Replace with actual user ID
        $folderId = 1; // Default folder
        $fileType = 1; // Fixed file type for PDFs

        $stmt = $conn->prepare("INSERT INTO files (name, user_id, folder_id, content, file_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siisi", $rename, $userId, $folderId, $extractedText, $fileType);

        if ($stmt->execute()) {
            $response = ["status" => true, "message" => "File uploaded and content saved successfully!"];
        } else {
            $response = ["status" => false, "message" => "Failed to save file data: " . $stmt->error];
        }
        $stmt->close();
    } else {
        $response = ["status" => false, "message" => "File upload error: " . $_FILES['file']['error']];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
