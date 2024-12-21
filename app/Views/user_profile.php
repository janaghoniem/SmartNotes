<?php
use App\Controllers\UserController;
use App\Controllers\SurveyController;
require_once __DIR__ . '/../../app/Controllers/UserController.php';
require_once __DIR__ . '/../../app/Controllers/SurveyController.php';
require_once __DIR__ . '/../../app/includes/session.php';

//set current page to update sidebar status
$current_page = 'My Account';


$colors = ['#8eb0f0', '#f2e982', '#c6408a'];

// Initialize controllers
$userController = new UserController();
$surveyController = new SurveyController();

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
  $result = $userController->updateUser();
  if ($result === "User updated successfully") {
    echo "User profile updated successfully.";
  } else {
    echo "There was an error updating the profile.";
  }
}

// Handle Survey Answer Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['question_id']) && isset($_POST['selected_option'])) {
  $result = $surveyController->updateAnswer($UserObject->id, $_POST['question_id'], $_POST['selected_option']);
  error_log($result);
  if ($result) {
    echo "Your answer has been updated successfully.";
  } else {
    echo "There was an error updating your answer.";
  }
}

// Render the form with current user data and survey questions
$questions = $surveyController->getSurveyQuestions();
$answers = $surveyController->getUserAnswers($UserObject->id);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../../public/assets/images/notes.png" rel="icon">
  <title>My Account</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="../../public/assets/css/user_style.css" rel="stylesheet">
  <link href="../../public/assets/css/user_profile.css" rel="stylesheet">
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- CSS Files -->
  <link href="../../public/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../../public/assets/css/now-ui-dashboard.css" rel="stylesheet" />
  <link href="../../public/assets/css/demo.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    label {
      font-size: 14px !important;
    }

    h6 {
      font-weight: 500;
      text-transform: none;
    }

    .btn-primary {
      background-color: #E66A6A !important;
      color: white;
    }

    .btn-primary:hover {
      background-color: #88aef4 !important;
    }

    .btn-outline-primary {
      color: #E66A6A !important;
      border-color: #E66A6A !important
    }

    .btn-outline-primary:hover {
      color: #88aef4 !important;
      border-color: #88aef4 !important
    }
  </style>
</head>

<body>
  <?php include '../includes/sidebar.php'; ?>
  <div class="main-panel">
    <main class="content">
      <div class="bordered-content">
        <div style=>
          <div class="row gutters-sm">
            <div class="col-md-4 mb-0">
              <div class="card white-card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <i class="now-ui-icons business_badge" style="font-size: 60px; margin-bottom: 0px;"></i>
                    <div>
                      <h4 style="font-weight: 600;"><?php echo $UserObject->username ?></h4>
                      <p class="text-secondary mb-1">
                        <?php echo $UserObject->first_name . " " . $UserObject->last_name . ", " . $UserObject->country ?>
                      </p>
                      <p class="text-muted font-size-sm"><?php echo $UserObject->email ?></p>
                      <button id="logout-btn" class="btn btn-primary">Log out</button>
                      <button id="deactivate-btn" class="btn btn-outline-primary">Deactivate Account</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-8 mb-0">
              <div class="card" style="height: 300px">
                <div class="card-body">
                  <div class="card-header" style="background-color: white;">
                    <h5 class="d-flex align-items-center" style="margin: 0px; color: black; font-weight: 400;">
                      <?php echo $UserObject->first_name . "'s survey answers" ?>
                    </h5>
                    <hr>
                  </div>
                  <ul class="list-group list-group-flush">
                    <?php
                    $i = 0;
                    foreach ($questions as $question_id => $question): ?>
                      <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0 d-flex align-items-center">
                          <i class="<?php echo isset($answers[$question_id]) ? $answers[$question_id]['option_icon'] : ''; ?>"
                            style='color: <?= $colors[$i % count($colors)] ?>; font-size: 30px; margin-right: 15px;'></i>
                          <?= htmlspecialchars($question['text']) ?>
                        </h6>

                        <select class="form-control text-secondary ml-0 survey-answer"
                          data-question-id="<?= $question_id ?>" style="width: 300px;">
                          <option value="">Select</option>
                          <?php foreach ($question['options'] as $option): ?>
                            <option value="<?= $option['option_id'] ?>" <?= isset($answers[$question_id]) && $answers[$question_id]['selected_option'] == $option['option_id'] ? 'selected' : '' ?>>
                              <span><?= $option['answer_option'] ?></span>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </li>
                      <?php $i++; endforeach; ?>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-12 mb-0">
              <div class="card white-card">
                <div class="card-body">
                  <form action="" method="POST" id="form">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="row">
                      <div class="col-sm-3">
                        <label class="mb-0">Username</label>
                      </div>
                      <div class="col-sm-4 text-secondary">
                        <input type="text" class="form-control" value="<?php echo $UserObject->username ?>"
                          name="username">
                        <div class="error-message" id="username-error"></div>
                      </div>
                      <div class="col-sm-1">
                        <label class="mb-0">Password</label>
                      </div>
                      <div class="col-sm-4 text-secondary">
                        <input type="password" class="form-control" name="password">
                        <div class="error-message" id="password-error"></div>
                      </div>

                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-sm-3">
                        <label class="mb-0">Full Name</label>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <div class="row">
                          <div class="col-sm-6">
                            <input name="firstname" type="text" class="form-control"
                              value="<?php echo $UserObject->first_name ?>">
                            <div class="error-message" id="firstname-error"></div>
                          </div>
                          <div class="col-sm-6">
                            <input name="lastname" type="text" class="form-control"
                              value="<?php echo $UserObject->last_name ?>">
                            <div class="error-message" id="lastname-error"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-sm-3">
                        <label class="mb-0">Email</label>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <input name="email" type="email" class="form-control" value="<?php echo $UserObject->email ?>">
                        <div class="error-message" id="email-error"></div>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-sm-3">
                        <label class="mb-0">Country</label>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <select class="form-control" id="country" name="country">
                          <option value="">Select Country</option>
                          <?php
                          $countries = ["United States", "Canada", "United Kingdom", "Australia", "Germany", "France", "Japan", "China", "India", "Egypt"];
                          foreach ($countries as $country) {
                            $selected = ($UserObject->country === $country) ? 'selected' : '';
                            echo "<option value='$country' $selected>$country</option>";
                          }
                          ?>
                        </select>
                        <div class="error-message" id="country-error"></div>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-sm-12">
                        <button type="button" class="btn btn-primary"
                          onclick="saveChanges('<?php echo htmlspecialchars($UserObject->username, ENT_QUOTES, 'UTF-8'); ?>', 
                                        '<?php echo htmlspecialchars($UserObject->email, ENT_QUOTES, 'UTF-8'); ?>')">Save Changes</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
  </div>
  </main>
  </div>
  <script src="../../public/assets/js/user_profile.js"></script>
  <script src="../../public/assets/js/admin_form_validation.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
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

  <script>
    $(document).ready(function () {
      // When the user changes their selection
      $('.survey-answer').change(function () {
        var questionId = $(this).data('question-id');  // Get the question ID
        var selectedOption = $(this).val();  // Get the selected option ID
        
        // Send the data via AJAX to update the answer in the database
        $.ajax({
          url: 'user_profile.php',  // The backend PHP file that processes the update
          type: 'POST',
          data: {
            question_id: questionId,
            selected_option: selectedOption
          },
          success: function (response) {
            // Handle response if needed, e.g., show success message
            alert("Your answers have been updated successfully.");
            location.reload();
          },
          error: function () {
            alert('Error updating your answer.');
          }
        });
      });
    });

  </script>
</body>

</html>