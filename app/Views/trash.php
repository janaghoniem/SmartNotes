<?php
include_once '../includes/session.php';
$current_page = 'Trash';
?>
<?php

require_once __DIR__ . '/../Controllers/trashController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    trashController::handleAction($_POST);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trash</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- CSS Files -->
    <link href="../../public/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../public/assets/css/now-ui-dashboard.css" rel="stylesheet" />
    <link href="../../public/assets/css/demo.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../public/assets/css/user_style.css">
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

        .folder.red {
            background-color: #ffcccb;
            border: 1px solid #ff8885;
        }

        .folder.yellow {
            background-color: #fff7cc;
            border: 1px solid #ffd700;
        }

        .folder.blue {
            background-color: #cce5ff;
            border: 1px solid #85c1ff;
        }

        .folder {
            padding: 15px;
            margin: 10px;
            border-radius: 8px;
            text-align: center;
            position: relative;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 10px;
        }

        .action-buttons .btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
        }

        .btn.restore {
            color: #4caf50;
        }

        .btn.delete {
            color: #f44336;
        }

        .black-placeholder::placeholder {
            color: black !important;
            opacity: 1;
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
                    <h3 style="margin-bottom: 15px;">Recently Deleted</h3>
                    <section class="recent-folders">
                        <div class="folders">
                            <?php
                            require_once __DIR__ . '/../includes/trash_class.php';

                            $user_id = $_SESSION['UserID'];
                            $trashItems = trash::readTrash($user_id);
                            $colors = ['red', 'yellow', 'blue'];

                            if ($trashItems) {
                                foreach ($trashItems as $index => $item) {
                                    $color = $colors[$index % count($colors)];
                                    ?>
                                    <div class="folder <?php echo $color; ?>">
                                        <i class="fa-solid fa-folder fold"></i>
                                        <p><?php echo htmlspecialchars($item['name']); ?></p>
                                        <span><?php echo htmlspecialchars($item['deleted_at']); ?></span>

                                        <div class="action-buttons">
                                            <!-- Restore Form and Button -->
                                            <form method="post" action="" onsubmit="return confirmRestore()">
                                                <input type="hidden" name="id"
                                                    value="<?php echo htmlspecialchars($item['ID']); ?>">
                                                <input type="hidden" name="action" value="restore_from_trash">
                                                <button type="submit" class="btn restore" title="Restore">
                                                    <i class="fa-solid fa-rotate-left" style="font-size: 1rem;"></i>
                                                </button>
                                            </form>

                                            <!-- Delete Form and Button -->
                                            <form method="post" action="" onsubmit="return confirmDelete()">
                                                <input type="hidden" name="id"
                                                    value="<?php echo htmlspecialchars($item['ID']); ?>">
                                                <input type="hidden" name="action" value="delete_from_trash">
                                                <button type="submit" class="btn delete" title="Delete Permanently">
                                                    <i class="fa-solid fa-trash" style="font-size: 1rem;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="folder empty"
                                    style="display: flex; justify-content: center; align-items: center;">
                                    <p>No items in trash.</p>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div id="no-results" style="display: none; text-align: center; color: gray;">
                            No results found.
                        </div>
                    </section>
                </section>
            </main>
        </div>

        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this permanently?");
            }

            function confirmRestore() {
                return confirm("Are you sure you want to restore this folder?");
            }


        </script>
        <script src="../../public/assets/js/SearchandFilters.js"></script>

</body>

</html>
<div id="deleteModal" class="modal" style="display:none;">
    <div class="modal-content">
        <p>Are you sure you want to delete this folder permanently?</p><br><br>
        <form id="deleteForm" method="post" action="">
            <input type="hidden" name="id" id="folder_id"> <!-- Folder ID hidden input -->
            <input type="hidden" name="action" value="delete_from_trash"> <!-- Action input -->
            <button type="submit" class="btn-confirm">Yes, delete</button>
            <button type="button" class="btn-cancel" onclick="closeModal('deleteModal')">Cancel</button>
        </form>
    </div>
</div>



<!-- Delete Trigger Button -->


<!-- Restriction Popup Modal -->
<div id="restrictionPopup" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('restrictionPopup')">&times;</span>
        <p id="restrictionMessage"></p>
    </div>
</div>

