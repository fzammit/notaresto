<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantRepository")
 */
class Restaurant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("all_restaurants")
     * @Groups("all_users")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("all_restaurants")
     * @Groups("all_users")
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("all_restaurants")
     * @Groups("all_users")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="restaurants")
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RestaurantPicture", mappedBy="restaurant", cascade={"all"}, orphanRemoval=true)
     * 
     */
    private $restaurantPictures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="restaurant", orphanRemoval=true)
     * 
     */
    private $reviews;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->restaurantPictures = new ArrayCollection();
        $this->reviews = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCity(): ?city
    {
        return $this->city;
    }

    public function setCity(?city $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|RestaurantPicture[]
     */
    public function getRestaurantPictures(): Collection
    {
        return $this->restaurantPictures;
    }

    public function addRestaurantPicture(RestaurantPicture $restaurantPicture): self
    {
        if (!$this->restaurantPictures->contains($restaurantPicture)) {
            $this->restaurantPictures[] = $restaurantPicture;
            $restaurantPicture->setRestaurant($this);
        }

        return $this;
    }

    public function removeRestaurantPicture(RestaurantPicture $restaurantPicture): self
    {
        if ($this->restaurantPictures->contains($restaurantPicture)) {
            $this->restaurantPictures->removeElement($restaurantPicture);
            // set the owning side to null (unless already changed)
            if ($restaurantPicture->getRestaurant() === $this) {
                $restaurantPicture->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setRestaurant($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getRestaurant() === $this) {
                $review->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getAverageRating(): float
    {
        $sum = 0;
        $total = 0;

        foreach ($this->getReviews() as $review) {
            $sum += $review->getRating();
            $total++;
        }

        return $sum / $total;
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
