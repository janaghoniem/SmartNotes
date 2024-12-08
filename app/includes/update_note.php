<?php
include_once '../includes/file_class.php'; 

if (isset($_POST['id']) && isset($_POST['name'])) {
    $noteId = intval($_POST['id']);
    $newName = trim($_POST['name']);

    if (empty($newName)) {
        echo 'error';
        exit;
    }

    $result = file::update($noteId, $newName);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
