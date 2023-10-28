<?php
session_start();
if(isset($_GET['mode']) && $_GET['mode'] === 'dark'){
    if($_SESSION['theme'] === true){
        $_SESSION['theme'] = false;
    } else {
        $_SESSION['theme'] = true;
    }
    
}
header("location: ../index.php");
?>
