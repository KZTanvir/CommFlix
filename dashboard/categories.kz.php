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
$siteTitle = "Categories | CommFlix @KZTanvir"; 
include "inc/head.kz.php";
?>
<body>

    <?php include "inc/header.kz.php"; ?>

    <section>
        <div class="card panel-card">
            <div class="panel-heading">Movie Category</div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                <?php $data = json_decode($category->getCategoryListing(), true);
                    foreach ($data['data'] as $row) {
                        echo "<tr>\n";
                        foreach ($row as $cell) {
                            echo "<td class=\"actions\">$cell</td>\n";
                        }
                        echo '</tr>';
                        echo "\n";
                    }
                ?>
                </tbody>
            </table>
            <div class="add">
                <a href="add_categories.kz.php" class="btn btn-info">Add Categories</a>
            </div>
        </div>
    </section>

    <?php include "inc/footer.kz.php"; ?>

</body>
</html>





