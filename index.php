<?php
include_once 'dashboard/config/Database.php';
include_once 'dashboard/class/email.php';
include_once 'dashboard/class/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if($user->loggedIn()) {
	header("location: home.kz.php");
}

$loginMessage = '';
if(!empty($_POST["login"]) && $_POST["email"]!=''&& $_POST["password"]!='') {	
	$user->email = $_POST["email"];
	$user->password = $_POST["password"];
	$login = $user->login();
	if($login == 1) {
		header("location: home.kz.php");
	} else if($login == 2) {
		$loginMessage = 'Account Locked/Banned!';
	} else {
	    $loginMessage = 'Invalid login! Please try again.';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
$siteTitle = "Login | CommFlix @KZTanvir"; 
include "dashboard/inc/head.kz.php";
?>
<body>

    <?php include "inc/header.kz.php"; ?>

    <section>        
        <div class="login-box">
            <div class="panel-heading">Login</div>
            <?php 
            if ($loginMessage != '') { 
                echo '<div class="save-msg">'.$loginMessage.'</div>'; 
            }?>
            <div class="panel-body">
                <form id="loginform" method="POST" action="">
                    <div class="input-group">
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="login" value="Login" class="btn btn-info">
                        <a href="register.kz.php" class="btn btn-reg">Apply</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php include "inc/footer.kz.php"; ?>

</body>
</html>
