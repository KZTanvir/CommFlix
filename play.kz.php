<?php
include_once 'dashboard/config/Database.php';
include_once 'dashboard/class/User.php';
include_once 'dashboard/class/Chat.php';
include_once 'dashboard/class/Movie.php';
include_once 'dashboard/class/Category.php';
include_once 'dashboard/class/Comment.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$movie = new Movie($db);
$chat = new Chat($db);
$category = new Category($db);
$comment = new Comment($db);

$movie->id = isset($_GET['mid']) ? $_GET['mid'] : '0';

if(!$user->loggedIn()) {
	header("location: index.php");
}

$movieDetails = $movie->getMovie();
$userDetails = $user->getOwner();
$categories = $movie->getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<?php
$siteTitle = "{$movieDetails['title']} | CommFlix @KZTanvir"; 
include "dashboard/inc/head.kz.php";
?>
<body>
    <?php include_once('inc/home_header.kz.php');?>
    <section>
        <div class="card panel-card">
            <div class="panel-heading">Movie Player</div>     <br>       
            <?php if (isset($movieDetails)) {
                $movie_file = $movieDetails['movie_file'];
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Process the form data
                    if (isset($_POST['comment']) && !empty($_POST['comment'])) {
                        $comment->movieId = $movieDetails['id'];
                        $comment->userId = $userDetails['id'];
                        $comment->commentText = $_POST['comment'];
                        $comment->addComment();
                        $chat->sender_id = $userDetails['id'];
                        $chat->sendMessage("<i>Commented on movie: " . "<a href='../play.kz.php?mid=" . $movieDetails['id'] . "'>" . $movieDetails['title'] . "</a></i>");
                    }
                }
            ?>
            <div class="panel-heading" style="border-radius: unset; background-color: #F44336; margin-bottom: 0px;"><?php echo $movieDetails['title'];?></div>
            <div class="movie-container">
                <div class="video-player" style="max-width: 100%;">
                    <video controls autoplay fullscreen style="width: 100%; height: auto;">
                        <source src="<?php echo $movie_file;?>" type='video/mp4'>
                    </video>
                </div>
            </div>
            <div class="panel-heading" style="border-radius: unset;">Comments</div>

            <div class="comment-list">

                <div class="comment">
                    <form method="post" class="comment-bar">
                        <input type="text" name="comment" id="comment" placeholder="Comment here...">
                        <button type="submit">Comment</button>
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
                    </form>
                </div>
                
                <br>
                <?php
                $comment->movieId = $movieDetails['id'];
                $comments = $comment->getCommentsByMovieId();
                foreach ($comments as $comment) {
                    $userInfo = $user->getUserById($comment['uid']);
                ?>
                <div class="comment">
                    <div class="comment-user">
                        <div class="comment-user-image">
                            <img src="<?php echo $userInfo['profile_link'];?>" alt="User Image">
                        </div>
                        <div class="comment-user-name">
                            <a><?php echo $userInfo['first_name']. ' '. $userInfo['last_name'];?></a><br>
                            <a style="color: black; font-size: 14px;" href="profile.kz.php?id=<?php echo $userInfo['id'];?>">[view user]</a>
                        </div>
                    </div>
                    <div class="comment-text">
                        <?php echo $comment['comment_text']. '<br><br><hr style="margin:0;"><i style="font-size: 10px;">Commented At: ['. $comment['comment_date']. ']</i>';?>
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php } else { ?>
                <div class="panel-heading" style="border-radius: unset; background-color: #F44336; margin-bottom: 0px;">Movie Not Found</div>
            <?php } ?>
        </div>
    </section>

    <?php include "inc/footer.kz.php"; ?>
    <script>
        function addEmoji(emoji) {
            var messageInput = document.getElementById("comment");
            messageInput.value += emoji;
        }
    </script>
</body>
</html>


