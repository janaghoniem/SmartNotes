<?php
include_once '../includes/session.php';

$current_page = 'User dashboard';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notes</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../../public/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../public/assets/css/now-ui-dashboard.css" rel="stylesheet" />
    <link href="../../public/assets/css/demo.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../public/assets/css/user_style.css">
    <link rel="stylesheet" href="../../public/assets/css/survey.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        button:disabled {
            background-color: #e0e0e0;
            color: #777;
            cursor: not-allowed;
            opacity: 0.6;
            border: none;
        }

        .popover-btn:disabled {
            background-color: #e0e0e0;
            color: #888;
        }

        .black-placeholder::placeholder {
            color: black !important;
            opacity: 1;
        }

        .note {
            position: relative;
            cursor: pointer;
        }

        .popover {
            position: absolute;
            top: 0;
            width: 8em;
            right: 100%;
            margin-left: 10px;
            display: none;
            background: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 300000;
        }

        .note:hover .popover {
            display: block;
        }

        .filter-buttons button.active {
            background-color: #f1f1f1;
            color: #555;
            border-radius: 5px;
            border-bottom: 1px solid black;

        }

        .filter-buttons {
            display: flex;
            gap: 10px;
        }


        /* Dropdown container */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        /* Dropdown button */
        .dropbtn {
            padding: 8px 12px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .dropbtn:hover {
            background-color: #0056b3;
        }

        /* Dropdown content */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 4px;
            overflow: hidden;
        }

        /* Links inside dropdown */
        .dropdown-content a {
            color: black;
            padding: 10px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        /* Show the dropdown on hover */
        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include '../includes/sidebar.php'; ?>
        <div class="main-panel" id="main-panel">
            <?php include '../includes/user_navbar.php' ?>
            <main class="content">
                <section class="bordered-content">
                    <h3 style="margin-bottom: 15px;">Recents</h3>

                    <section class="recent-folders">
                        <div class="filter-buttons">
                            <button class="filter-btn" data-filter="today">Today</button>
                            <button class="filter-btn" data-filter="this week">This Week</button>
                            <button class="filter-btn" data-filter="this month">This Month</button>
                            <div class="dropdown">
                                <button class="dropbtn">Sort by</button>
                                <div class="dropdown-content">
                                    <a href="#" data-sort="name">Name</a>
                                    <a href="#" data-sort="created">Date Created</a>
                                    <a href="#" data-sort="modified">Last Modified</a>
                                </div>
                            </div>
                        </div>

                        <div class="folders">
                            <?php
                            include_once '../includes/folder_class.php';
                            include_once '../includes/session.php';
                            $user_id = $_SESSION['UserID'];
                            $obj = folder::readRecent($user_id);
                            $colors = ['blue', 'yellow', 'red'];
                            if ($obj) {
                                for ($j = 0; $j < count($obj); $j++) {
                                    $color = $colors[$j % count($colors)];
                                    $folderId = $obj[$j]['ID'];
                                    $folderName = strtolower($obj[$j]['name']);
                                    $isGeneral = ($folderId == 1 && $folderName == 'general');
                                    ?>
                                    <div class="folder <?php echo $color; ?>"
                                        data-created-at="<?php echo htmlspecialchars($obj[$j]['created_at']); ?>">
                                        <a href="folder_contents.php?folder_id=<?php echo $folderId; ?>" class="folder-link">
                                            <i class="fa-solid fa-folder fold"></i>
                                            <p><?php echo htmlspecialchars($obj[$j]['name']); ?></p>
                                        </a>
                                        <span><?php echo htmlspecialchars($obj[$j]['created_at']); ?></span>
                                        <i class="fa-solid fa-ellipsis ellipsis"></i>
                                        <div class="popover" style="z-index: 300000;">
                                            <!-- Rename Button -->
                                            <button class="popover-btn rename" data-folder-id="<?php echo $folderId; ?>">
                                                Rename
                                            </button>
                                            <button class="popover-btn move" data-folder-id="<?php echo $folderId; ?>">
                                                Move
                                            </button>
                                            <!-- Delete Button -->
                                            <button class="popover-btn delete" data-item-id="<?php echo $folderId; ?>"
                                                data-item-type="folder">
                                                Delete
                                            </button>
                                        </div>
                                    </div>

                                    <?php
                                }
                            }
                            ?>
                        </div>

                    </section>
                    <section class="my-notes">
                        <h3 style="margin-bottom: 15px;">My Notes</h3>

                        <div class="notes">
                            <?php
                            ini_set('display_errors', 1);
                            ini_set('display_startup_errors', 1);
                            error_reporting(E_ALL);
                            
                            require_once __DIR__ . '/../Models/file_class.php';
                            
                            $user_id = $_SESSION['UserID'];

                            $folder_id = isset($_GET['folder_id']) ? $_GET['folder_id'] : null;
                            $files = file::readAll($user_id, $folder_id);
                            ?>

                            <?php if ($files): ?>
                                <?php foreach ($files as $index => $file): ?>
                                    <div class="note <?php echo $colors[$index % 3]; ?>"
                                        data-note-id="<?php echo $file['id']; ?>"
                                        data-created-at="<?php echo $file['created_at']; ?>">
                                        <span><?php echo date('d/m/Y', strtotime($file['created_at'])); ?></span>
                                        <h3 class="note-name">
                                            <?php echo htmlspecialchars($file['name'], ENT_QUOTES, 'UTF-8'); ?>
                                            <i class="fa-solid fa-ellipsis ellipsis"></i>
                                            <div class="popover" style="z-index: 300000;">
                                                <button class="popover-btn rename"
                                                    data-note-id="<?php echo $file['id']; ?>">Rename</button>
                                                <button class="popover-btn move"
                                                    data-folder-id="<?php echo $folder_id; ?>">Move</button>
                                                <button class="popover-btn delete" data-item-id="<?php echo $file['id']; ?>"
                                                    data-item-type="file">Delete</button>
                                            </div>
                                        </h3>
                                        <hr>
                                        <p><?php echo strlen($file['content']) > 100 ? substr($file['content'], 0, 100) . '...' : $file['content']; ?>
                                        </p>
                                        <span
                                            class="bottom"><?php echo "⏱️ " . date('h:i A, l', strtotime($file['created_at'])); ?></span>
                                    </div>
                                    <div id="no-results" style="display: none; text-align: center; color: gray;">
                                        No results found.
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No files found.</p>
                            <?php endif; ?>

                            <script>
                                document.querySelectorAll('.note').forEach(note => {
                                    note.addEventListener('click', function () {
                                        const noteId = this.getAttribute('data-note-id');
                                        if (noteId) {
                                            window.location.href = `../Views/Note.php?id=${noteId}`;
                                        } else {
                                            console.error("Note ID is null or undefined.");
                                        }
                                    });
                                });
                            </script>


                            <?php include '../includes/survey.php' ?>



                            <script src="../../public/assets/js/sidebar.js"></script>
                            <script src="../../public/assets/js/survey.js"></script>
                            <script src="../../public/assets/js/core/jquery.min.js"></script>
                            <script src="../../public/assets/js/core/popper.min.js"></script>
                            <script src="../../public/assets/js/core/bootstrap.min.js"></script>
                            <script src="../../public/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
                            <script src="../../public/assets/js/plugins/chartjs.min.js"></script>
                            <script src="../../public/assets/js/plugins/bootstrap-notify.js"></script>
                            <script src="../../public/assets/js/now-ui-dashboard.min.js?v=1.5.0"
                                type="text/javascript"></script>

                            <script src="../../public/assets/js/SearchandFilters.js"></script>




</body>

</html>