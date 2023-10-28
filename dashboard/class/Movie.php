<?php
class Movie {	
   
	private $movieTable = 'cms_movies';
	private $categoryTable = 'cms_category';
	private $userTable = 'cms_user';	
	private $conn;
	
	public $id = null;
	public $title = null;
	public $movie_cover = null;
	public $movie_file = null;
	public $category = null;
	public $status = null;
	public $userid = null;
	public $created = null;
	public $updated = null;

	public $homepageQuery = null;
	
	public function __construct($db){
        $this->conn = $db;
    }	
	
	public function getMoviesListing(){		
				
		$sqlQuery = "
			SELECT p.id, p.title, p.movie_file, p.movie_cover, p.category_id, u.first_name, u.last_name, p.status, p.created, p.updated, c.name 
			FROM ".$this->movieTable." p
			LEFT JOIN ".$this->categoryTable." c ON c.id = p.category_id
			LEFT JOIN ".$this->userTable." u ON u.id = p.userid
			$this->homepageQuery";

		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$movies = array();
		while ($movie = $result->fetch_assoc()) { 				
			$movies[] = $movie;
		}
		
		return $movies;	
	}
	
	public function getMovie(){		
		if($this->id) {
			$sqlQuery = "
				SELECT p.id, p.title, p.movie_file, p.movie_cover, p.category_id, p.status, p.created, p.updated, c.name 
				FROM ".$this->movieTable." p
				LEFT JOIN ".$this->categoryTable." c ON c.id = p.category_id
				WHERE p.id = ? ";
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->id);	
			$stmt->execute();
			$result = $stmt->get_result();
			$movie = $result->fetch_assoc();
			return $movie;
		}	
	}
	
	
	
	
	public function insert(){
		
		if($this->title && $this->movie_cover && $this->movie_file && $this->status && $_SESSION['user_type'] != 3) {
		    
			$stmt = $this->conn->prepare("
				INSERT INTO ".$this->movieTable."(`title`, `movie_file`, `movie_cover`, `category_id`, `userid`, `status`, `created` , `updated`)
				VALUES(?,?,?,?,?,?,?,?)");
		
			$this->title = htmlspecialchars(strip_tags($this->title));
			$this->movie_file = htmlspecialchars(strip_tags($this->movie_file));
			$this->movie_cover = htmlspecialchars(strip_tags($this->movie_cover));
			$this->category = htmlspecialchars(strip_tags($this->category));
			$this->userid = htmlspecialchars(strip_tags($this->userid));
			$this->status = htmlspecialchars(strip_tags($this->status));
			$this->created = htmlspecialchars(strip_tags($this->created));		
			$this->updated = htmlspecialchars(strip_tags($this->updated));			
			
			$stmt->bind_param("sssiisss", $this->title, $this->movie_file, $this->movie_cover, $this->category, $this->userid, $this->status, $this->created, $this->updated);
			
			if($stmt->execute()){
				return $stmt->insert_id;
			}		
		}
	}
	
    public function updateMovie(){
        if (!$this->id || $_SESSION['user_type'] == 3) {
            return false;
        }
        if (!$this->movieExists($this->id)) {
            return false; //for later update give custom error messages
        }
            
        $oldmovie = $this->getMovie();
        
        //if old and new file are not same then delete the files
        $this->deleteOldFiles($oldmovie['movie_file'], $this->movie_file);
        $this->deleteOldFiles($oldmovie['movie_cover'], $this->movie_cover);
        
        $stmt = $this->conn->prepare("
            UPDATE " . $this->movieTable . " 
            SET title = ?, movie_file = ?, movie_cover = ?, category_id = ?, status = ?, updated = ?
            WHERE id = ?"
        );
        
		$this->movie_file = htmlspecialchars(strip_tags($this->movie_file));
		$this->movie_cover = htmlspecialchars(strip_tags($this->movie_cover));
		
        $this->id = htmlspecialchars(strip_tags($this->id));
		$this->title = htmlspecialchars(strip_tags($this->title));
		$this->category = htmlspecialchars(strip_tags($this->category));
		$this->status = htmlspecialchars(strip_tags($this->status));
		$this->updated = htmlspecialchars(strip_tags($this->updated));	

        $stmt->bind_param("sssissi", $this->title, $this->movie_file, $this->movie_cover, $this->category, $this->status, $this->updated, $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function deleteOldFiles($oldFile, $newFile)
    {
        if ($oldFile != $newFile) {
            $this->deleteFile('../'.$oldFile);
            return true;
        }
        return false;
    }

    private function deleteFile($filePath)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    
	
    public function movieExists($movieId) {
        $sql = "SELECT COUNT(*) FROM {$this->movieTable} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $movieId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count > 0;
    }
	
	
	public function delete(){
		
		if($this->id && $_SESSION['user_type'] !=3 && $this->movieExists($this->id)) {
			$oldMovie = $this->getMovie();
			if(!$this->deleteOldFiles($oldMovie['movie_cover'], "x")){
				echo "Error deleting movie cover file.";
			}
			if(!$this->deleteOldFiles($oldMovie['movie_file'], "x")){
				echo "Error deleting movie file.";
			}
			$stmt = $this->conn->prepare("
				DELETE FROM ".$this->movieTable." 				
				WHERE id = ?");

			$this->id = htmlspecialchars(strip_tags($this->id));

			$stmt->bind_param("i", $this->id);

			if($stmt->execute()){
				return true;
			}
		}
		return false;
	}
	
	public function getCategories(){		
		$sqlQuery = "
			SELECT id, name 
			FROM ".$this->categoryTable;
		
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();			
		
		$categories = $result->fetch_all(MYSQLI_ASSOC);
		
		return $categories;	
	}
	
	
	public function totalMovie(){		
		$sqlQuery = "SELECT * FROM ".$this->movieTable;			
		$stmt = $this->conn->prepare($sqlQuery);			
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;	
	}	
}
?>
