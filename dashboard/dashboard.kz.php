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
$userDetails = $user->getUserById($_SESSION["userid"]);
?>
<!DOCTYPE html>
<html lang="en">
<?php
$siteTitle = "Dashboard | CommFlix @KZTanvir"; 
include "inc/head.kz.php";
?>
<body>

    <?php include "inc/header.kz.php"; ?>

    <section>
        <?php if(!empty($_SESSION["userid"])) { ?>
        <h2>Welcome <b><?php echo $_SESSION["name"]; ?></b></h2>
        
	    <?php } ?>

        <div class="tiles">
            <div class="tile">
                <h3>Total Users</h3>
                <p><?php echo $user->totalUser(); ?></p>
            </div>

            <div class="tile">
                <h3>Category</h3>
                <p><?php echo $category->totalCategory(); ?></p>
            </div>

            <div class="tile">
                <h3>Total Movies</h3>
                <p><?php echo $movie->totalMovie();?></p>
            </div>
        </div>
    </section>
    <?php include "inc/footer.kz.php"; ?>

</body>
</html>

