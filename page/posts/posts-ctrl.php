<?php

$head_template = new HeadTemplate;
$head_template->setTitle('Posts');
$head_template->setDescription('Savvy Savs Posts');


$dao = new PostDao();

// data for template
$sql = 'SELECT posts.text, posts.title, posts.user_id, posts.id, posts.date_created, users.username FROM posts JOIN users ON posts.user_id = users.id ';
$posts = $dao->find($sql);

include '../page/comments/comments-ctrl.php';

if (isset($_SESSION['user_username'])) {
    $username = $_SESSION['user_username'];
    
    $userDao = new UserDao();

    $userSql = "SELECT id FROM users WHERE username = " . "'$username'";
    $user = $userDao->find($userSql);
    $user_id = $user->getId();
}

if (isset($_SESSION['admin'])) {
    $admin = true;
} else {
    $admin = false;
}
