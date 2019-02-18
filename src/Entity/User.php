<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="datetime")
     */
    private $subscribeDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentLike", mappedBy="user")
     */
    private $likes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Listing", mappedBy="users")
     */
    private $listings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MovieView", mappedBy="user")
     */
    private $movieViews;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MovieToWatch", mappedBy="user", orphanRemoval=true)
     */
    private $movieToWatches;



    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->listings = new ArrayCollection();
        $this->movieViews = new ArrayCollection();
        $this->movieToWatches = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getGender(): ?bool
    {
        return $this->gender;
    }

    public function setGender(bool $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getSubscribeDate(): ?\DateTimeInterface
    {
        return $this->subscribeDate;
    }

    public function setSubscribeDate(\DateTimeInterface $subscribeDate): self
    {
        $this->subscribeDate = $subscribeDate;

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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommentLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(CommentLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(CommentLike $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Listing[]
     */
    public function getListings(): Collection
    {
        return $this->listings;
    }

    public function addListing(Listing $listing): self
    {
        if (!$this->listings->contains($listing)) {
            $this->listings[] = $listing;
            $listing->addUser($this);
        }

        return $this;
    }

    public function removeListing(Listing $listing): self
    {
        if ($this->listings->contains($listing)) {
            $this->listings->removeElement($listing);
            $listing->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|MovieView[]
     */
    public function getMovieViews(): Collection
    {
        return $this->movieViews;
    }

    public function addMovieView(MovieView $movieView): self
    {
        if (!$this->movieViews->contains($movieView)) {
            $this->movieViews[] = $movieView;
            $movieView->setUser($this);
        }

        return $this;
    }

    public function removeMovieView(MovieView $movieView): self
    {
        if ($this->movieViews->contains($movieView)) {
            $this->movieViews->removeElement($movieView);
            // set the owning side to null (unless already changed)
            if ($movieView->getUser() === $this) {
                $movieView->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MovieToWatch[]
     */
    public function getMovieToWatches(): Collection
    {
        return $this->movieToWatches;
    }

    public function addMovieToWatch(MovieToWatch $movieToWatch): self
    {
        if (!$this->movieToWatches->contains($movieToWatch)) {
            $this->movieToWatches[] = $movieToWatch;
            $movieToWatch->setUser($this);
        }

        return $this;
    }

    public function removeMovieToWatch(MovieToWatch $movieToWatch): self
    {
        if ($this->movieToWatches->contains($movieToWatch)) {
            $this->movieToWatches->removeElement($movieToWatch);
            // set the owning side to null (unless already changed)
            if ($movieToWatch->getUser() === $this) {
                $movieToWatch->setUser(null);
            }
        }

        return $this;
    }


}
