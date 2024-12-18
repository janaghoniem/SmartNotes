<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Config/Database.php';

class UserController
{
    // Handles user login
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars(trim($_POST['email1']));
            $password = htmlspecialchars(trim($_POST['password1']));

            // Attempt to log in using the User model
            $user = User::login($email, $password);

            if ($user) {
                // If the singleton instance is already set, prevent unnecessary redirects
                if (!User::getInstance()) {
                    // Set the singleton instance for the logged-in user
                    User::setInstance($user);
                }

                session_start();
                $_SESSION['UserID'] = $user->id;
                //echo $_SESSION['UserID'] . " " . User::getInstance()->username . "    " . User::getInstance()->userType_obj->pages_array[0]->link_address;
                // Redirect to the first page available to the user's type
                $first_page = $user->userType_obj->pages_array[0]->link_address;
                header("Location: /smartnotes/app/Views/" . $first_page);
                exit;
            } else {
                return "Invalid email or password";
            }
        }
    }



    // Handles user registration
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'username' => htmlspecialchars(trim($_POST['username'])),
                'first_name' => htmlspecialchars(trim($_POST['first_name'])),
                'last_name' => htmlspecialchars(trim($_POST['last_name'])),
                'email' => htmlspecialchars(trim($_POST['email'])),
                'password' => htmlspecialchars(trim($_POST['password'])),
                'country' => htmlspecialchars(trim($_POST['country'])),
                'user_type' => htmlspecialchars(trim($_POST['user_type'])),
            ];

            $user = UserFactory::createUser($data);

            if ($user) {
                header("Location: /smartnotes/app/Views/Login.php");
            } else {
                return "Registration failed. Email might already be in use.";
            }
        }
    }

    public function listUsers($type = null)
    {
        // Use UserFactory to fetch users based on the type
        return UserFactory::getAllUsers($type);
    }


    // Deletes a user (admin action)
    public function deleteUser($userId)
    {
        $user = User::getUserById($userId);
        if ($user && User::deleteUser($user)) {
            return "User deleted successfully";
        }
        return "Failed to delete user";
    }

    // Updates user details (profile or admin action)
    public function updateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = User::getInstance();

            $user->username = htmlspecialchars(trim($_POST['username']));
            $user->first_name = htmlspecialchars(trim($_POST['firstname']));
            $user->last_name = htmlspecialchars(trim($_POST['lastname']));
            $user->email = htmlspecialchars(trim($_POST['email']));
            $user->country = htmlspecialchars(trim($_POST['country']));

            if (!empty($_POST['password'])) {
                $user->password = password_hash(htmlspecialchars(trim($_POST['password'])), PASSWORD_DEFAULT);
            }

            if ($user->updateUser()) {
                return "User updated successfully";
            }
            return "Failed to update user";
        }
    }

    // Logs out the user
    public function logout()
    {
        // Destroy the session
        session_start();
        session_unset();
        session_destroy();

        // Reset the Singleton instance
        User::logout();

        // Redirect to login page
        header("Location: /smartnotes/public/index.php");
        exit;
    }

}
?>