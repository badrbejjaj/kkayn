<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie implements LogUserInterface
{

    CONST ARTICLE_TYPE = 1;
    CONST VOCABULARY_TYPE = 2;
    CONST TYPES_LIST = [
        self::ARTICLE_TYPE,
        self::VOCABULARY_TYPE
    ];

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

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="categorie")
     */
    private $articles;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Vocabulary::class, mappedBy="categorie")
     */
    private $vocabularies;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->vocabularies = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCategorie($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategorie() === $this) {
                $article->setCategorie(null);
            }
        }

        return $this;
    }
    
    public function isRemovable() {

        $function = function ($key, $element) {
            return $element->isLogSupp() === false;
        };

        if ($this->getArticles()->exists($function)) {
            return false;
        }

        return true;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Vocabulary>
     */
    public function getVocabularies(): Collection
    {
        return $this->vocabularies;
    }

    public function addVocabulary(Vocabulary $vocabulary): self
    {
        if (!$this->vocabularies->contains($vocabulary)) {
            $this->vocabularies[] = $vocabulary;
            $vocabulary->setCategorie($this);
        }

        return $this;
    }

    public function removeVocabulary(Vocabulary $vocabulary): self
    {
        if ($this->vocabularies->removeElement($vocabulary)) {
            // set the owning side to null (unless already changed)
            if ($vocabulary->getCategorie() === $this) {
                $vocabulary->setCategorie(null);
            }
        }

        return $this;
    }
}
