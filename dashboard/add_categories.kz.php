<?php
include_once 'config/Database.php';
include_once 'class/User.php';
include_once 'class/Movie.php';
include_once 'class/Category.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$movie = new Movie($db);
$category = new Category($db);

if(!$user->loggedIn()) {
	header("location: index.php");
}

$category = new Category($db);

$category->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';

$saveMessage = '';

$categoryDetails = $category->getCategory();
?>
<!DOCTYPE html>
<html lang="en">
<?php
$siteTitle = "Add Categories | CommFlix @KZTanvir"; 
include "inc/head.kz.php";
?>
<body>

    <?php include "inc/header.kz.php"; ?>

    <section>
        <div class="card panel-card">            
            <div class="panel-heading">Category Settings</div>
            
            <?php
            if($_SESSION['user_type'] !=3 || $_SESSION['userid'] == $user->id) {   
                if(isset($_GET["state"]) && $_GET["state"]=="delete") {
                    if($category->delete()) {
                    $saveMessage = "Category deleted successfully!";
                    } else {
                        $saveMessage = "Error deleting category.";
                    }
                }
                if(!empty($_POST["categorySave"]) && $_POST["categoryName"]!='') {
	                $category->name = $_POST["categoryName"];	
	                if($category->id) {			
		                if($category->update()) {
			                $saveMessage = "Category updated successfully!";
		                } else {
			                $saveMessage = "Category Already Exists!";
		                }
	                } else {			
		                $lastInserId = $category->insert();
		                if($lastInserId) {
			                $category->id = $lastInserId;
			                $saveMessage = "Category saved successfully!";
		                }
	                }
                }
                
                if ($saveMessage != '') { 
                    echo '<div class="save-msg">'.$saveMessage.'</div>'; 
                }
                if(!isset($_GET["state"]) && !isset($_POST["categorySave"])) { 
            ?>
                                   
            <form method="post">
                <label for="title">Category Name</label>
                <input type="text" name="categoryName" value="<?php 
                                                                if (isset($categoryDetails['name']) && $categoryDetails['name']) {
                                                                    echo $categoryDetails['name']; } ?>" 
                                                            placeholder="Category Name..">
                				
                <input type="submit" name="categorySave" class="btn btn-info" value="Save"/>	
            </form>
            <?php }} else { 
                       echo '<div class="save-msg">Unauthorized Access!</div>'; 
            }?>
        </div>
    </section>

    <?php include "inc/footer.kz.php"; ?>

</body>
</html>

