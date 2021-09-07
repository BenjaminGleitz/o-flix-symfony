<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"tvshow_list", "tvshow_detail", "categories_list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"tvshow_list", "tvshow_detail", "categories_list"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"tvshow_list", "tvshow_detail", "categories_list"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"tvshow_list", "tvshow_detail", "categories_list"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=TvShow::class, mappedBy="categories")
     * @Groups({"tvshow_list", "tvshow_detail", "categories_list"})
     */
    private $tvShows;

    public function __construct()
    {
        $this->tvShows = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString() 
    {
        return $this->name;
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
            $tvShow->addCategory($this);
        }

        return $this;
    }

    public function removeTvShow(TvShow $tvShow): self
    {
        if ($this->tvShows->removeElement($tvShow)) {
            $tvShow->removeCategory($this);
        }

        return $this;
    }
}
