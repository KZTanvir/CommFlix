<?php
include_once 'dashboard/config/Database.php';
include_once 'dashboard/class/User.php';
include_once 'dashboard/class/Movie.php';
include_once 'dashboard/class/Category.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$movie = new Movie($db);
$category = new Category($db);

if(!$user->loggedIn()) {
	header("location: index.php");
}

$userDetails = $user->getOwner();
$categories = $movie->getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<?php
$siteTitle = "Category | CommFlix @KZTanvir"; 
include "dashboard/inc/head.kz.php";
?>
<body>
    <?php include_once('inc/home_header.kz.php');?>
    <section>
        <div class="card panel-card">
            <div class="panel-heading" style="border-radius: unset; background-color: #F44336; margin-bottom: 8px;"><?php if(isset($_GET['title']) && $_GET['title'] != ''){echo $_GET['title'];}?></div>            
            <div class="movie-container">
                <?php
                if(isset($_GET['title']) && !empty($_GET['title'])) {
                    $movie->homepageQuery = "WHERE p.title LIKE '%".$_GET['title']."%' OR c.name LIKE '%".$_GET['title']."%'";
                    $movies = $movie->getMoviesListing();

                    if (empty($movies)) {
                        echo "<p>No movies available. Please try another one with corrct title</p>";
                    } else {
                        foreach($movies as $mov){
                    ?>
                    <div class="movie">
                        <a href="play.kz.php?mid=<?php echo $mov['id']; ?>"><img src="<?php echo $mov['movie_cover'];?>" alt="<?php echo $mov['title'];?>"></a>
                        <p><?php echo $mov['title'];?></p>
                    </div>

                <?php
                        }
                    }
                } else {
                    echo "<p>No movies available. Please try another one with corrct title</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <?php include "inc/footer.kz.php"; ?>

</body>
</html>


