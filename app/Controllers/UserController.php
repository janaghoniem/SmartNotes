<?php
require_once __DIR__ . '/../Models/User.php';
class UserController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = htmlspecialchars(trim($_POST['email1']));
            $password = htmlspecialchars(trim($_POST['password1']));

            $user = User::login($email, $password);
            if ($user) {
                session_start();
                $_SESSION['UserID'] = $user->id;
                $firstPage = "UserDashboard.php"; // Ensure it's just the file name
                header("Location: /smartnotes/app/Views/" . $firstPage);  // Absolute path from the root

            } else {
                $error = "Invalid email or password";
                return $error;
            }
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'username' => htmlspecialchars(trim($_POST['username'])),
                'first_name' => htmlspecialchars(trim($_POST['firstname'])),
                'last_name' => htmlspecialchars(trim($_POST['lastname'])),
                'email' => htmlspecialchars(trim($_POST['email'])),
                'password' => htmlspecialchars(trim($_POST['password'])),
                'country' => htmlspecialchars(trim($_POST['country'])),
                'user_type' => 2,
            ];

            if (User::insertUser($data)) {
                header("Location: /smartnotes/app/Views/login.php");
            } else {
                $error = "Registration failed. Email might already be in use.";
                return $error;
            }
        }
    }
}
?>