<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie implements LogUserInterface
{
    use LogUserTrait;
    use LogDateTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", options={"default" : 1})
     */
    private $active  = true;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $logSupp  = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function isLogSupp(): ?bool
    {
        return $this->logSupp;
    }

    public function setLogSupp(bool $logSupp): self
    {
        $this->logSupp = $logSupp;

        return $this;
    }
}
