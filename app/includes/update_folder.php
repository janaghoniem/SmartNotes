<?php
include '../includes/folder_class.php';

if (isset($_POST['id']) && isset($_POST['name'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];

    if (folder::update($id, $name)) {
        echo "success";
    } else {
        echo "Error: Unable to update folder name.";
    }
} else {
    echo "Error: Missing data.";
}
?>
