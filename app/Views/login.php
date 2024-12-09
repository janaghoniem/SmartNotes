<?php
require_once __DIR__ . '/../../app/Controllers/UserController.php';

$controller = new UserController();

$error = "";

if (isset($_POST['email1'])) {
  $error = $controller->login();
} elseif (isset($_POST['username'])){
  $error = $controller->register();
} else {

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartNotes</title>

    <!-- Fonts and CSS Libraries -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Karla:wght@200;300;400;500&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../public/assets/css/login.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 col-md-7 intro-section">
                <div class="brand-wrapper">
                    <h1><a href="./index.php">SmartNotes</a></h1>
                    <!-- <h5>Capture ideas. Unlock potential.</h5> -->
                </div>
                <div class="intro-content-wrapper">
                    <h3 class="intro-title">Welcome to SmartNotes!</h3>
                    <p class="intro-text">
                        Log in to access powerful tools like speech-to-text, summarization, and question generation, all
                        designed to boost your note-taking and productivity.</p>
                    <a href="index.php" class="btn btn-read-more">Read more</a>
                </div>
            </div>
            <div class="col-sm-6 col-md-5 form-section">
                <div class="login-wrapper">
                    <h2 class="login-title">Sign in</h2>
                    <form action="" method="POST" id="login-form">
                        <div class="form-group">
                            <label for="email1" class="sr-only">Email</label>
                            <input type="email" name="email1" id="email1" class="form-control" placeholder="Email">
                            <span id="email-error1" class="text-danger"></span> <!-- Error message for email -->
                        </div>
                        <div class="form-group mb-3">
                            <label for="password1" class="sr-only">Password</label>
                            <input type="password" name="password1" id="password1" class="form-control"
                                placeholder="Password">
                            <span id="password-error1" class="text-danger"></span> <!-- Error message for password -->
                        </div>
                        <?php if (!empty($error)): ?>
                            <p class="text-danger"><?php echo $error; ?></p> <!-- Server-side error message -->
                        <?php endif; ?>
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <input name="login" id="login-btn" class="btn login-btn" type="submit" value="Login">
                        </div>
                    </form>
                    <p class="login-wrapper-footer-text">Need an account? <a href="#" id="signup-toggle"
                            style="color: #171a59;"> Signup here</a></p>
                </div>

                <!-- Sign Up Form -->
                <div class="sign-up-form" style="display: none;">
                    <h2 class="login-title">Sign Up</h2>
                    <form action="" method="POST" id="form">
                        <!-- Ensure the form submits to login.php -->
                        <div class="form-group">
                            <label for="username" class="sr-only">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username"
                                required>
                            <div class="error-message" id="username-error"></div>
                        </div>

                        <!-- First Name and Last Name on the same row -->
                        <div class="form-group d-flex justify-content-between">
                            <div style="flex: 1; margin-right: 10px;">
                                <label for="firstname" class="sr-only">First Name</label>
                                <input type="text" name="firstname" id="firstname" class="form-control"
                                    placeholder="First Name" required>
                                <div class="error-message" id="firstname-error"></div>
                            </div>
                            <div style="flex: 1; margin-left: 10px;">
                                <label for="lastname" class="sr-only">Last Name</label>
                                <input type="text" name="lastname" id="lastname" class="form-control"
                                    placeholder="Last Name" required>
                                <div class="error-message" id="lastname-error"></div>
                            </div>
                        </div>
                        <!-- Country dropdown for major countries -->
                        <div class="form-group mb-3">
                            <label for="country" class="sr-only">Country</label>
                            <select name="country" id="country" class="form-control" required>
                                <option value="Argentina">Argentina</option>
                                <option value="Australia">Australia</option>
                                <option value="Brazil">Brazil</option>
                                <option value="Canada">Canada</option>
                                <option value="China">China</option>
                                <option value="Egypt" selected>Egypt</option> <!-- Default selected option -->
                                <option value="France">France</option>
                                <option value="Germany">Germany</option>
                                <option value="India">India</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Italy">Italy</option>
                                <option value="Japan">Japan</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Nigeria">Nigeria</option>
                                <option value="Russia">Russia</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="South Africa">South Africa</option>
                                <option value="South Korea">South Korea</option>
                                <option value="Turkey">Turkey</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States">United States</option>
                            </select>
                            <div class="error-message" id="country-error"></div>

                        </div>
                        <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email"
                                required>
                            <div class="error-message" id="email-error"></div>

                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Password" required>
                            <div class="error-message" id="password-error"></div>

                        </div>
                        <div class="form-group mb-3">
                            <label for="confirm_password" class="sr-only">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                                placeholder="Confirm Password" required>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" id="terms-checkbox" required>
                            <label for="terms-checkbox">
                                <a href="terms.php" target="_blank" class="terms-link">I agree to the terms and
                                    conditions</a>
                            </label>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <input name="signup" id="submit_button" class="btn login-btn" type="submit" value="Sign Up">
                        </div>
                    </form>
                    <p class="login-wrapper-footer-text">Already have an account? <a href="#" id="signin-toggle"
                            class="text-reset">Sign in here</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap Bundle JS -->
    <script src="../assets/js/admin_form_validation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/login.js"></script>
</body>

</html>