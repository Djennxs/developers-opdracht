<?php include('partials/head.php'); ?>
<?php include('partials/header.php'); ?>

    <div id="login">
        <div class="container">
            <div class="content">
                <h1>Login</h1>
                
                <div id="form">
                    
                    <?php 
                        if (isset($_POST['messages'])) {
                            $messages = json_decode($_POST['messages'], true);

                            echo sprintf(
                                '<div class="messages %1$s">%2$s</div>',
                                /* 1 */ isset($_POST['status']) ? $_POST['status'] : '',
                                /* 2 */ '<div class="message">' . implode($messages, '</div><div class="message">') . '</div>'
                            );
                        }

                        if (isset($_POST['values'])) {
                            $post = json_decode($_POST['values'], true);
                        }
                    ?>

                    <form action="/login-check.php" method="POST">
                        <div class="form-group">
                            <input type="email" name="email" placeholder="E-mailaddress">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-green">Sign in</button>
                        </div>
                        <div class="form-group">
                            <label for="logged"><input type="checkbox" name="logged" value="1" id="logged"> Stay logged in</label>
                        </div>
                    </form>

                    <div class="links">
                        <a href="/reset.php">I forgot my password!</a><span>|</span><a href="/register.php">I don't have an account!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include('partials/footer.php'); ?>