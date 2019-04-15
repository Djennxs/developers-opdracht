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

    if ($_POST['password'] != $_POST['password-repeat']) {
        $error = true;
        $messages['password-check'] = 'The passwords you filled in were not the same!';
    }

    $query = $dbh->prepare('SELECT `email` FROM `users` WHERE `email` = :email');
    $query->execute([':email' => $_POST['email']]);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $results = $query->fetchAll();

    if (count($results) > 0) {
        $error = true;
        $messages['exists'] = 'There is already an account with this e-mailaddress!<br /> Have you <a href="/reset.php">forgotten</a> your password or would you like to <a href="/login.php">sign in</a>?';
    }

    if ($error) {
        printf('
            <form action="/register.php" method="post" id="return" style="display: none;">
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
        $values = [
            ':firstname' => $_POST['firstname'],
            ':lastname' => $_POST['lastname'],
            ':email' => $_POST['email'],
            ':password' => md5($_POST['password']),
            ':question' => $_POST['question'],
            ':answer' => $_POST['answer']
        ];

        $query = $dbh->prepare('INSERT INTO `users` (`firstname`, `lastname`, `email`, `password`, `question`, `answer`) VALUES (:firstname, :lastname, :email, :password, :question, :answer)');
        $query->execute($values);

        $messages['success'] = 'Your account has been successfully created!';
        
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