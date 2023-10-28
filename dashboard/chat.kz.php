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

if (!$user->loggedIn()) {
    header("location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
$siteTitle = "Global Chat | CommFlix @KZTanvir"; 
include "inc/head.kz.php";
?>
<body>
    <?php include "inc/header.kz.php"; ?>
    <section>
        <div class="card panel-card">            
            <div class="panel-heading">Global Chat</div>
            <div class="chat">
            <div id="chatContainer" class="chatContainer"></div>                                  
            <form id="chatForm" onsubmit="return false;" method="post">
                <input type="text" name="message" id="message" size="50" placeholder="Send message..">
                <input type="button" class="btn btn-info" value="send" onclick="sendMessage();"/>
            </form>

            <div class="emoji-buttons">
                <button type="button" onclick="addEmoji('😊')">😊</button>
                <button type="button" onclick="addEmoji('👍')">👍</button>
                <button type="button" onclick="addEmoji('❤️')">❤️</button>
                <button type="button" onclick="addEmoji('😂')">😂</button>
                <button type="button" onclick="addEmoji('🎉')">🎉</button>
                <button type="button" onclick="addEmoji('🔥')">🔥</button>
                <button type="button" onclick="addEmoji('🙌')">🙌</button>
                <button type="button" onclick="addEmoji('🤔')">🤔</button>
                <button type="button" onclick="addEmoji('😎')">😎</button>
                <button type="button" onclick="addEmoji('🚀')">🚀</button>
                <button type="button" onclick="addEmoji('🌟')">🌟</button>
                <button type="button" onclick="addEmoji('😍')">😍</button>
                <button type="button" onclick="addEmoji('💻')">💻</button>
                <button type="button" onclick="addEmoji('🤗')">🤗</button>
                <button type="button" onclick="addEmoji('👏')">👏</button>
                <button type="button" onclick="addEmoji('🎶')">🎶</button>
                <button type="button" onclick="addEmoji('💡')">💡</button>
                <button type="button" onclick="addEmoji('🌈')">🌈</button>
                <button type="button" onclick="addEmoji('🍕')">🍕</button>
                <button type="button" onclick="addEmoji('🚲')">🚲</button>
            </div>


            <?php
            if ($_SESSION['user_type'] == 1) {
                echo '<button class="clean-chat-btn" onclick="cleanChat();">Clean Chat</button>';
            }
            ?>
            </div>
        </div>
    </section>
    <?php include "inc/footer.kz.php"; ?>
    <script>
        function addEmoji(emoji) {
            var messageInput = document.getElementById("message");
            messageInput.value += emoji;
        }
        
        function refreshChat() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("chatContainer").innerHTML = xhr.responseText;
                }
            };
            xhr.open("POST", "refresh_chat.kz.php", true);
            xhr.send();
        }

        function sendMessage() {
            var message = document.getElementById("message").value;
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    refreshChat();
                }
            };
            xhr.open("POST", "refresh_chat.kz.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("message=" + encodeURIComponent(message) + "&send=send");
            document.getElementById("message").value = ""; 
        }

        function cleanChat() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    refreshChat();
                }
            };
            xhr.open("POST", "refresh_chat.kz.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("clean_chat=clean_chat");
        }
        
        setInterval(refreshChat, 5000);
        refreshChat();

        document.getElementById("message").addEventListener("keyup", function (event) {
            if (event.key === "Enter") {
                sendMessage();
            }
        });
    </script>
</body>
</html>

