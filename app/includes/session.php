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
$UserObject = null;
$currentPage = basename($_SERVER['PHP_SELF']);

if (!empty($_SESSION['UserID'])) {
    if (!User::getInstance()) {
        // If no instance is set, we need to set it using the user ID stored in the session
        $user = User::getUserById($_SESSION['UserID']);
        if ($user) {
            User::setInstance($user); // Set the singleton instance
        }
    }

    // Retrieve the singleton instance
    $UserObject = User::getInstance();

    if (isPageAllowed($currentPage, $UserObject->userType_obj->pages_array)) {
        
    } else {
        http_response_code(401);
        header("Location: /smartnotes/Public/401.php");
        exit;
    }
} else {
    require_once __DIR__ . '/../Config/Database.php';
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
        //header("Location: /smartnotes/app/Views/login.php");
        header("Location: /smartnotes/Public/401.php");
        exit;
    }

    $stmt->close();
    $db->close();
}
