<?php
use App\Config\Database;

require_once __DIR__ . '/../Config/Database.php';

$conn = Database::getInstance()->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = key($_POST);
    $value = $_POST[$input];

    $sql = "SELECT * FROM users WHERE $input = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "exists";
    } else {
        echo "available";
    }

    $stmt->close();
    $conn->close();
}
?>
