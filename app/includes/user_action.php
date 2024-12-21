<?php
require_once __DIR__ . '/../Controllers/UserController.php';
require_once __DIR__ . '/../Models/User.php';
$userController = new UserController();
$UserObject = User::getInstance();

// Check if action is set
if (isset($_POST['action'])) {
  $action = $_POST['action'];

  if ($action == 'logout') { 
      $userController->logout();
  } elseif ($action == 'deactivate') {
      $userController->deleteUser($UserObject->id);
  } else {
      echo "Invalid action";
  }
} else {
  echo "No action specified";
}

?>