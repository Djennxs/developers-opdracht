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
        if ($results[0]['password'] != md5($_POST['password'])) {
            $error = true;
            $messages['password'] = 'The password does not match the one we saved!<br />Did you <a href="/reset.php">forget</a> your password?';
        }
    }

    if ($results[0]['blocked'] == 1) {
        $error = true;
        $messages['blocked'] = 'Your account seems to be blocked! Please contact the administrator for more information.';
    }

    if ($error) {
        printf('
            <form action="/login.php" method="post" id="return" style="display: none;">
                <input type="text" name="status" value="%1$s">
                <textarea name="messages">%2$s</textarea>
                <textarea name="values">%3$s</textarea>
            </form>

            <script>
                document.querySelector(\'#return\').submit();
            </script>
            ',
            /* 1 */ 'danger',
            /* 2 */ json_encode($messages),
            /* 3 */ json_encode($_POST)
        );
    } else {
        session_start();
        $_SESSION['user'] = $results[0];

        if (isset($_POST['logged'])) {
            setcookie('user', json_encode($results[0]), time()+60*60*24*365);
        }

        printf('
            <form action="/" method="post" id="return" style="display: none;">
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