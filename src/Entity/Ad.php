<?php

namespace App\Entity;

use App\Repository\AdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @ORM\Entity(repositoryClass=AdRepository::class)
 */
class Ad
{
    /**
     * @Groups("ads")
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $typology;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="ad")
     */
    private $pictures;

    /**
     * @ORM\Column(type="integer")
     */
    private $houseSize;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gardenSize;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $irrelevantSince;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTypology(): ?string
    {
        return $this->typology;
    }

    public function setTypology(string $typology): self
    {
        $this->typology = $typology;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setAd($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getAd() === $this) {
                $picture->setAd(null);
            }
        }

        return $this;
    }

    public function getHouseSize(): ?int
    {
        return $this->houseSize;
    }

    public function setHouseSize(int $houseSize): self
    {
        $this->houseSize = $houseSize;

        return $this;
    }

    public function getGardenSize(): ?int
    {
        return $this->gardenSize;
    }

    public function setGardenSize(?int $gardenSize): self
    {
        $this->gardenSize = $gardenSize;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getIrrelevantSince(): ?\DateTimeInterface
    {
        return $this->irrelevantSince;
    }

    public function setIrrelevantSince(?\DateTimeInterface $irrelevantSince): self
    {
        $this->irrelevantSince = $irrelevantSince;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'description' => $this->getDescription(),
            'typology' => $this->getTypology(),
            'houseSize' => $this->getHouseSize(),
            'pictures' => $this->getPictures()->getValues(),
            'gardenSize' => $this->getGardenSize(),
            'score' => $this->getScore(),
            'irrelevantSince' => $this->getIrrelevantSince()
        ];
    }
}
