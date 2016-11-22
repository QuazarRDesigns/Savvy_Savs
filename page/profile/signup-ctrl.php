<?php

$head_template = new HeadTemplate;
$head_template->setTitle('Sign Up');
$head_template->setDescription('Sign up to Savvy Savs');

$errors = array();
$user = null;
$edit = array_key_exists('id', $_GET);
if ($edit) {
    $dao = new UserDao();
    $user = Utils::getObjByGetId($dao);
} else {
    // set defaults
    $user = new User();
}
//if (array_key_exists('cancel', $_POST)) {
//    // redirect
//    Utils::redirect('detail', array('id' => $booking->getId()));
//}
if (array_key_exists('save', $_POST)) {
    // for security reasons, do not map the whole $_POST['post']
    $data = array(
        'username' => $_POST['user']['username'],
        'password' => $_POST['user']['password']
    );

    // map
    UserMapper::map($user, $data);
    // validate
    $errors = UserValidator::validate($user);
    // validate
    if (empty($errors)) {
        // save
        $dao = new UserDao();
        $user = $dao->save($user);
        Flash::addFlash('Signed up successfully.');
        // redirect
        Utils::redirect('posts', array('module' => 'posts'));
    }
}