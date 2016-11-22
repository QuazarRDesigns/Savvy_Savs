<?php

$head_template = new HeadTemplate;
$head_template->setTitle('Add-Edit');
$head_template->setDescription('Add a Post');

$errors = array();
$post = null;
$edit = array_key_exists('id', $_GET);
if ($edit) {
    $dao = new PostDao();
    $post = Utils::getObjByGetId($dao);
} else {
    // set defaults
    $post = new Post();
    $post->getText();
    $post->getTitle(); 
    $user_id = $_GET['user_id'];
    $post->setUser_id($user_id);
    $date_created = new DateTime($post->getDate_created(), new DateTimeZone('NZ'));
    $post->setDate_created($date_created->format(DateTime::ISO8601));
}
//if (array_key_exists('cancel', $_POST)) {
//    // redirect
//    Utils::redirect('detail', array('id' => $booking->getId()));
//}
if (array_key_exists('save', $_POST)) {
    // for security reasons, do not map the whole $_POST['post']
    $data = array(
        'title' => $_POST['post']['title'],
        'text' => $_POST['post']['text']
    );
    
    // map
    PostMapper::map($post, $data);
    // validate
    $errors = PostValidator::validate($post);
    // validate
    if (empty($errors)) {
        // save
        $dao = new PostDao();
        $post = $dao->save($post);
        Flash::addFlash('Post saved successfully.');
        // redirect
        Utils::redirect('posts', array('module' => 'posts'));
    }
}