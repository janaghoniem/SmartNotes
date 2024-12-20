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
            $db = Database::getInstance()->getConnection();

            // Fetch user type information
            $sql = "SELECT * FROM user_types WHERE id = ?";
            $stmt = $db->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare the SQL statement: " . $db->error);
            }

            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $this->id = $row['id'];
                $this->userType_name = $row['name'];

                // Fetch associated pages
                $sql = "SELECT page_id FROM usertype_pages WHERE usertype_id = ?";
                $stmt = $db->prepare($sql);

                if (!$stmt) {
                    throw new Exception("Failed to prepare the SQL statement: " . $db->error);
                }

                $stmt->bind_param("i", $this->id);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row1 = $result->fetch_assoc()) {
                    $this->pages_array[] = new Page($row1['page_id']);
                }
            }

            $stmt->close();
        }
    }
}
?>
