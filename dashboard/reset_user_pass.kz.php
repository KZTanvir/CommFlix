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

if(!$user->loggedIn()) {
	header("location: dashboard.kz.php");
}

$saveMessage = '';

$userDetails = $user->getUserById(isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : '0');

?>
<!DOCTYPE html>
<html lang="en">
<?php
$siteTitle = "Reset Password | CommFlix @KZTanvir"; 
include "inc/head.kz.php";
?>
<body>

    <?php include "inc/header.kz.php"; ?>

    <section>
        <div class="card panel-card">
                       
            <div class="panel-heading">Reset Password</div>
            
            <?php
                if($_SESSION['user_type'] == 1 && isset($_GET['id']) && $_SESSION['userid'] != $_GET['id']) {
                    $registerMessage = '';
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resetPassword'])) {
                        $user->email = $userDetails['email'];
                        $res =  $user->resetPass();
                        if ($res == 1) {
                            $registerMessage = "Password Reset Successfull.";
                        } else {
                            $registerMessage = "Password reset failed! Please try again.";
                        }
                    }
                    if ($registerMessage != '') {
                        echo '<div class="save-msg">'.$registerMessage.'</div>';
                    } ?>
            
            <div class="save-msg" style="background:unset; color:unset; font-weight:unset;">
                Send an email and reset this user's password?<br>
                <i>Email: <?php echo isset($userDetails['email']) ? $userDetails['email'] : ''; ?></i>
            </div>
            
            <form method="post">                
                <input type="submit" name="resetPassword" class="btn btn-info" value="Reset Password"/>	
            </form>
            <?php
            } else { 
                echo '<div class="save-msg" style="background:unset; color:unset; font-weight:unset; font-size:18px;"><i>Go to change password to reset yours.</i>
            </div>';
                echo '<div class="save-msg">Unauthorized Access!</div>'; 
            }
            ?>
        </div>
    </section>

    <?php include "inc/footer.kz.php"; ?>

</body>
</html>

