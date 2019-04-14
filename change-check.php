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
                <form action="/change.php?user_id=%3$s" method="post" id="return" style="display: none;">
                    <input type="text" name="status" value="%1$s">
                    <textarea name="messages">%2$s</textarea>
                </form>
    
                <script>
                    document.querySelector(\'#return\').submit();
                </script>
                ',
                /* 1 */ 'danger',
                /* 2 */ json_encode($messages),
                /* 3 */ $_POST['user_id']
            );
        } else {

            $values = [
                ':password' => md5($_POST['password']),
                ':id' => $_POST['user_id']
            ];
    
            $query = $dbh->prepare('UPDATE `users` SET `password` = :password WHERE `id` = :id');
            $query->execute($values);

            $_SESSION['user']['password'] = $_POST['password'];

            if (isset($_COOKIE['logged'])) {
                setcookie('user', json_encode($_SESSION['user']), time()+60*60*24*365);
            }
            
            $messages['success'] = 'Your password has been updated!';
            
            printf('
                <form action="/users.php" method="post" id="return" style="display: none;">
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
            if (empty($v) && !in_array($k, ['blocked', 'note'])) {
                $error = true;
                $messages[$k] = ucfirst(str_replace('-', ' ', $k)) . ' can not be empty.';
            }
        }
    
        if ($error) {
            printf('
                <form action="/change.php?user_id=%3$s" method="post" id="return" style="display: none;">
                    <input type="text" name="status" value="%1$s">
                    <textarea name="messages">%2$s</textarea>
                </form>
    
                <script>
                    document.querySelector(\'#return\').submit();
                </script>
                ',
                /* 1 */ 'danger',
                /* 2 */ json_encode($messages),
                /* 3 */ $_POST['user_id']
            );
        } else {

            $values = [
                ':role_id' => $_POST['role_id'],
                ':firstname' => $_POST['firstname'],
                ':lastname' => $_POST['lastname'],
                ':question' => $_POST['question'],
                ':answer' => $_POST['answer'],
                ':note' => $_POST['note'],
                ':blocked' => $_POST['blocked'],
                ':id' => $_POST['user_id']
            ];
    
            $query = $dbh->prepare('UPDATE `users` SET `role_id` = :role_id, `firstname` = :firstname, `lastname` = :lastname, `question` = :question, `answer` = :answer, `note` = :note, `blocked` = :blocked WHERE `id` = :id');
            $query->execute($values);

            $messages['success'] = 'The profile of <strong>' . $_POST['firstname'] . ' ' . $_POST['lastname'] . '</strong> has been updated!';
            
            printf('
                <form action="/users.php" method="post" id="return" style="display: none;">
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