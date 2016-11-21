<?php

$head_template = new HeadTemplate;
$head_template->setTitle('Login');
$head_template->setDescription('Savvy Savs Login');


$dao = new UserDao();



if (isset($_POST['submit'])) {
    if (isset($_POST['inputUsername']) && isset($_POST['inputPassword'])) {
        $username = $_POST['inputUsername'];
        $password = $_POST['inputPassword'];
        $user = $dao->getUserDetails($username, $password);
        if ($user === null) {
            header('Location: index.php?module=auth&page=login');
        }
        //$admin = false;

        if ($username === $user->getUsername() && $password === $user->getPassword()) {
            $_SESSION['user_username'] = $username;
            $_SESSION['user_password'] = $password;
            header('Location: index.php?module=posts&page=posts');
        } else {
            $error = 'These credentials are invalid';
        }
    }
}
