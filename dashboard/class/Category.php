<?php
class Category { 
	
	private $categoryTable = 'cms_category';	
	private $conn;
	
	public $id = null;
	public $name = null;
	
	public function __construct($db){
        $this->conn = $db;
    }	
	
	public function getCategoryListing(){	
		
		$sqlQuery = "
			SELECT id, name
			FROM ".$this->categoryTable."  
			 ";

		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();	
		
		$stmtTotal = $this->conn->prepare("SELECT * FROM ".$this->categoryTable);
		$stmtTotal->execute();
		$allResult = $stmtTotal->get_result();
		$allRecords = $allResult->num_rows;		
		
		$displayRecords = $result->num_rows;
		$categories = array();		
		while ($category = $result->fetch_assoc()) { 				
			$rows = array();				
			$rows[] = $category['id'];
			$rows[] = $category['name'];				
			if ($_SESSION['user_type'] == 3) {
                $rows[] = "None";
                $rows[] = "None";
            } else {
                $rows[] = '<a href="add_categories.kz.php?id='.$category["id"].'" class="edit">Edit</a>';
			    $rows[] = '<a class="delete" href="add_categories.kz.php?id='.$category["id"].'&state=delete" onclick="return confirm(\'Are you sure you want to delete this category?\')">Delete</a>';
            }
			$categories[] = $rows;
		}
		
		$output = array(		
			"iTotalRecords"	=> 	$displayRecords,
			"iTotalDisplayRecords"	=>  $allRecords,
			"data"	=> 	$categories
		);
		
		return json_encode($output);	
	}
	
	
	
	
	public function getCategory(){		
		if($this->id) {
			$sqlQuery = "
			SELECT id, name
			FROM ".$this->categoryTable." 			
			WHERE id = ? ";
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->id);	
			$stmt->execute();
			$result = $stmt->get_result();
			$category = $result->fetch_assoc();
			return $category;
		}		
	}
	
	public function insert(){
		
		if($this->name && $_SESSION['user_type'] != 3) {

			$stmt = $this->conn->prepare("
				INSERT INTO ".$this->categoryTable."(`name`)
				VALUES(?)");
		
			$this->name = htmlspecialchars(strip_tags($this->name));						
			$stmt->bind_param("s", $this->name);
			
			if($stmt->execute()){
				return $stmt->insert_id;
			}		
		}
	}
	
	public function update(){
		
		if($this->id && $_SESSION['user_type'] != 3 && $this->categoryExists($this->id)) {			
			$stmt = $this->conn->prepare("
				UPDATE ".$this->categoryTable." 
				SET name= ?
				WHERE id = ?");
	 
			$this->id = htmlspecialchars(strip_tags($this->id));
			$this->name = htmlspecialchars(strip_tags($this->name));			
			
			$stmt->bind_param("si", $this->name, $this->id);
			
			if($stmt->execute()){
				return true;
			}			
		}
		return false;
		
	}
	
	public function delete(){
		
		if($this->id && $_SESSION['user_type'] != 3 && $this->categoryExists($this->id)) {	
		
			$stmt = $this->conn->prepare("
				DELETE FROM ".$this->categoryTable." 				
				WHERE id = ?");

			$this->id = htmlspecialchars(strip_tags($this->id));

			$stmt->bind_param("i", $this->id);

			if($stmt->execute()){
				return true;
			}
		}
		return false;
	}
	
	public function categoryExists($id) {
        $sql = "SELECT COUNT(*) FROM {$this->categoryTable} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count > 0;
    }
	public function totalCategory(){		
		$sqlQuery = "SELECT * FROM ".$this->categoryTable;			
		$stmt = $this->conn->prepare($sqlQuery);			
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;	
	}	
}
?>
