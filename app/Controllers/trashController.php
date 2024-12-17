<?php
// TrashController.php


class trashController {
    public static function handleAction($postData) {
        $action = $postData['action'];
        $folderId = !empty($postData['id']) ? intval($postData['id']) : 0;

        if ($folderId === 0) {
            error_log("Action failed: No folder ID provided.");
            echo "<script>alert('Error: No folder ID provided.');</script>";
            return;
        }

        switch ($action) {
            case 'delete_from_trash':
                require_once __DIR__ . '/../includes/trash_class.php';

                $trashItem = new trash($folderId);
                if ($trashItem->delete()) {
                    echo "<script>alert('Folder permanently deleted.'); window.location.href = 'trash.php';</script>";
                } else {
                    echo "<script>alert('Error deleting folder.');</script>";
                }
                break;

            case 'restore_from_trash':
                require_once __DIR__ . '/../includes/trash_class.php';

                $trashItem = new trash($folderId);
                if ($trashItem->restore()) {
                    echo "<script>alert('Folder successfully restored.'); window.location.href = 'trash.php';</script>";
                } else {
                    echo "<script>alert('Error restoring folder.');</script>";
                }
                break;

            default:
                echo "<script>alert('Unknown action.');</script>";
                break;
        }
    }
}
?>
