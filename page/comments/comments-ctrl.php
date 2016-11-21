<?php

$commentDao = new CommentDao();

// data for template
$commentSql = 'SELECT comments.comment, comments.user_id, comments.id, comments.date_created, comments.post_id , users.username FROM comments JOIN users ON comments.user_id = users.id';
$comments = $commentDao->find($commentSql);

