<?php
include_once '../includes/trash_class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    $action = $_POST['action'];
    $folderId = !empty($_POST['id']) ? intval($_POST['id']) : 0;

    error_log("Action: $action, Folder ID: $folderId"); // Log for debugging

    if ($folderId === 0) {
        error_log("Restore action failed: No folder ID provided.");
        echo "<script>alert('Error: No folder ID provided.');</script>";
    } elseif ($action === 'restore_from_trash') {
        $trashItem = new trash($folderId);
        if ($trashItem->restore()) {
            echo "<script>alert('Folder successfully restored.'); window.location.href = '../pages/trash.php';</script>";
        } else {
            echo "<script>alert('Error restoring folder.');</script>";
        }
    }
}
?>
