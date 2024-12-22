<?php
require_once '../config/Database.php';
require_once '../includes/FileContent.php';

use App\Models\User;
use App\Controllers\FileController;
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Controllers/FileController.php';

// Check if user is logged in
if (isset($UserObject) && $UserObject instanceof User) {
    $user_id = $UserObject->id;
} else {
    header("Location: /smartnotes/Public/login.php");
    exit();
}

// Check if the POST request contains the required fields
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
    $file_id = $_POST['file_id'];
    $content = $_POST['content'];

    // Initialize the controller
    $fileController = new FileController();

    // Call the saveFileContent method
    $result = $fileController->saveFileContent($file_id, $content);

    // Return a JSON response
    if ($result) {
        echo json_encode(['message' => 'Content saved successfully']);
    } else {
        echo json_encode(['message' => 'Failed to save content']);
    }
} else {
    echo json_encode(['message' => 'Invalid request']);
}
?>
