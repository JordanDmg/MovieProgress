<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\OneToMany(targetEntity="App\Entity\MovieView", mappedBy="movie")
     */
    private $usersWhoWtach;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $posterPath;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="movie", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $runtime;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $release_date;

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
    /**
     * Permet de savoir si cet article est liké pas un user
     *
     * @param User $user
     * @return boolean
     */
    public function isWatchByUser(User $user) :bool {
        foreach($this->usersWhoWtach as $watch){
            if($watch->getUser() === $user) return true;
        }
        return false;
    }

    public function getPosterPath(): ?string
    {
        return $this->posterPath;
    }

    public function setPosterPath(?string $posterPath): self
    {
        $this->posterPath = $posterPath;

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

    public function getRuntime(): ?int
    {
        return $this->runtime;
    }

    public function setRuntime(?int $runtime): self
    {
        $this->runtime = $runtime;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->release_date;
    }

    public function setReleaseDate(?\DateTimeInterface $release_date): self
    {
        $this->release_date = $release_date;

        return $this;
    }

}
