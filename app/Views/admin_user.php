<?php
  //connect to database
  require_once __DIR__ . '/../../app/Controllers/UserController.php';
  require_once __DIR__ . '/../../app/includes/session.php';

  //set current page to update sidebar status
  $current_page = 'Admin Profile';

  $userController = new UserController();

  //graph data from user if admin form was submitted 
  // Handle form submission for adding or editing an admin
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $Username = htmlspecialchars($_POST["username"]);
    $Password = htmlspecialchars($_POST["password"]);
    // Encrypt password for additional security
    $Hashedpassword = password_hash($Password, PASSWORD_DEFAULT);
    $Fname = htmlspecialchars($_POST["firstname"]);
    $Lname = htmlspecialchars($_POST["lastname"]);
    $Email = htmlspecialchars($_POST["email"]);
    $Country = htmlspecialchars($_POST["country"]);
    $UserType = new UserType(1);

    if ($user_id) {
        // Update existing user
        $user = new User($user_id);
        $user->first_name = $Fname;
        $user->last_name = $Lname;
        $user->username = $Username;
        $user->email = $Email;
        $user->password = $Hashedpassword;
        $user->country = $Country;
        $user->user_type = $UserType->id;
        $result = $user->updateUser();
    } else {
        // Insert new user
        $result = User::insertUser($Username, $Fname, $Lname, $Email, $Hashedpassword, $Country, '1');
    }

    if ($result) {
        echo "<script>console.log('Admin saved successfully.');</script>";
    } else {
        echo "<script>console.log('Error saving admin.');</script>";
    }
  }

  //get all admins from database
  $admins = $userController->listUsers($UserObject->userType_obj->id);
?>

<!--

=========================================================
* Now UI Dashboard - v1.5.0
=========================================================

* Product Page: https://www.creative-tim.com/product/now-ui-dashboard
* Copyright 2019 Creative Tim (http://www.creative-tim.com)

* Designed by www.invisionapp.com Coded by www.creative-tim.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../../public/assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../public/assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Now UI Dashboard by Creative Tim
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <!-- CSS Files -->
  <link href="../../public/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../../public/assets/css/now-ui-dashboard.css" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
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
      <!-- Navbar -->
      <?php include '../includes/admin_navbar.php'; ?>
      <!-- End Navbar -->
      <div class="panel-header panel-header-sm">
      </div>
      <div class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5 class="title">Admin Profile</h5>
            </div>
            <div class="card-body">
              <form method="POST" action="admin_user.php" id = "form">
                <input type="hidden" name="user_id" id="user_id">
                  <div class="row">
                      <div class="col-md-5 pr-1">
                          <div class="form-group">
                              <label for="company">Company (disabled)</label>
                              <input type="text" class="form-control" disabled="" placeholder="Company" value="SmartNotes Inc." name="company" id="company">
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
                              <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                              <div class="error-message" id="password-error"></div>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6 pr-1">
                          <div class="form-group">
                              <label for="firstname">First Name</label>
                              <input type="text" class="form-control" placeholder="First Name" name="firstname" id="firstname">
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
                              <select class="form-control" id="country" name="country" id="country">
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
                                  <!-- Add more countries as needed -->
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
              </form>
            </div>
          </div>
        </div>
    </div>
    <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Admin Table</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table" id="dataTable">
                    <thead class=" text-primary">
                      <th>
                        Full Name
                      </th>
                      <th>
                        Username
                      </th>
                      <th>
                        Email
                      </th>
                    </thead>
                    <tbody>
                      <?php
                        foreach ($admins as $admin) {
                          echo "<tr id='admin-row-{$admin->id}'>";
                          echo "<td>" . $admin->first_name . " " . $admin->last_name . "</td>";
                          echo "<td>" . $admin->username . "</td>";
                          echo "<td>" . $admin->email . "</td>";
                          echo '<td class="td-actions text-right">
                                  <button type="button" rel="tooltip" title="" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Edit Task" onclick="editUser(' . $admin->id . ', \'' . $admin->first_name . '\', \'' . $admin->last_name . '\', \'' . $admin->username . '\', \'' . $admin->email . '\', \'' . $admin->country . '\', \'' . $admin->userType_obj->id . '\')">
                                    <i class="now-ui-icons ui-2_settings-90"></i>
                                  </button>
                                  <button type="button" rel="tooltip" title="" class="btn btn-danger btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Remove" data-user-id="' . $admin->id . '" onclick="deleteUser(' . $admin->id . ', this)">
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
  <!--   Core JS Files   -->
  <script src="../../public/assets/js/core/jquery.min.js"></script>
  <script src="../../public/assets/js/core/popper.min.js"></script>
  <script src="../../public/assets/js/core/bootstrap.min.js"></script>
  <script src="../../public/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Chart JS -->
  <script src="../../public/assets/js/plugins/chartjs.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="../../public/assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../public/assets/js/now-ui-dashboard.min.js?v=1.5.0" type="text/javascript"></script>
  <!-- Now Ui Dashboard DEMO methods, don't include it in your project! -->
  <script src="../../public/assets/js/demo.js"></script>
  <script src="../../public/assets/js/admin_form_validation.js"></script>
  <script src="../../public/assets/js/delete_user.js"></script>
  <script src="../../public/assets/js/searchAdmin.js"></script>
</body>
</html>