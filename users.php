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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $values = [
        ':firstname' => '%' . $_POST['query'] . '%',
        ':lastname' => '%' . $_POST['query'] . '%',
        ':email' => '%' . $_POST['query'] . '%',
    ];

    $query = $dbh->prepare('SELECT `users`.*, `roles`.`label` FROM `users` INNER JOIN `roles` ON `users`.`role_id` = `roles`.`id` WHERE `users`.`firstname` LIKE :firstname OR `users`.`lastname` LIKE :lastname OR `users`.`email` LIKE :email');
    $query->execute($values);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $results = $query->fetchAll();
} else {
    $query = $dbh->prepare('SELECT `users`.*, `roles`.`label` FROM `users` INNER JOIN `roles` ON `users`.`role_id` = `roles`.`id`');
    $query->execute();
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $results = $query->fetchAll();
}
?>

    <div id="users">
        <div class="container">
            <div class="content">
                <h1>Users</h1>

                <div id="table">
                <?php 
                        if (isset($_POST['messages'])) {
                            $messages = json_decode($_POST['messages'], true);

                            echo sprintf(
                                '<div class="messages %1$s">%2$s</div>',
                                /* 1 */ isset($_POST['status']) ? $_POST['status'] : '',
                                /* 2 */ '<div class="message">' . implode($messages, '</div><div class="message">') . '</div>'
                            );
                        }
                    ?>
                    <form action="users.php" method="post">
                        <div class="form-group">
                            <input type="text" name="query" placeholder="Query" value="<?php echo isset($_POST['query']) ? $_POST['query'] : ''; ?>"><button class="btn btn-green">Search</button>
                        </div>
                    </form>
                    <a href="/create.php" class="btn btn-green">Create</a>
                    <table>
                        <thead>
                            <th>Role</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>E-mailaddress</th>
                            <th>Blocked</th>
                            <th>&nbsp;</th>
                        </thead>
                        <tbody>
                            <?php
                            foreach($results as $user) {
                                printf('
                                    <tr>
                                        <td>%1$s</td>
                                        <td>%2$s</td>
                                        <td>%3$s</td>
                                        <td>%4$s</td>
                                        <td>%5$s</td>
                                        <td>%6$s</td>
                                    </tr>
                                    ',
                                    /* 1 */ $user['label'],
                                    /* 2 */ $user['firstname'],
                                    /* 3 */ $user['lastname'],
                                    /* 4 */ $user['email'],
                                    /* 5 */ $user['blocked'] == 0 ? 'No' : 'Yes',
                                    /* 6 */ '<a href="change.php?user_id=' . $user['id'] . '" class="btn btn-green">Change</a>'
                                );
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php include('partials/footer.php'); ?>