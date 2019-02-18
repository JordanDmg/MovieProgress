<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idTMDB;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="movie", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MovieView", mappedBy="movie")
     */
    private $usersWhoWtach;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->usersWhoWtach = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdTMDB(): ?int
    {
        return $this->idTMDB;
    }

    public function setIdTMDB(int $idTMDB): self
    {
        $this->idTMDB = $idTMDB;

        return $this;
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

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setMovie($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getMovie() === $this) {
                $comment->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MovieView[]
     */
    public function getUsersWhoWtach(): Collection
    {
        return $this->usersWhoWtach;
    }

    public function addUsersWhoWtach(MovieView $usersWhoWtach): self
    {
        if (!$this->usersWhoWtach->contains($usersWhoWtach)) {
            $this->usersWhoWtach[] = $usersWhoWtach;
            $usersWhoWtach->setMovie($this);
        }

        return $this;
    }

    public function removeUsersWhoWtach(MovieView $usersWhoWtach): self
    {
        if ($this->usersWhoWtach->contains($usersWhoWtach)) {
            $this->usersWhoWtach->removeElement($usersWhoWtach);
            // set the owning side to null (unless already changed)
            if ($usersWhoWtach->getMovie() === $this) {
                $usersWhoWtach->setMovie(null);
            }
        }

        return $this;
    }



}
