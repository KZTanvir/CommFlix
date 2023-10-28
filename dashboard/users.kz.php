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
$siteTitle = "Users | CommFlix @KZTanvir"; 
include "inc/head.kz.php";
?>
<body>

    <?php include "inc/header.kz.php"; ?>

    <section>
        <div class="card panel-card">
            <div class="panel-heading">All Users</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            <?php if($_SESSION['user_type']===1){echo '<th>Reset</th>';}?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $users = $user->getUsersListing();

                    foreach ($users as $user) {
                        echo '<tr>' . "\n";
                        echo '<td><a href="../profile.kz.php?id=' . $user['id'] . '">' . $user['user_name'] . '</a></td>' . "\n"; // Name
                        echo '<td>' . $user['email'] . '</td>' . "\n"; // Email
                        echo '<td class="' . $user['type'] . '">' . $user['type'] . '</td>' . "\n"; // Type
                        echo '<td>' . $user['status'] . '</td>' . "\n"; // Status
                        
                        echo '<td>'. $user['edit'] .'</td>' . "\n"; // Edit
                        echo '<td>'. $user['delete'] .'</td>' . "\n"; // Delete
                        //reset pass authorization
                        if($_SESSION['user_type']===1){
                            echo '<td><a style="background:aqua; color:black;" href="reset_user_pass.kz.php?id=' . $user['id'] . '" class="edit">Reset</a></td>' . "\n"; // Reset
                        }
                        echo '</tr>' . "\n";
                    }
                    ?>

                    </tbody>
                </table>
            </div>
            <div class="add">
                <a href="add_users.kz.php" class="btn btn-info">Add Users</a>
            </div>
        </div>
    </section>

    <?php include "inc/footer.kz.php"; ?>

</body>
</html>





