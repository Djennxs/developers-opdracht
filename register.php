<?php include('partials/head.php'); ?>
<?php include('partials/header.php'); ?>

    <div id="register">
        <div class="container">
            <div class="content">
                <h1>Register</h1>
                
                <div id="form">
                    <p>Please fill in the form to register an account!</p>

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

                    <form action="/register-check.php" method="POST">
                        <div class="form-group">
                            <input type="text" name="firstname" placeholder="Firstname *" value="<?php echo $post['firstname'] ? $post['firstname'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <input type="text" name="lastname" placeholder="Lastname *" value="<?php echo $post['lastname'] ? $post['lastname'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="E-mailaddress *" value="<?php echo $post['email'] ? $post['email'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Password *">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password-repeat" placeholder="Password repeat *">
                        </div>

                        <div class="line"></div>

                        <div class="form-group">
                            <select name="question">
                                <option value="">Pick a question...</option>
                                <option value="option1" <?php echo $post['question'] == 'option1' ? 'selected' : ''; ?>>What was your first pet's name?</option>
                                <option value="option2" <?php echo $post['question'] == 'option2' ? 'selected' : ''; ?>>Where did your parents meet?</option>
                                <option value="option3" <?php echo $post['question'] == 'option3' ? 'selected' : ''; ?>>Who was your childhood best friend?</option>
                                <option value="option4" <?php echo $post['question'] == 'option4' ? 'selected' : ''; ?>>What street did you grow up in?</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="answer" placeholder="Answer *" value="<?php echo $post['answer'] ? $post['answer'] : ''; ?>">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-green">Register</button>
                        </div>
                    </form>

                    <div class="links">
                        <a href="/reset.php">I forgot my password!</a><span>|</span><a href="/login.php">I already have an account!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include('partials/footer.php'); ?>