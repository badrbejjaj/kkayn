<?php 

namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
/**
 * 
 */
trait LogDateTrait
{
    /**
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="update_date", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;
   

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
