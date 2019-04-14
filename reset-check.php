<?php
require_once 'models/Database.php';

$error = false;
$messages = [];

$db = new Database();
$dbh = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    foreach($_POST as $k => $v) {
        if (empty($v)) {
            $error = true;
            $messages[$k] = ucfirst(str_replace('-', ' ', $k)) . ' can not be empty.';
        }
    }

    $query = $dbh->prepare('SELECT * FROM `users` WHERE `email` = :email');
    $query->execute([':email' => $_POST['email']]);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $results = $query->fetchAll();

    if (count($results) < 1) {
        $error = true;
        $messages['exists'] = 'We can\'t find an account with this e-mailaddress!<br /> Would you like to <a href="/register.php">register</a> one?';
    } else {
        if ($results[0]['question'] != $_POST['question']) {
            $error = true;
            $messages['quesion-check'] = 'This is not the question you selected.';
        } else if ($results[0]['answer'] != $_POST['answer']) {
            $error = true;
            $messages['answer-check'] = 'This is not the correct answer.';
        }

        if ($_POST['password'] != $_POST['password-repeat']) {
            $error = true;
            $messages['password-check'] = 'The passwords you filled in were not the same!';
        }
    }

    if ($error) {
        printf('
            <form action="/reset.php" method="post" id="return" style="display: none;">
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
            ':password' => md5($_POST['password'])
        ];

        $query = $dbh->prepare('UPDATE `users` SET `password` = :password');
        $query->execute($values);

        $_SESSION['user']['password'] = $_POST['password'];

        if (isset($_COOKIE['logged'])) {
            setcookie('user', json_encode($_SESSION['user']), time()+60*60*24*365);
        }
        
        $messages['success'] = 'Your password has been updated!';
        
        printf('
            <form action="/login.php" method="post" id="return" style="display: none;">
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