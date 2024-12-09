<?php
include 'Page.php';
// Create connection
$con = new mysqli("localhost", "root", "", "smartnotes_db");

class UserType {
    public $id;
    public $userType_name;
    public $pages_array;

    public function __construct($id) {
        if($id != "") {
            $sql = "SELECT * from user_types where id = $id";
            $result = mysqli_query($GLOBALS['con'], $sql);
            if($row = mysqli_fetch_array($result)) {
                $this->id = $row['id'];
                $this->userType_name = $row['name'];
                $sql = "SELECT page_id FROM usertype_pages WHERE usertype_id = $this->id";
                $result = mysqli_query($GLOBALS['con'], $sql);
                $i = 0;
                while($row1 = mysqli_fetch_array($result)) {
                    $this->pages_array[$i] = new Page($row1[0]);
                    $i++;
                }
            }
        }
    }
}

?>