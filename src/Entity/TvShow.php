<?php

namespace App\Entity;

use App\Repository\TvShowRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TvShowRepository::class)
 */
class TvShow
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"tvshow_list", "tvshow_detail", "tvshow_delete",})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tvshow_list", "tvshow_detail", "tvshow_delete", "characters_list"})
     * @Assert\NotBlank(message="Merci de saisir un titre de série")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"tvshow_list", "tvshow_detail", "tvshow_delete", "characters_list"})
     * @Assert\NotBlank(message="Merci de saisir un synopsis")
     */
    private $synopsis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"tvshow_list", "tvshow_detail", "tvshow_delete", "characters_list"})
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     * @Groups({"tvshow_list", "tvshow_detail", "tvshow_delete", "characters_list"})
     */
    private $nbLikes;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"tvshow_list", "tvshow_detail", "tvshow_delete", "characters_list"})
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"tvshow_list", "tvshow_detail", "tvshow_delete", "characters_list"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"tvshow_list", "tvshow_detail", "tvshow_delete", "characters_list"})
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Season::class, mappedBy="tvShow", orphanRemoval=true)
     */
    private $seasons;

    /**
     * @ORM\ManyToMany(targetEntity=Character::class, inversedBy="tvShows")
     */
    private $characters;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="tvShows")
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"tvshow_list", "tvshow_detail", "tvshow_delete", "characters_list"})
     */
    private $slug;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->characters = new ArrayCollection();
        $this->categories = new ArrayCollection();

        $this->publishedAt = new DateTimeImmutable();
        $this->createdAt = new DateTimeImmutable();
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

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

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

    public function getNbLikes(): ?int
    {
        return $this->nbLikes;
    }

    public function setNbLikes(?int $nbLikes): self
    {
        $this->nbLikes = $nbLikes;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

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
     * @return Collection|Season[]
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setTvShow($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getTvShow() === $this) {
                $season->setTvShow(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Character[]
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        $this->characters->removeElement($character);

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
