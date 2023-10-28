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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sendCode'])) {
    $email = $user->getOwner()['email'];

    if ($user->sendConfirmationEmail($email)) {
        $saveMessage = "Confirmation code sent successfully!";
    } else {
        $saveMessage = "Failed to send confirmation code. Please check your email address.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changePassword'])) {
    $confirmationCode = $_POST['confirmation_code'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    if($newPassword === $confirmPassword){
        if ($user->changePassword($user->getOwner()['id'],$confirmationCode, $newPassword)) {
            $saveMessage = "Password changed successfully!";
        } else {
            $saveMessage = "Password change failed.<br>Please check your confirmation code and try again.";
        }
    } else {
        $saveMessage = "Make sure to enter password correctly.";
    }
}

$userDetails = $user->getUserById($_SESSION['userid']);

?>
<!DOCTYPE html>
<html lang="en">
<?php
$siteTitle = "Change Password | CommFlix @KZTanvir"; 
include "inc/head.kz.php";
?>
<body>

    <?php include "inc/header.kz.php"; ?>

    <section>
        <div class="card panel-card">
                       
            <div class="panel-heading">Change Password</div>
            
            <?php 
            if ($saveMessage != '') { 
                echo '<div class="save-msg">'.$saveMessage.'</div>'; 
            }
            if(!isset($_POST['sendCode']) && !isset($_POST['changePassword'])){
            ?>
            
            <div class="save-msg" style="background:unset; color:unset; font-weight:unset;">
                Send a confirmation code to your email to change password.<br>
                <i>Email: <?php echo isset($userDetails['email']) ? $userDetails['email'] : ''; ?></i>
            </div>
            <form method="post">
                <input type="submit" name="sendCode" class="btn btn-info" value="Send Confirmation"/>	
            </form>
            <?php 
            }
            if(isset($_POST['sendCode']) || (isset($_POST['confirm_password']) && $_POST['confirm_password'] != $_POST['new_password'])){
            ?>
            <form method="post">
                <label for="title">Confirmation Code</label>
                <input type="text" name="confirmation_code" placeholder="Confirmation Code...">
                         
                <label for="title">Enter Password</label>
                <input type="password" name="new_password" placeholder="Password..">
                
                <label for="title">Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm Password..">
                
                <input type="submit" name="changePassword" class="btn btn-info" value="Change Password"/>	
            </form>
            <?php
            }
            ?>
        </div>
    </section>

    <?php include "inc/footer.kz.php"; ?>

</body>
</html>

