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
$siteTitle = "Home | CommFlix @KZTanvir"; 
include "dashboard/inc/head.kz.php";
?>
<body>
    <?php include 'inc/home_header.kz.php';?>
    <section>
        <div class="card panel-card">
            <div class="panel-heading">Upcoming Movies</div>
            <div class="movie-container">
                <?php
                $movie->homepageQuery = "WHERE p.status = 'upcoming' ORDER BY p.updated DESC LIMIT 10";
                $movies = $movie->getMoviesListing();

                if (empty($movies)) {
                    echo "<p>No movies available in this category.</p>";
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
                ?>
            </div>
        </div>
    </section>
    <section  style="margin-left:unset;">
        <div class="panel-heading" style="border-radius: unset; background-color: #F44336; margin-bottom: 8px;">Latest Uploaded</div>
        <?php
            foreach($categories as $category){
        ?>
        <div class="card panel-card">
            <div class="panel-heading"><?php echo $category['name'];?></div>
            <div class="movie-container">
                <?php
                $categoryID = $category['id'];
                $movie->homepageQuery = "WHERE p.status = 'published' AND p.category_id = $categoryID ORDER BY p.updated DESC LIMIT 10";
                $movies = $movie->getMoviesListing();

                if (empty($movies)) {
                    echo "<p>No movies available in this category.</p>";
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
                ?>
            </div>
        </div>
        <?php }?>
    </section>

    <?php include "inc/footer.kz.php"; ?>

</body>
</html>


