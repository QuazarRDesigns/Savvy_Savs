<?php

class Post {

    private $id;
    private $text;
    private $title;
    private $date_created;
    private $user_id;
    private $username;

    function getId() {
        return $this->id;
    }

    function getText() {
        return $this->text;
    }

    function getTitle() {
        return $this->title;
    }

    function getDate_created() {
        return $this->date_created;
    }

    function getUser_id() {
        return $this->user_id;
    }
    
    function getUsername() {
        return $this->username;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setText($text) {
        $this->text = $text;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setDate_created($date_created) {
        $this->date_created = $date_created;
    }

    function setUser_id($user_id) {
        $this->user_id = $user_id;
    }
    
    function setUsername($username) {
        $this->username = $username;
    }

}
