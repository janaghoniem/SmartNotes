<?php
session_start();

require_once __DIR__ . '/../Models/User.php';

function isPageAllowed($currentPage, $allowedPages) {
    foreach ($allowedPages as $page) {
        if ($page->link_address === $currentPage) {
            return true;
        }
    }
    return false;
}

$currentPage = basename($_SERVER['PHP_SELF']);

if (!empty($_SESSION['UserID'])) {
    $UserObject = new User($_SESSION["UserID"]);

    if (isPageAllowed($currentPage, $UserObject->userType_obj->pages_array)) {
        
    } else {
        http_response_code(401);
        header("Location: /public/401.php");
        exit;
    }
} else {
    require_once __DIR__ . '/../app/Config/Database.php'; // Centralized DB connection
    $db = Database::getInstance()->getConnection();

    $stmt = $db->prepare("
        SELECT pages.friendly_name, pages.link_address 
        FROM pages 
        INNER JOIN usertype_pages ON pages.id = usertype_pages.page_id 
        WHERE usertype_pages.usertype_id = ?
    ");
    $guestUserType = 3;
    $stmt->bind_param("i", $guestUserType);
    $stmt->execute();
    $result = $stmt->get_result();

    $isAllowed = false;
    while ($row = $result->fetch_assoc()) {
        if ($row['link_address'] === $currentPage) {
            $isAllowed = true;
            break;
        }
    }

    if (!$isAllowed) {
        http_response_code(401);
        header("Location: /public/401.php");
        exit;
    }

    $stmt->close();
    $db->close();
}
