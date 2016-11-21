<?php

$head_template = new HeadTemplate;
$head_template->setTitle('Add-Edit');
$head_template->setDescription('Add a Commment');

$errors = array();
$comment = null;
$edit = array_key_exists('id', $_GET);
if ($edit) {
    $dao = new CommentDao();
    $comment = Utils::getObjByGetId($dao);
} else {
    // set defaults
    $comment = new Comment();
    $comment->getComment();
    $user_id = 2;
    $comment->setUser_id($user_id);
    $date_created = new DateTime($comment->getDate_created(), new DateTimeZone('NZ'));
    $comment->setDate_created($date_created->format(DateTime::ISO8601));
    $post_id = $_GET['post_id'];
    $comment->setPost_id($post_id);
}
//if (array_key_exists('cancel', $_POST)) {
//    // redirect
//    Utils::redirect('detail', array('id' => $booking->getId()));
//}
if (array_key_exists('save', $_POST)) {
    // for security reasons, do not map the whole $_POST['comment']
    $data = array(
        'comment' => $_POST['comment']['comment'],
    );
    
    // map
    CommentMapper::map($comment, $data);
    // validate
    $errors = CommentValidator::validate($comment);
    // validate
    if (empty($errors)) {
        // save
        $dao = new CommentDao();
        $comment = $dao->save($comment);
        Flash::addFlash('Comment saved successfully.');
        // redirect
        Utils::redirect('posts', array('module' => 'posts'));
    }
}