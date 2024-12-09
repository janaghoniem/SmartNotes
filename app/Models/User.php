<?php
require_once __DIR__ . '/../Config/Database.php';
include 'UserType.php';
include 'UserActivity.php';

// Create connection
$con = new mysqli("localhost", "root", "", "smartnotes_db");

class User {
    public $id;
    public $username;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $country;
    public $userType_obj;
    public $created_at;

    public function __construct($user_id = null) {
        if ($user_id) {
            $this->loadUser($user_id);
        }
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
    
                return $user; 
            }
        }
        return null;
    }

    static function getAllUsers() {
        $sql = "SELECT * FROM users WHERE user_type = 2";
        $users = mysqli_query($GLOBALS['con'], $sql);
        $result = [];
        while($row = mysqli_fetch_array($users)) {
            $userObj = new User($row["id"]);
            $result[] = $userObj;
        }
        return $result;
    }

    static function getAllAdmins() {
        $sql = "SELECT * FROM users WHERE user_type = 1";
        $admins = mysqli_query($GLOBALS['con'], $sql);
        $result = [];
        while($row = mysqli_fetch_array($admins)) {
            $adminObj = new User($row["id"]);
            $result[] = $adminObj;
        }
        return $result;
    }

    static function deleteUser($objUser) {
        $sql = "DELETE from users WHERE id = ".$objUser->id;
        if(mysqli_query($GLOBALS['con'], $sql)) {
            return true;
        }
        else 
            return false;
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
        $sql = "UPDATE users SET 
            username = '$this->username',
            first_name = '$this->first_name', 
            last_name = '$this->last_name', 
            email = '$this->email', 
            password = '$this->password', 
            country = '$this->country', 
            user_type = '".$this->userType_obj->id."' 
            WHERE id = $this->id";
        return mysqli_query($GLOBALS['con'], $sql);
    }

    static function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = $id";
        $result = mysqli_query($GLOBALS['con'], $sql);
        
        if ($row = mysqli_fetch_array($result)) {
            return new User($row["id"]);
        }
        return null;
    }

    static function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($GLOBALS['con'], $sql);
        if ($row = mysqli_fetch_array($result)) {
            return new User($row["id"]);
        }
        return null;
    }
}
?>