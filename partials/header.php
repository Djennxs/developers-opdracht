<header>
    <div class="container">
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <?php if (isset($_SESSION['user'])) { ?>
                    <li><a href="/profile.php">Change profile</a></li>
                    <li class="logout"><a href="/logout.php">Sign out</a></li>

                    <?php if ($_SESSION['user']['role_id'] == 1) { ?>
                        <li><a href="users.php">Users</a></li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </nav>
    </div>
</header>