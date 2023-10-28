<?php
include_once 'config/Database.php';
include_once 'class/email.php';
include_once 'class/User.php';
include_once 'class/Movie.php';
include_once 'class/Category.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$movie = new Movie($db);
$category = new Category($db);


if (!$user->loggedIn()) {
    header("location: index.php");
    exit();
}

$saveMessage = '';
$userDetails = $user->getUserById(isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : '0');
$user->id = isset($userDetails['id']) ? $userDetails['id'] : '0';
?>
<!DOCTYPE html>
<html lang="en">
<?php
$siteTitle = "Add Users | CommFlix @KZTanvir"; 
include "inc/head.kz.php";
?>
<body>

    <?php include "inc/header.kz.php"; ?>

    <section>
        <div class="card panel-card">
                       
            <div class="panel-heading">User Settings</div>
            
            <?php
            if($_SESSION['user_type'] != 3 || $_SESSION['userid'] == $user->id) {
                if(isset($_GET["state"]) && $_GET["state"]=="delete") {
                    if($user->delete()) {
                        $saveMessage = "User deleted successfully!";
                    } else {
                        $saveMessage = "Error deleting User.";
                    }
                }
                
                if (!empty($_POST["saveUser"]) && $_POST["email"] != '') {
                    $user->first_name = $_POST["first_name"];
                    $user->last_name = $_POST["last_name"];
                    $user->email = $_POST["email"];
                    $user->profile_link = $_POST["profile_link"];
                    if($_SESSION['user_type'] != 3){
                        $user->type = $_POST["user_type"];
                        $user->deleted = $_POST["user_status"];
                    }
                    

                    if ($user->id) {
                        if ($user->update()) {
                            $saveMessage = "User updated successfully!";
                        }
                    } else {
                        $user->password = $_POST["password"];
                        $lastInsertId = $user->insert();
                        if ($lastInsertId) {
                            $user->id = $lastInsertId;
                            $saveMessage = "User saved successfully!";
                        }
                    }
                }
                             
                if ($saveMessage != '') { 
                    echo '<div class="save-msg">'.$saveMessage.'</div>'; 
                }
                if(!isset($_GET["state"]) && !isset($_POST["email"])) { 
            ?>
            <form method="post">
                <div class="profile">    
                    <img src="<?php echo isset($userDetails['profile_link']) ? $userDetails['profile_link'] : 'images/admin_image.jpg'; ?>" alt="Admin Image">
                </div>
                <label for="title">First Name</label>
                <input type="text" name="first_name" value="<?php echo isset($userDetails['first_name']) ? $userDetails['first_name'] : ''; ?>" placeholder="First Name..">

                <label for="title">Last Name</label>
                <input type="text" name="last_name" value="<?php echo isset($userDetails['last_name']) ? $userDetails['last_name'] : ''; ?>" placeholder="Last Name..">

                <label for="title">Email</label>
                <input type="email" name="email" value="<?php echo isset($userDetails['email']) ? $userDetails['email'] : ''; ?>" placeholder="Email..">

                <label for="title">Profile Image Link</label>
                <input <?php if ($_SESSION['user_type'] != 1) { ?>type="url"<?php }?> name="profile_link" value="<?php echo isset($userDetails['profile_link']) ? $userDetails['profile_link'] : 'images/admin_image.jpg'; ?>" placeholder="Enter profile image url..">

                <?php if (!$user->id) { ?>
                    <label for="title">Password</label>
                    <input type="password" name="password" value="<?php echo isset($userDetails['password']) ? $userDetails['password'] : ''; ?>" placeholder="Password..">
                <?php } ?>

                <?php if ($_SESSION['user_type'] != 3) { ?>
                    <div class="form-group">
                        <label for="status">User Rank: </label><br>
                        <label>
                            <input type="radio" name="user_type" value="1" <?php echo (isset($userDetails['type']) && $userDetails['type'] == '1') ? "checked" : ""; ?>>Administrator
                        </label>
                        <label>
                            <input type="radio" name="user_type" value="2" <?php echo (isset($userDetails['type']) && $userDetails['type'] == '2') ? "checked" : ""; ?>>Admin
                        </label>
                        <label>
                            <input type="radio" name="user_type" value="3" <?php echo (isset($userDetails['type']) && $userDetails['type'] == '3') ? "checked" : ""; ?>>Normal User
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="status">User Status: </label><br>
                        <label>
                            <input type="radio" name="user_status" value="0" <?php echo (!isset($userDetails['deleted']) || !$userDetails['deleted']) ? "checked" : ""; ?>>Active
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="user_status" value="1" <?php echo (isset($userDetails['deleted']) && $userDetails['deleted']) ? "checked" : ""; ?>>Inactive
                        </label>
                    </div>
                <?php } ?>
                
                <input type="submit" name="saveUser" class="btn btn-info" value="Save"/>    
            </form>

            <?php }} else { 
                       echo '<div class="save-msg">Unauthorized Access!</div>'; 
            }?>
            
        </div>
    </section>

    <?php include "inc/footer.kz.php"; ?>

</body>
</html>

