<?php
use App\Controllers\UserController;

// Include necessary controllers and session management
require_once __DIR__ . '/../../app/Controllers/UserController.php';
require_once __DIR__ . '/../../app/includes/session.php';

// Set current page for sidebar navigation
$current_page = 'Admin Profile';

// Create an instance of UserController
$userController = new UserController();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user_id'])) {
  $user_id = $_POST['delete_user_id'];
  $userId = intval($_POST['delete_user_id']); // Sanitize the input

  // Call the delete method
  $result = $userController->deleteUser($userId);

  // Respond with JSON
  if ($result) {
    echo json_encode([
      'status' => 'success',
      'message' => 'User deleted successfully.'
    ]);
  } else {
    echo json_encode([
      'status' => 'error',
      'message' => 'Failed to delete user.'
    ]);
  }
  exit;
}

// Handle form submission for adding or editing an admin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect and sanitize form input
  $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
  if ($user_id) {
    // Update existing user via controller method
    $result = $userController->updateUser();
  } else {
    // Insert new user via controller method
    $userController->register();
  }

  // Provide feedback based on result
  if ($result) {
    echo "<script>console.log('Admin saved successfully.');</script>";
  } else {
    echo "<script>console.log('Error saving admin.');</script>";
  }
}

// Get the list of users via the UserController
$admins = $userController->listUsers(1);  // Assuming 1 is the admin type
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../../public/assets/img/apple-icon.png">
  <link href="../../public/assets/images/notes.png" rel="icon">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Admin Profile</title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
    name='viewport' />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" />
  <link href="../../public/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../../public/assets/css/now-ui-dashboard.css" rel="stylesheet" />
  <link href="../../public/assets/css/demo.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../public/assets/css/user_style.css">
  <style>
    .is-invalid-label {
      color: red;
    }

    .error-message {
      color: red !important;
      font-size: 0.875em !important;
      margin-top: 5px !important;
    }
  </style>
</head>

<body class="user-profile">
  <div class="wrapper ">
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-panel" id="main-panel">
      <?php include '../includes/admin_navbar.php'; ?>
      <div class="panel-header panel-header-sm"></div>
      <div class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Admin Profile</h5>
              </div>
              <div class="card-body">
                <form method="POST" action="admin_user.php" id="form">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $UserObject->id ?>">
                <div class="row">
                    <div class="col-md-5 pr-1">
                      <div class="form-group">
                        <label for="company">Company (disabled)</label>
                        <input type="text" class="form-control" disabled="" value="SmartNotes Inc." name="company"
                          id="company">
                      </div>
                    </div>
                    <div class="col-md-3 px-1">
                      <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" placeholder="Username" name="username" id="username">
                        <div class="error-message" id="username-error"></div>
                      </div>
                    </div>
                    <div class="col-md-4 pl-1">
                      <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" placeholder="Password" name="password"
                          id="password">
                        <div class="error-message" id="password-error"></div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-1">
                      <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control" placeholder="First Name" name="firstname"
                          id="firstname">
                        <div class="error-message" id="firstname-error"></div>
                      </div>
                    </div>
                    <div class="col-md-6 pl-1">
                      <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control" placeholder="Last Name" name="lastname" id="lastname">
                        <div class="error-message" id="lastname-error"></div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-1">
                      <div class="form-group">
                        <label for="country">Country</label>
                        <select class="form-control" id="country" name="country">
                          <option value="">Select Country</option>
                          <option value="United States">United States</option>
                          <option value="Canada">Canada</option>
                          <option value="United Kingdom">United Kingdom</option>
                          <option value="Australia">Australia</option>
                          <option value="Germany">Germany</option>
                          <option value="France">France</option>
                          <option value="Japan">Japan</option>
                          <option value="China">China</option>
                          <option value="India">India</option>
                          <option value="Egypt">Egypt</option>
                        </select>
                        <div class="error-message" id="country-error"></div>
                      </div>
                    </div>
                    <div class="col-md-6 pl-1">
                      <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                        <div class="error-message" id="email-error"></div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <button type="submit" class="btn btn-primary btn-block" id="submit_button">Save Admin</button>
                        </div>
                        <div class="col-md-6">
                          <button type="reset" class="btn btn-primary btn-block">Reset</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="user_type" value="1">
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Admin Table Section -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Admin Table</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table" id="dataTable">
                    <thead class="text-primary">
                      <th>Full Name</th>
                      <th>Username</th>
                      <th>Email</th>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($admins as $admin) {
                        echo "<tr id='admin-row-{$admin->id}'>";
                        echo "<td>" . $admin->first_name . " " . $admin->last_name . "</td>";
                        echo "<td>" . $admin->username . "</td>";
                        echo "<td>" . $admin->email . "</td>";
                        echo '<td class="td-actions text-right">
                                  <button type="button" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral" onclick="editUser(' . $admin->id . ', \'' . $admin->first_name . '\', \'' . $admin->last_name . '\', \'' . $admin->username . '\', \'' . $admin->email . '\', \'' . $admin->country . '\', \'' . $admin->userType_obj->id . '\')">
                                    <i class="now-ui-icons ui-2_settings-90"></i>
                                  </button>';
                        if ($admin->id == $UserObject->id) {
                          echo "</td></tr>";
                          continue;
                        }
                        ;
                        echo '<button type="button" class="btn btn-danger btn-round btn-icon btn-icon-mini btn-neutral" onclick="deleteUser(' . $admin->id . ', this)">
                                    <i class="now-ui-icons ui-1_simple-remove"></i>
                                  </button>
                                </td>';
                        echo "</tr>";
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JS Scripts -->
  <script src="../../public/assets/js/core/jquery.min.js"></script>
  <script src="../../public/assets/js/core/popper.min.js"></script>
  <script src="../../public/assets/js/core/bootstrap.min.js"></script>
  <script src="../../public/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="../../public/assets/js/now-ui-dashboard.min.js?v=1.5.0"></script>
  <script src="../../public/assets/js/demo.js"></script>
  <script src="../../public/assets/js/admin_form_validation.js"></script>
  <script src="../../public/assets/js/delete_user.js"></script>
  <script src="../../public/assets/js/searchAdmin.js"></script>
</body>

</html>