<?php

class PostMapper {
    
    public static function map(Post $post, Array $properties) {
        
        if (array_key_exists('id', $properties)) {
            $post->setId($properties['id']);
        }
        if (array_key_exists('text', $properties)) {
            $post->setText($properties['text']);
        }
        if (array_key_exists('title', $properties)) {
            $post->setTitle($properties['title']);
        }
        if (array_key_exists('date_created', $properties)) {
            $post->setDate_created($properties['date_created']);
        }
        if (array_key_exists('user_id', $properties)) {
            $post->setUser_id($properties['user_id']);
        }
        if (array_key_exists('username', $properties)) {
            $post->setUsername($properties['username']);
        }
    }
}
