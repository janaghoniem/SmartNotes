<?php
require_once __DIR__ . '/../Config/Database.php';
include 'UserType.php';
include 'UserActivity.php';

class User {
    private static $instance = null;
    public $id;
    public $username;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $country;
    public $userType_obj;
    public $created_at;

    protected function __construct($user_id = null) {
        if ($user_id) {
            $this->loadUser($user_id);
        }
    }

    // Singleton pattern: Get the single instance of the User class
    public static function getInstance() {
        return self::$instance;
    }

    // Set the singleton instance explicitly
    public static function setInstance($user) {
        self::$instance = $user;
    }

    // Clear the singleton instance (used for logout)
    public static function clearInstance() {
        self::$instance = null;
    }

    private function loadUser($id) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->country = $row['country'];
            $this->userType_obj = new UserType($row["user_type"]);
            $this->created_at = $row['created_at'];

            // Set the Singleton instance when user is loaded
            self::setInstance($this);
        }
    }

    public static function login($email, $password) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $user = new User($row['id']); 

                if ($user->userType_obj->id == 2) {
                    UserActivity::startSession($user->id); // Start session tracking
                }

                // Set the Singleton instance
                self::setInstance($user);

                return $user; 
            }
        }
        return null;
    }

    public static function logout() {
        // Clear any session-related data (if applicable)
        UserActivity::endSession(self::getInstance()->id);
        // Reset the singleton instance
        self::clearInstance();
    }

    static function deleteUser($objUser) {
        $db = Database::getInstance()->getConnection();
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $objUser->id);
        return $stmt->execute();
    }

    static function insertUser($data) {
        $db = Database::getInstance()->getConnection();
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, first_name, last_name, email, password, country, user_type) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param(
            "ssssssi",
            $data['username'],
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $hashedPassword,
            $data['country'],
            $data['user_type']
        );
        return $stmt->execute();
    }

    public function updateUser() {
        $db = Database::getInstance()->getConnection();
        $sql = "UPDATE users SET 
            username = ?, first_name = ?, last_name = ?, email = ?, password = ?, country = ?, user_type = ?
            WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param(
            "ssssssii", 
            $this->username, $this->first_name, $this->last_name, $this->email, $this->password, $this->country, $this->userType_obj->id, $this->id
        );
        return $stmt->execute();
    }

    static function getUserById($id) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return new User($row['id']);
        }
        return null;
    }

    static function getUserByEmail($email) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new User($row['id']);
        }
        return null;
    }
}


// Factory Method Pattern: UserFactory class to create users based on user type

class UserFactory {
    public static function createUser($userData) {
        if ($userData['user_type'] == 1) {
            return new AdminUser($userData); // Create an Admin User
        } else {
            return new RegularUser($userData); // Create a Regular User
        }
    }

    // Static method to get all users based on type
    public static function getAllUsers($type = 2) {
        $sql = "SELECT * FROM users WHERE user_type = ?";
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $type);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];

        while ($row = $result->fetch_assoc()) {
            // Use the factory method to create the appropriate user type
            $users[] = self::getUserById($row['id']);
        }

        return $users;
    }

    // Static method to get a user by ID
    public static function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Use the factory method to create the appropriate user type
            return self::createUser($row);
        }

        return null;
    }
}

class AdminUser extends User {
    public function __construct($userData) {
        // Check if the user already exists by email or ID
        $existingUser = isset($userData['id']) ? self::getUserById($userData['id']) : self::getUserByEmail($userData['email']);

        if ($existingUser) {
            // Load the existing user without inserting a new one
            $this->id = $existingUser->id;
            $this->username = $existingUser->username;
            $this->first_name = $existingUser->first_name;
            $this->last_name = $existingUser->last_name;
            $this->email = $existingUser->email;
            $this->password = $existingUser->password;
            $this->country = $existingUser->country;
            $this->userType_obj = $existingUser->userType_obj;
            $this->created_at = $existingUser->created_at;
        } else {
            // Insert a new user if not found
            $this->insertUser($userData);
        }
    }
}

class RegularUser extends User {
    public function __construct($userData) {
        // Check if the user already exists by email or ID
        $existingUser = isset($userData['id']) ? self::getUserById($userData['id']) : self::getUserByEmail($userData['email']);

        if ($existingUser) {
            // Load the existing user without inserting a new one
            $this->id = $existingUser->id;
            $this->username = $existingUser->username;
            $this->first_name = $existingUser->first_name;
            $this->last_name = $existingUser->last_name;
            $this->email = $existingUser->email;
            $this->password = $existingUser->password;
            $this->country = $existingUser->country;
            $this->userType_obj = $existingUser->userType_obj;
            $this->created_at = $existingUser->created_at;
        } else {
            // Insert a new user if not found
            $this->insertUser($userData);
        }
    }
}

?>
