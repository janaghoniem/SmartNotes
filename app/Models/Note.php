<?php
namespace App\Models;

use App\Config\Database;
require_once __DIR__ . '/../Config/Database.php';

class Note {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getContentById($file_id) {
        $stmt = $this->db->prepare("SELECT content FROM files WHERE id = ?");
        $stmt->bind_param("i", $file_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['content'] ?? null;
    }


    public function createFile($name, $user_id, $folder_id, $content, $file_type) {
        $stmt = $this->db->prepare("INSERT INTO files (name, user_id, folder_id, content, created_at, file_type) 
                                    VALUES (?, ?, ?, ?, NOW(), ?)");
        $stmt->bind_param("siisi", $name, $user_id, $folder_id, $content, $file_type);
        return $stmt->execute() ? $this->db->insert_id : false;
    }

    public function getFolderIdByFileId($file_id) {
        $stmt = $this->db->prepare("SELECT folder_id FROM files WHERE id = ?");
        $stmt->bind_param("i", $file_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['folder_id'] ?? null;
    }
    


    public function getFileById($file_id) {
        $stmt = $this->db->prepare("SELECT * FROM files WHERE id = ?");
        $stmt->bind_param("i", $file_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $file = $result->fetch_assoc();
        $stmt->close();
        return $file;
    }
    
    public function updateFileContent($file_id, $content) {
        $stmt = $this->db->prepare("UPDATE files SET content = ? WHERE id = ?");
        $stmt->bind_param("si", $content, $file_id);
        return $stmt->execute();
    }
    
}
?>
