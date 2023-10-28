<?php
include_once 'config/Database.php';
include_once 'class/User.php';
include_once 'class/Chat.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$chat = new Chat($db);

if(!$user->loggedIn()) {
    header("location: index.php");
}

$chat->sender_id = $_SESSION['userid'];

if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"]==="POST" && (isset($_POST['send']) || isset($_POST['clean_chat']))) {
    if(isset($_POST['message']) && $_POST['message']!='') {
        $message = $_POST['message'];
        $chat->sendMessage($message);
        //header("location: dashboard.kz.php");
    }
    if(isset($_POST['clean_chat']) && $_POST['clean_chat']=='clean_chat') {
        $chat->cleanChat();
        //header("location: dashboard.kz.php");
    }
} else {
    $chatHistory = $chat->getChatHistory();
    foreach ($chatHistory as $message) {
        echo '<div>';
        
        // Check if variables are set before using them
        $senderId = isset($message['sender_id']) ? htmlspecialchars($message['sender_id']) : 'x';
        $messageContent = isset($message['message']) ? $message['message'] : 'x';
        $senderName = $user->getUserById($senderId);
        
        echo '<strong>' . $senderName['first_name'] . ' ' . $senderName['last_name'] . ' :</strong> ' . $messageContent;
        echo '</div>';
    }
}
?>

