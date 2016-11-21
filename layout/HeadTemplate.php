<?php

class HeadTemplate {

    private $title;
    private $description;

    function getTitle() {
        return $this->title;
    }

    function getDescription() {
        return $this->description;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setDescription($description) {
        $this->description = $description;
    }

}
