<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 * @ORM\Table(name="`character`")
 */
class Character
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
 * @Groups({"tvshow_list", "tvshow_detail", "characters_list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
 * @Groups({"tvshow_list", "tvshow_detail", "characters_list"})
     * @Assert\NotBlank
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
 * @Groups({"tvshow_list", "tvshow_detail", "characters_list"})
     * @Assert\NotBlank
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
 * @Groups({"tvshow_list", "tvshow_detail", "characters_list"})
     */
    private $gender;

    /**
     * @ORM\Column(type="text", nullable=true)
 * @Groups({"tvshow_list", "tvshow_detail", "characters_list"})
     */
    private $bio;

    /**
     * @ORM\Column(type="smallint", nullable=true)
 * @Groups({"tvshow_list", "tvshow_detail", "characters_list"})
     */
    private $age;

    /**
     * @ORM\Column(type="datetime_immutable")
 * @Groups({"tvshow_list", "tvshow_detail", "characters_list"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
 * @Groups({"tvshow_list", "tvshow_detail", "characters_list"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=TvShow::class, mappedBy="characters")
 * @Groups({"tvshow_list", "tvshow_detail", "characters_list"})
     */
    private $tvShows;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
 * @Groups({"tvshow_list", "tvshow_detail", "characters_list"})
     */
    private $image;

    public function __construct()
    {
        $this->tvShows = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString() 
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|TvShow[]
     */
    public function getTvShows(): Collection
    {
        return $this->tvShows;
    }

    public function addTvShow(TvShow $tvShow): self
    {
        if (!$this->tvShows->contains($tvShow)) {
            $this->tvShows[] = $tvShow;
            $tvShow->addCharacter($this);
        }

        return $this;
    }

    public function removeTvShow(TvShow $tvShow): self
    {
        if ($this->tvShows->removeElement($tvShow)) {
            $tvShow->removeCharacter($this);
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
