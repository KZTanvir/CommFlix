<header>
        <div class="site-info">
            <div class="site-info-sec">
                <img src="images/admin_image.jpg" alt="Site Logo">
                <a href="home.kz.php"><h2>Dashboard</h2></a>
            </div>
        </div>
        
        <div class="menu">
            <a href="home.kz.php">Home</a>
            <div class="dropdown">
                <a href="#">Categories</a>
                <div class="dropdown-content">
                    <?php foreach($categories as $category){
                        echo '<a href="home_category.kz.php?title='. $category['name'] .'">'. $category['name'] .'</a>';
                    }?>
                </div>
            </div>
            <a href="search.kz.php">Search</a>
            <a href="dashboard.kz.php">Dashboard</a>
        </div>

        <form action="search.kz.php" method="GET" class="search-bar">
            <input type="text" name="title" placeholder="Search...">
            <button type="submit">Search</button>
        </form>

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
        </div><a href="chat.kz.php">Global Chat</a>
        <a href="logout.kz.php">Logout</a>
        
        <?php }?>
    </nav>
