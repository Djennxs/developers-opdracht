<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: /index.php');
}

include('partials/head.php');
include('partials/header.php');
require_once 'models/Database.php'; 

$db = new Database();
$dbh = $db->connect();

$query = $dbh->prepare('SELECT * FROM `users` WHERE `id` = ' . $_GET['user_id']);
$query->execute();
$query->setFetchMode(PDO::FETCH_ASSOC);
$user = $query->fetch();

$query = $dbh->prepare('SELECT * FROM `roles`');
$query->execute();
$query->setFetchMode(PDO::FETCH_ASSOC);
$roles = $query->fetchAll();
?>

    <div id="change">
        <div class="container">
            <div class="content">
                <h1>Change user</h1>

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
                    <form action="/change-check.php" method="POST">
                        <input type="text" name="user_id" class="hidden" value="<?php echo $_GET['user_id']; ?>">
                        <div class="form-group">
                            <select name="role_id">
                                <?php
                                    foreach($roles as $role) {
                                        printf('
                                                <option value="%1$s"%2$s>%3$s</option>
                                            ',
                                            /* 1 */ $role['id'],
                                            /* 2 */ $user['role_id'] == $role['id'] ? ' selected' : '',
                                            /* 3 */ $role['label']
                                        );
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <p><strong>User blocked</strong></p>
                            <label for="no">
                                <input type="radio" name="blocked" id="no" value="0"<?php echo $user['blocked'] == 0 ? ' checked' : '' ?>> No
                            </label>
                            <label for="yes">
                                <input type="radio" name="blocked" id="yes" value="1"<?php echo $user['blocked'] == 1 ? ' checked' : '' ?>> Yes
                            </label>
                        </div>
                        <div class="form-group">
                            <input type="text" name="firstname" placeholder="Firstname *" value="<?php echo $user['firstname']; ?>">
                        </div>
                        <div class="form-group">
                            <input type="text" name="lastname" placeholder="Lastname *" value="<?php echo $user['lastname']; ?>">
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="E-mailaddress *" value="<?php echo $user['email']; ?>" disabled>
                        </div>

                        <div class="form-group">
                            <select name="question">
                                <option value="">Pick a question...</option>
                                <option value="option1" <?php echo $user['question'] == 'option1' ? 'selected' : ''; ?>>What was your first pet's name?</option>
                                <option value="option2" <?php echo $user['question'] == 'option2' ? 'selected' : ''; ?>>Where did your parents meet?</option>
                                <option value="option3" <?php echo $user['question'] == 'option3' ? 'selected' : ''; ?>>Who was your childhood best friend?</option>
                                <option value="option4" <?php echo $user['question'] == 'option4' ? 'selected' : ''; ?>>What street did you grow up in?</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="answer" placeholder="Answer *" value="<?php echo $user['answer']; ?>">
                        </div>

                        <div class="line"></div>
                        
                        <div class="form-group">
                            <p><strong>Note</strong></p>
                            <textarea name="note"><?php echo $user['note']; ?></textarea>
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