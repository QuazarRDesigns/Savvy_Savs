<?php

class Comment {
    
    private $id;
    private $comment;
    private $date_created;
    private $user_id;
    private $post_id;
    private $username;
    
    function getId() {
        return $this->id;
    }

    function getComment() {
        return $this->comment;
    }

    function getDate_created() {
        return $this->date_created;
    }

    function getUser_id() {
        return $this->user_id;
    }

    function getPost_id() {
        return $this->post_id;
    }
    
    function getUsername() {
        return $this->username;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setComment($comment) {
        $this->comment = $comment;
    }

    function setDate_created($date_created) {
        $this->date_created = $date_created;
    }

    function setUser_id($user_id) {
        $this->user_id = $user_id;
    }

    function setPost_id($post_id) {
        $this->post_id = $post_id;
    }
    
    function setUsername($username) {
        $this->username = $username;
    }
    


}
