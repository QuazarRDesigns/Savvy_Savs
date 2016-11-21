<?php

class CommentMapper {
    
    public static function map(Comment $comment, Array $properties) {
        
        if (array_key_exists('id', $properties)) {
            $comment->setId($properties['id']);
        }
        if (array_key_exists('comment', $properties)) {
            $comment->setComment($properties['comment']);
        }
        if (array_key_exists('date_created', $properties)) {
            $comment->setDate_created($properties['date_created']);
        }
        if (array_key_exists('user_id', $properties)) {
            $comment->setUser_id($properties['user_id']);
        }
        if (array_key_exists('post_id', $properties)) {
            $comment->setPost_id($properties['post_id']);
        }
        if (array_key_exists('username', $properties)) {
            $comment->setUsername($properties['username']);
        }
    }
}

