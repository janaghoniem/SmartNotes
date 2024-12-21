<?php
namespace App\Controllers;
use App\Models\Note;
require_once __DIR__ . '/../Models/Note.php';

class FileController {
    private $fileModel;

    public function __construct() {
        $this->fileModel = new Note();
    }

    public function setFileModel(Note $fileModel) {
        $this->fileModel = $fileModel;
    }
    public function getFileContent($file_id) {
        return $this->fileModel->getContentById($file_id);
    }

    public function saveFileContent($file_id, $content) {
        return $this->fileModel->updateFileContent($file_id, $content);
    }

    public function createFile($name, $user_id, $folder_id, $content, $file_type) {
        return $this->fileModel->createFile($name, $user_id, $folder_id, $content, $file_type);
    }
    public function getFolderId($file_id) {
        return $this->fileModel->getFolderIdByFileId($file_id);
    }
    
}
?>
