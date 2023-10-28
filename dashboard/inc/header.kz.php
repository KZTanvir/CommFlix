    <header>
        <div class="site-info">
            <div class="site-info-sec">
                <img src="https://i.ibb.co/6g4qKX3/Screenshot-from-2023-10-25-21-03-20-modified.png" alt="Site Logo">
                <a href="index.php"><h4>CommFlix Dashboard</h4></a>
            </div>
            <?php if(!empty($_SESSION["userid"])){ ?>   
            <div class="menu">
                <a href="../home.kz.php">Home</a>
                <a href="index.php?mode=dark">Dark Mode</a>
            </div>
            <form action="../search.kz.php" method="GET" class="search-bar">
                <input type="text" name="title" placeholder="Search...">
                <button type="submit">Search</button>
            </form>
            <?php }?>
       </div>
       
    </header>
    <nav>
        <?php if(!empty($_SESSION["userid"])){ ?>
        <div class="profile">    
            <img src="<?php echo $user->getOwner()['profile_link']; ?>" alt="Admin Image">
            <?php if($_SESSION["user_type"] == 1){
                        echo '<h4 class="administrator">'.$_SESSION["name"].'</h4><p>Administrator</p>';
                     } else if($_SESSION["user_type"] == 2){
                        echo '<h4 class="admin">'.$_SESSION["name"].'</h4><p>Admin</p>';
                     } else if($_SESSION["user_type"] == 3){
                        echo '<h4 class="normal_user">'.$_SESSION["name"].'</h4><p>User</p>';
                     }?>
        </div>
        <a href="dashboard.kz.php">Dashboard<span><?php echo $movie->totalMovie()+$category->totalCategory()+$user->totalUser(); ?></span></a>
        <a href="chat.kz.php">Global Chat</a>
        <a href="users.kz.php">All Users<span><?php echo $user->totalUser(); ?></span></a>
        <?php if($_SESSION['user_type'] != 3){?>
        <a href="movie.kz.php">All Movies<span><?php echo $movie->totalMovie(); ?></span></a>
        <a href="categories.kz.php">Categories<span><?php echo $category->totalCategory(); ?></span></a>
        <?php }?>
        <a href="add_users.kz.php?id=<?php echo $_SESSION["userid"]?>">Settings</a>
        <a href="change_password.kz.php">Change Password</a>
        <a href="logout.kz.php">Logout</a>
        
        <?php }?>
    </nav>
    
