<?php
use App\Models\User;
use App\Controllers\UserController;

require_once __DIR__ . '/../Controllers/UserController.php';

$userController = new UserController();
$UserObject = User::getInstance();

// Check if action is set
if (isset($_POST['action'])) {
    $action = $_POST['action']; 

    if ($action == 'logout') { 
        $result = $userController->logout();
        if ($result) {
            echo json_encode(["status" => "success", "message" => "Logged out successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Logout failed"]);
        }
    } elseif ($action == 'deactivate') {
        $result = $userController->deleteUser($UserObject->id);
        if ($result) {
            echo json_encode(["status" => "success", "message" => "User deactivated successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "User deactivation failed"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid action"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "No action specified"]);
}
?>
