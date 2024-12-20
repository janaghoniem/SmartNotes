<?php
require_once __DIR__ . '/../Config/Database.php';
include 'Page.php';

class UserType
{
    public $id;
    public $userType_name;
    public $pages_array = []; // Initialize as an empty array

    public function __construct($id)
    {
        if (!empty($id)) {
            $sql = "
            SELECT ut.id AS usertype_id, ut.name AS usertype_name, p.id AS page_id, p.name AS page_name
            FROM user_types ut
            LEFT JOIN usertype_pages up ON ut.id = up.usertype_id
            LEFT JOIN pages p ON up.page_id = p.id
            WHERE ut.id = ?
            ";

            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare the SQL statement: " . $db->error);
            }

            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch user type and associated pages
            while ($row = $result->fetch_assoc()) {
                if (empty($this->id)) {
                    $this->id = $row['usertype_id'];
                    $this->userType_name = $row['usertype_name'];
                }

                if (!empty($row['page_id'])) {
                    $this->pages_array[] = new Page($row['page_id']);
                }
            }

            $stmt->close();
        }
    }
}
?>
