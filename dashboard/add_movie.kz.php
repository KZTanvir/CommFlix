<?php
include_once 'config/Database.php';
include_once 'class/User.php';
include_once 'class/Chat.php';
include_once 'class/Movie.php';
include_once 'class/Category.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$chat = new Chat($db);
$movie = new Movie($db);
$category = new Category($db);

if(!$user->loggedIn()) {
	header("location: index.php");
}

$categories = $movie->getCategories();

$movie->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';
$saveMessage = '';

$movieDetails = $movie->getMovie();
?>
<!DOCTYPE html>
<html lang="en">
<?php
$siteTitle = "Add Movies | CommFlix @KZTanvir"; 
include "inc/head.kz.php";
?>
<body>

    <?php include "inc/header.kz.php"; ?>

    <section>
        <div class="card panel-card">            
            <div class="panel-heading">Movie Settings</div>
            <?php 
            if($_SESSION['user_type'] !=3 || $_SESSION['userid'] == $user->id) {
                if(isset($_GET["state"]) && $_GET["state"]=="delete") {
                    if($movie->delete()) {
                        $saveMessage = "Movie deleted successfully!";                        
                    } else {
                        $saveMessage = "Error deleting Movie.";
                    }
                }
                if(!empty($_POST["upload"]) && $_POST["title"]!='' && isset($_POST["upload"])) {
                    $movieUp = false;
                    $coverUp = false;
                    $movie->title = $_POST["title"];
	                $movie->category = $_POST["category"];
	                $movie->status = $_POST["status"];
                    if($_FILES['movie_cover']['error'] === 4){
                        $movie->movie_cover = $movieDetails['movie_cover'];
                    } else {
                        $coverFileName = uniqid() . '_' . basename($_FILES['movie_cover']['name']);
                        $targetCover = "../media/covers/" . $coverFileName;
                        $movie->movie_cover = "media/covers/" . $coverFileName;                        
	    	            $coverUp = move_uploaded_file($_FILES['movie_cover']['tmp_name'], $targetCover);
                    }
                    if($_FILES['movie_file']['error'] === 4){
                        $movie->movie_file = $movieDetails['movie_file'];
                    } else {
                        $movieFileName = uniqid() . '_' . basename($_FILES['movie_file']['name']);
                        $targetMovie = "../media/movies/" . $movieFileName;
                        $movie->movie_file = "media/movies/" . $movieFileName;
                        $movieUp = move_uploaded_file($_FILES['movie_file']['tmp_name'], $targetMovie);    
                    }
	                if($movie->id) {	
	    	            $movie->updated = date('Y-m-d H:i:s');
	    	            if($movie->updateMovie()) {
	    		            $saveMessage = "Movie Update Successfully.";	    		            
	    	            } else {
	    	                $saveMessage = "Movie Update Error!";
	    	            }
	                } else {
	    	            $movie->userid = $_SESSION["userid"];
	    	            $movie->created = date('Y-m-d H:i:s'); 
	    	            $movie->updated = date('Y-m-d H:i:s');
	    	            if($movieUp && $coverUp) { 	
	    	                $lastInserId = $movie->insert();
	    		            $movie->id = $lastInserId;
	    		            $saveMessage = "Movie saved successfully!";
                            //adding new movie in the global chat code.
                            $chat->sender_id = 1;
                            $chat->sendMessage("New movie added: " . $movie->title . ' Category: ' . $movie->category . ' Status: ' . $movie->status);
	    	            } else {
	    	                $saveMessage = "Movie Saving Error!";
	    	            }
	                }
                }
                if(!$categories){
                    $saveMessage = "Please add category first!";
                }
                if ($saveMessage != '') { 
                    echo '<div class="save-msg">'.$saveMessage.'</div>'; 
                    exit();
                }
                if(!isset($_GET["state"]) && !isset($_POST["upload"])) { 
            ?>
            
            
            <form method="post" enctype="multipart/form-data">
                <label for="title">Movie Title</label>
                <input type="text" name="title" value="<?php echo isset($movieDetails['title']) ? $movieDetails['title'] : ''; ?>" placeholder="Movie title..">

                <label for="cover">Movie Cover</label>
                <input type="file" name="movie_cover">

                <label for="file">Movie File</label>
                <input type="file" name="movie_file">

                <label for="category">Category</label>
                <select class="form-control" name="category">
                <?php
                foreach ($categories as $category) {
                    $selected = isset($movieDetails['name']) && $category['name'] == $movieDetails['name'] ? 'selected=selected' : '';
                    echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
                }
                ?>

                </select>

                <div class="form-group">
                    <label>
                        <input type="radio" name="status" value="published" <?php if (isset($movieDetails['status']) && $movieDetails['status'] == 'published') {echo "checked";} ?>>Publish
                    </label>
                    <label>
                        <input type="radio" name="status" value="upcoming" <?php if (isset($movieDetails['status']) && $movieDetails['status'] == 'upcoming') {echo "checked";} ?>>Upcoming
                    </label>
                    <label>
                        <input type="radio" name="status" id="archived" value="archived" <?php if (isset($movieDetails['status']) && $movieDetails['status'] == 'archived') {echo "checked";} ?>>Archive
                    </label>
                </div>

                <input type="submit" name="upload" class="btn btn-info" value="upload"/>
            </form>

            <?php 
                }
            } else { 
                       echo '<div class="save-msg">Unauthorized Access!</div>'; 
            }?>
        </div>
    </section>

    <?php include "inc/footer.kz.php"; ?>

</body>
</html>

