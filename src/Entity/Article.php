<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $relaseDate = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'ArtList')]
    #[ORM\JoinTable(name:"article_user")]

    private Collection $Relation;


    public function __construct()
    {
        $this->relaseDate = new \DateTime();
        $this->Relation = new ArrayCollection();
    }


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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getRelaseDate(): ?\DateTimeInterface
    {
        return $this->relaseDate;
    }

    public function setRelaseDate(): void
    {
        $this->relaseDate = new \DateTime();
    }

    /**
     * @return Collection<int, User>
     */
    public function getRelation(): Collection
    {
        return $this->Relation;
    }

    public function addRelation(User $relation): self
    {
        if (!$this->Relation->contains($relation)) {
            $this->Relation->add($relation);
        }

        return $this;
    }

    public function removeRelation(User $relation): self
    {
        $this->Relation->removeElement($relation);

        return $this;
    }


}
