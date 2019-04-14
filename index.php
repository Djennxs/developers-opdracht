<?php include('partials/head.php'); ?>
<?php include('partials/header.php'); ?>

    <div id="homepage">
        <div class="container">
            <div class="content">
                <h1>User Management System</h1>
                <?php
                    if (!isset($_SESSION['user'])) {
                    ?>
                        <p><strong>You're not signed in yet!</strong></p>
                        <a href="/login.php" class="btn btn-green">Sign in!</a>
                    <?php
                    } else {
                    ?>
                        <p>Welcome, <?php echo $_SESSION['user']['firstname']; ?>.</p>
                        
                        <a href="/profile.php" class="btn btn-green">Update profile</a>
                    <?php
                    }
                ?>
            </div>
        </div>
    </div>

<?php include('partials/footer.php'); ?>