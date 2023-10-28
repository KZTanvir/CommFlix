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
?>
<!DOCTYPE html>
<html lang="en">
<?php
$siteTitle = "Movies | CommFlix @KZTanvir"; 
include "inc/head.kz.php";
?>
<body>

    <?php include "inc/header.kz.php"; ?>

    <section>
        <div class="card panel-card">
            <div class="panel-heading">All Movies</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Movie Name</th>
                            <th>Genere</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <?php
                    $movies = $movie->getMoviesListing();

                    foreach ($movies as $movie) {
                        echo '<tr>' . "\n";
                        echo '<td>' . $movie['title'] . '</td>' . "\n";
                        echo '<td>' . $movie['name'] . '</td>' . "\n";
                        if ($_SESSION['user_type'] == 3) {
                            echo '<td>---</td>' . "\n";
                        } else {
                            echo '<td>' . $movie['first_name'] . ' ' . $movie['last_name'] . '</td>' . "\n";
                        }
                        echo '<td>' . $movie['status'] . '</td>' . "\n";
                        echo '<td>' . $movie['created'] . '</td>' . "\n";
                        echo '<td>' . $movie['updated'] . '</td>' . "\n";
                        
                        if ($_SESSION['user_type'] == 3) {
                            echo '<td>None</td>' . "\n";
                            echo '<td>None</td>' . "\n";
                        } else {
                            echo '<td><a class="edit" href="add_movie.kz.php?id='.$movie["id"].'">Edit</a></td>';
                            echo '<td><a class="delete" href="add_movie.kz.php?id='.$movie["id"].'&state=delete" onclick="return confirm(\'Are you sure you want to delete this movie?\')">Delete</a></td>';
                        }
                        echo '</tr>' . "\n";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="add">
                <a href="add_movie.kz.php" class="btn btn-info">Add Movies</a>
            </div>
        </div>
    </section>

    <?php include "inc/footer.kz.php"; ?>

</body>
</html>





