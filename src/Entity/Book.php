<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    normalizationContext : ['groups' => ['read:collection']],
    itemOperations : ['get'=>[
        'normalization_context' => ['groups' => ['read:collection','read:item','read:Book']]
    ],
    'put','delete']
)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection'])]

    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:collection'])]

    private ?string $Title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:collection'])]

    private ?string $Description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['read:collection'])]

    private ?\DateTimeInterface $PublicationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:collection'])]

    private ?string $Genre = null;

    #[ORM\ManyToOne(inversedBy: 'Books')]
    #[Groups(['read:item'])]
    private ?Author $author = null;

    #[ORM\OneToMany(mappedBy: 'Book', targetEntity: Review::class)]
    private Collection $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(?string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->PublicationDate;
    }

    public function setPublicationDate(?\DateTimeInterface $PublicationDate): self
    {
        $this->PublicationDate = $PublicationDate;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->Genre;
    }

    public function setGenre(?string $Genre): self
    {
        $this->Genre = $Genre;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setBook($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getBook() === $this) {
                $review->setBook(null);
            }
        }

        return $this;
    }
}
