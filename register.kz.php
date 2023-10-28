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
?>
<!DOCTYPE html>
<html lang="en">
<?php
$siteTitle = "Apply for Registration | CommFlix @KZTanvir"; 
include "dashboard/inc/head.kz.php";
?>
<body>

    <?php include "inc/header.kz.php"; ?>

    <section>        
        <div class="login-box">
            <div class="panel-heading">Registration Request</div>
                
                    <?php
                    $registerMessage = '';
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
                        $user->first_name = $_POST['first_name'];
                        $user->last_name = $_POST['last_name'];
                        $user->email = $_POST['email'];
                        $regn =  $user->register();
                        if ($regn == 1) {
                            $registerMessage = "Application Successful.<br>You are in the queue.<br>Please wait for 24h to account activation.";
                        } else if($regn == 2) {
                            $registerMessage = "Email already exists!!<br>[DUPLICATE NOT ALLOWED]";
                        } else {
                            $registerMessage = "Registration failed! Please try again.";
                        }
                    }
                    if ($registerMessage != '') {
                        echo '<div class="save-msg">'.$registerMessage.'</div>';
                    } ?>
            <div class="panel-body">
                <form id="loginform" method="POST" action="">
                    <div class="input-group">
                        <input type="text" class="form-control" name="first_name" placeholder="First Name" required>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" name="last_name" placeholder="Last_Name" required>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="register" value="Submit" class="btn btn-info">
                        <a href="index.php" class="btn btn-reg">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php include "inc/footer.kz.php"; ?>

</body>
</html>
