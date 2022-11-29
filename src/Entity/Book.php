<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Api\FilterInterface;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    normalizationContext : ['groups' => ['read:collection']],
    itemOperations : ['get'=>[
        'normalization_context' => ['groups' => ['read:collection','read:item','read:Book']]
    ],
    'put'=>[
        "security" => "is_granted('ROLE_EDITOR') or object.user == user",
        "security_message" => "Only owner can update books.",
        'normalization_context' => ['groups' => ['read:Book']]
    ],
    'delete'],
    attributes: ["security" => "is_granted('ROLE_USER')"],
    collectionOperations: [
        "post" => [
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Only admins can add books.",
        ],
       
    ],
)]

#[ApiFilter(SearchFilter::class, properties: ['author' => 'exact'])]

class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection'])]

    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:collection'])]

    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:collection'])]

    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['read:collection'])]

    private ?\DateTimeInterface $publicationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:collection'])]

    private ?string $genre = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[Groups(['read:item'])]
    private ?Author $author = null;

    #[ORM\OneToMany(mappedBy: 'Book', targetEntity: Review::class)]
    private Collection $reviews;

    #[ORM\ManyToOne(inversedBy: 'books')]
    public ?User $user = null;

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
        return $this->title;
    }

    public function setTitle(?string $Title): self
    {
        $this->title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $Description): self
    {
        $this->description = $Description;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(?\DateTimeInterface $PublicationDate): self
    {
        $this->publicationDate = $PublicationDate;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $Genre): self
    {
        $this->genre = $Genre;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
