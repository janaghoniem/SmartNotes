<?php
// Create connection
$con = new mysqli("localhost", "root", "", "smartnotes_db");

class Page {
    public $id;
    public $friendly_name;
    public $link_address;
    public $link_icon;
    public $visible;

    // function __construct($id) {
    //     if($id != "") {
    //         $sql = "SELECT * FROM pages WHERE id = $id";
    //         $result = mysqli_query($GLOBALS['con'], $sql);
    //         if($row = mysqli_fetch_array[$result]) {
    //             $this->id = $row['id'];
    //             $this->friendly_name = $row['friendly_name'];
    //             $this->link_address = $row['link_address'];
    //         }
    //     }
    // }
    function __construct($id){
		if ($id !=""){	
			$sql="select * from pages where ID=$id";
			$result2=mysqli_query($GLOBALS['con'],$sql) ;
			if ($row2 = mysqli_fetch_array($result2)){
				$this->friendly_name=$row2["friendly_name"];
				$this->link_address=$row2["link_address"];
				$this->id=$row2["id"];
                $this->link_icon=$row2["link_icon"];
                $this->visible = $row2['visible'];
			}
		}
	}
    // static function selectAllPagesInDB() {
    //     $sql = "SELECT * FROM pages";
    //     $pageDataset = mysqli_query($GLOBALS['con'], $sql);
    //     $i = 0;
    //     $result;
    //     while($row = mysqli_fetch_array($pageDataset)) {
    //         $pageObj = new Page($row['id']);
    //         $result[$i] = $pageObj;
    //         $i++;
    //     }
    //     return $result;
    // }
}

?>