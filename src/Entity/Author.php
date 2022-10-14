<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ApiResource(
    itemOperations : ['get','put','delete']
)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:Book'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:Book'])]
    private ?string $FirstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:Book'])]
    private ?string $LastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Bibliography = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Book::class)]
    private Collection $Books;

    public function __construct()
    {
        $this->Books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(?string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(?string $LastName): self
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getBibliography(): ?string
    {
        return $this->Bibliography;
    }

    public function setBibliography(?string $Bibliography): self
    {
        $this->Bibliography = $Bibliography;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->Books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->Books->contains($book)) {
            $this->Books->add($book);
            $book->setAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->Books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAuthor() === $this) {
                $book->setAuthor(null);
            }
        }

        return $this;
    }
}
