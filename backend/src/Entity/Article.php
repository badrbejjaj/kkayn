<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article  implements LogUserInterface {

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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean", options={"default" : 1})
     */
    private $active = true;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $logSupp = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $readTime;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="articles")
     */
    private $categorie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getReadTime(): ?int
    {
        return $this->readTime;
    }

    public function setReadTime(?int $readTime): self
    {
        $this->readTime = $readTime;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
