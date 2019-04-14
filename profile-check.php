<?php
session_start();

require_once 'models/Database.php';

$error = false;
$messages = [];

$db = new Database();
$dbh = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($_POST['password']) && !empty($_POST['password-repeat'])) {

        if (empty($_POST['password'])) {
            $error = true;
            $messages['password'] = 'Password can not be empty.';
        }

        if (empty($_POST['password-repeat'])) {
            $error = true;
            $messages['password'] = 'Password repeat can not be empty.';
        }

        if ($_POST['password'] != $_POST['password-repeat']) {
            $error = true;
            $messages['password-check'] = 'The passwords you filled in were not the same!';
        }

        if ($error) {
            printf('
                <form action="/profile.php" method="post" id="return" style="display: none;">
                    <input type="text" name="status" value="%1$s">
                    <textarea name="messages">%2$s</textarea>
                </form>
    
                <script>
                    document.querySelector(\'#return\').submit();
                </script>
                ',
                /* 1 */ 'danger',
                /* 2 */ json_encode($messages)
            );
        } else {

            $values = [
                ':password' => md5($_POST['password']),
                ':id' => $_SESSION['user']['id']
            ];
    
            $query = $dbh->prepare('UPDATE `users` SET `password` = :password WHERE `id` = :id');
            $query->execute($values);

            $_SESSION['user']['password'] = $_POST['password'];

            if (isset($_COOKIE['logged'])) {
                setcookie('user', json_encode($_SESSION['user']), time()+60*60*24*365);
            }
            
            $messages['success'] = 'Your password has been updated!';
            
            printf('
                <form action="/profile.php" method="post" id="return" style="display: none;">
                    <input type="text" name="status" value="%1$s">
                    <textarea name="messages">%2$s</textarea>
                </form>
    
                <script>
                    document.querySelector(\'#return\').submit();
                </script>
                ',
                /* 1 */ 'success',
                /* 2 */ json_encode($messages)
            );
        }

    } else {
        unset($_POST['password']);
        unset($_POST['password-repeat']);

        foreach($_POST as $k => $v) {
            if (empty($v)) {
                $error = true;
                $messages[$k] = ucfirst(str_replace('-', ' ', $k)) . ' can not be empty.';
            }
        }
    
        if ($error) {
            printf('
                <form action="/profile.php" method="post" id="return" style="display: none;">
                    <input type="text" name="status" value="%1$s">
                    <textarea name="messages">%2$s</textarea>
                </form>
    
                <script>
                    document.querySelector(\'#return\').submit();
                </script>
                ',
                /* 1 */ 'danger',
                /* 2 */ json_encode($messages)
            );
        } else {

            $values = [
                ':firstname' => $_POST['firstname'],
                ':lastname' => $_POST['lastname'],
                ':question' => $_POST['question'],
                ':answer' => $_POST['answer'],
                ':id' => $_SESSION['user']['id']
            ];
    
            $query = $dbh->prepare('UPDATE `users` SET `firstname` = :firstname, `lastname` = :lastname, `question` = :question, `answer` = :answer WHERE `id` = :id');
            $query->execute($values);

            $_SESSION['user']['firstname'] = $_POST['firstname'];
            $_SESSION['user']['lastname'] = $_POST['lastname'];
            $_SESSION['user']['question'] = $_POST['question'];
            $_SESSION['user']['answer'] = $_POST['answer'];

            if (isset($_COOKIE['user'])) {
                setcookie('user', json_encode($_SESSION['user']), time()+60*60*24*365);
            }
            
            $messages['success'] = 'Your profile has been updated!';
            
            printf('
                <form action="/profile.php" method="post" id="return" style="display: none;">
                    <input type="text" name="status" value="%1$s">
                    <textarea name="messages">%2$s</textarea>
                </form>
    
                <script>
                    document.querySelector(\'#return\').submit();
                </script>
                ',
                /* 1 */ 'success',
                /* 2 */ json_encode($messages)
            );
        }
    }
}