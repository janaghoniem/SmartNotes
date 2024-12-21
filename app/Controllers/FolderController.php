<?php
namespace App\Controllers;
use App\Models\file;

require_once __DIR__ . '/../includes/sidebar.php';
class FolderController {
    public static function create($con, $post, $get, $session) {
      $name = $post['name'];
      $type = $post['dropdown'];
      $parent_folder_id = $get['folder_id'] ?? 1;
      $user_id = $session['UserID'];
  
      if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
      }
  
      if ($type == "option2") { // Creating a folder
        require_once __DIR__ . '/../Models/folder_class.php';

        $new_folder_id = folder::create($name, $user_id, $parent_folder_id);
        if ($new_folder_id) {

          header("Location:../Views/folder_contents.php?folder_id=$new_folder_id");
          exit();
        } else {
          echo "ERROR!";
        }
      } elseif ($type == "option3") { // Creating a file
        $content = "  "; 
        $file_type = 4; 
        require_once __DIR__ . '/../Models/file_class.php';
        $new_file_id = file::create($name, $user_id, $parent_folder_id, $content, $file_type);
        if ($new_file_id) {
          header("Location:../Views/speech.php?id=$new_file_id");
          exit();
        } else {
          echo "ERROR!";
        }
      } else {
        echo "<script>alert('Invalid selection! Please choose either a \"Folder\" or a \"File\".');</script>";
      }
    }

    public static function moveToTrash($post) {
        // Start output buffering
        ob_start();
    
        // Extract and validate POST data
        $item_id = intval($post['item_id']);
        $item_type = $post['item_type'];
    
        // Debugging outputs (optional, remove in production)
        error_log("Move to trash request: ID = $item_id, Type = $item_type");
    
        if ($item_id && in_array($item_type, ['folder', 'file'])) {
          // Perform action based on type
          $result = false;
          if ($item_type === 'folder') {
            require_once __DIR__ . '/../Models/folder_class.php';

            $result = folder::moveToTrash($item_id);
          } elseif ($item_type === 'file') {
            require_once __DIR__ . '/../Models/file_class.php';
            $result = file::moveToTrash($item_id);
          }
    
          // Check result and handle response
          if ($result) {
            ob_end_clean(); // Clear buffer
            header("Location: ../Views/trash.php");
            exit();
          } else {
            echo "<script>alert('Error moving $item_type to trash.');</script>";
          }
        } else {
          echo "<script>alert('Invalid item ID or type.');</script>";
        }
    
        // Flush the buffer in case of errors
        ob_end_flush();
      }
  }
  
  
  