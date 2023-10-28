<header>
        <div class="site-info">
            <div class="site-info-sec">
                <img src="https://i.ibb.co/6g4qKX3/Screenshot-from-2023-10-25-21-03-20-modified.png" alt="Site Logo">
                <a href="home.kz.php"><h2>CommFlix</h2></a>
            </div>
        <div class="menu">
            <a href="home.kz.php">Home</a>
            <div class="dropdown">
                <a href="#">Categories</a>
                <div class="dropdown-content">
                    <?php foreach($categories as $cat){
                        echo '<a href="home_category.kz.php?title='. $cat['name'] .'">'. $cat['name'] .'</a>';
                    }?>
                </div>
            </div>
            <a href="search.kz.php">Search</a>
            <a href="dashboard/index.php?mode=dark">Dark Mode</a>
        </div>
        

        <form action="search.kz.php" method="GET" class="search-bar">
            <input type="text" name="title" placeholder="Search...">
            <button type="submit">Search</button>
        </form>
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
        <a href="dashboard/dashboard.kz.php">Dashboard<span><?php echo $movie->totalMovie()+$category->totalCategory()+$user->totalUser(); ?></span></a>
        <a href="dashboard/chat.kz.php">Global Chat</a>
        <a href="logout.kz.php">Logout</a>
        
        <?php }?>
    </nav>
