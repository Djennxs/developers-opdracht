<?php

session_start();
session_destroy();

if ($_COOKIE['user']) {
    setcookie('user', '', time()-1);
}

header('Location: /');