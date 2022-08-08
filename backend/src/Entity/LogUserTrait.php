<?php 

namespace App\Entity;

/**
 * 
 */
trait LogUserTrait
{
    
    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="user_created", referencedColumnName="id", nullable=false)
     */
    private $userCreated;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="user_updated", referencedColumnName="id", nullable=false)
     */
    private $userUpdated;

    

    /**
     * Get the value of userCreated
     */ 
    public function getUserCreated(): User
    {
        return $this->userCreated;
    }

    /**
     * Set the value of userCreated
     * @param User $user
     * @return  self
     */ 
    public function setUserCreated(User $user): self
    {
        $this->userCreated = $user;

        return $this;
    }

    /**
     * Get the value of userUpdated
     */ 
    public function getUserUpdated(): User
    {
        return $this->userUpdated;
    }

    /**
     * Set the value of userUpdated
     * @param User $user
     * @return  self
     */ 
    public function setUserUpdated(User $user): self
    {
        $this->userUpdated = $user;

        return $this;
    }
}
