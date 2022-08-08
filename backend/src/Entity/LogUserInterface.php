<?php
namespace App\Entity;

interface LogUserInterface {
        /**
     * Get the value of userCreated
     */ 
    public function getUserCreated(): User;

    /**
     * Set the value of userCreated
     * @param User $user
     * @return  self
     */ 
    public function setUserCreated(User $user);

    /**
     * Get the value of userUpdated
     */ 
    public function getUserUpdated(): User;

    /**
     * Set the value of userUpdated
     * @param User $user
     * @return  self
     */ 
    public function setUserUpdated(User $user);
}