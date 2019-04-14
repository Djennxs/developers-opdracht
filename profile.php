<?php 
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /index.php');
}
?>

<?php include('partials/head.php'); ?>
<?php include('partials/header.php'); ?>

    <div id="profile">
        <div class="container">
            <div class="content">
                <h1>Profile</h1>
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
                    <form action="/profile-check.php" method="POST">
                        <div class="form-group">
                            <input type="text" name="firstname" placeholder="Firstname *" value="<?php echo $_SESSION['user']['firstname']; ?>">
                        </div>
                        <div class="form-group">
                            <input type="text" name="lastname" placeholder="Lastname *" value="<?php echo $_SESSION['user']['lastname']; ?>">
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="E-mailaddress *" value="<?php echo $_SESSION['user']['email']; ?>" disabled>
                        </div>

                        <div class="form-group">
                            <select name="question">
                                <option value="">Pick a question...</option>
                                <option value="option1" <?php echo $_SESSION['user']['question'] == 'option1' ? 'selected' : ''; ?>>What was your first pet's name?</option>
                                <option value="option2" <?php echo $_SESSION['user']['question'] == 'option2' ? 'selected' : ''; ?>>Where did your parents meet?</option>
                                <option value="option3" <?php echo $_SESSION['user']['question'] == 'option3' ? 'selected' : ''; ?>>Who was your childhood best friend?</option>
                                <option value="option4" <?php echo $_SESSION['user']['question'] == 'option4' ? 'selected' : ''; ?>>What street did you grow up in?</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="answer" placeholder="Answer *" value="<?php echo $_SESSION['user']['answer']; ?>">
                        </div>

                        <div class="line"></div>

                        <div class="form-group">
                            <input type="password" name="password" placeholder="Password *">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password-repeat" placeholder="Password repeat *">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-green">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include('partials/footer.php'); ?>