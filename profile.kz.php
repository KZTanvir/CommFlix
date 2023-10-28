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

$userDetails = $user->getUserById(isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : $_SESSION['userid']);
$categories = $movie->getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<?php
$siteTitle = "Profile: {$userDetails['first_name']} | CommFlix @KZTanvir"; 
include "dashboard/inc/head.kz.php";
?>
<body>
    <?php include_once('inc/home_header.kz.php');?>
    <section>
        <div class="card panel-card">
            <div class="panel-heading" style="border-radius: unset; background-color: #F44336; margin-bottom: 8px;">User Profile</div>            
            <?php
            if($userDetails['type'] != 1 || $_SESSION['userid'] == $userDetails['id']) {
            ?>
            <div class="profile">    
                <img src="<?php echo isset($userDetails['profile_link']) ? $userDetails['profile_link'] : 'images/admin_image.jpg'; ?>" alt="Admin Image">
            </div>
            <br>
            <div class="user-details">
                <div class="detail">
                    <span class="label">User Name</span>
                    <span class="value"><?php echo $userDetails['first_name']. " " .$userDetails['last_name'];?></span>
                </div>
                <div class="detail">
                    <span class="label">User Rank</span>
                    <?php
                        if($userDetails['type'] == 1){
                            echo '<span class="Administrator">Administrator';
                        } else if($userDetails['type'] == 2){
                            echo '<span class="Admin">Admin';
                        } else {
                        echo '<span class="User">Normal User';
                        }
                    ?></span>
                </div>
                <div class="detail">
                    <span class="label">User Status</span>
                    <span class="value"><?php
                        if($userDetails['deleted'] == 1){
                            echo 'Banned/Disabled';
                        } else {
                        echo 'Active/Enabled';
                        }
                    ?></span>
                </div>
                <?php if($_SESSION['user_type'] != 3){?>
                <br>
                <div class="detail">
                    <span class="label">User Edit</span>
                    <span class="value">
                        <a href="dashboard/add_users.kz.php?id=<?php echo $userDetails['id'];?>" class="edit">Edit</a>
                    </span>
                </div>
                <br>
                <div class="detail">
                    <span class="label">User Delete</span>
                    <span class="value">
                        <a class="delete" href="dashboard/add_users.kz.php?id=<?php echo $userDetails['id'];?>&state=delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </span>
                </div>
                <br>
                <div class="detail">
                    <span class="label">Password Reset</span>
                    <span class="value">
                        <a style="background:aqua; color:black;" href="dashboard/reset_user_pass.kz.php?id=<?php echo $userDetails['id'];?>" class="edit">Reset</a>
                    </span>
                </div>
                <?php }?>
            </div>
            <?php } else { 
                       echo '<div class="save-msg">Unauthorized Access!</div>'; 
            }?>
        </div>
    </section>

    <?php include "inc/footer.kz.php"; ?>

</body>
</html>


