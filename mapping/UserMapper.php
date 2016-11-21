<?php

class UserMapper {
    
    public static function map(User $user, Array $properties) {
        
        if (array_key_exists('id', $properties)) {
            $user->setId($properties['id']);
        }
        if (array_key_exists('username', $properties)) {
            $user->setUsername($properties['username']);
        }
        if (array_key_exists('password', $properties)) {
            $user->setPassword($properties['password']);
        }
        if (array_key_exists('status', $properties)) {
            $user->setStatus($properties['status']);
        }
    }
}
