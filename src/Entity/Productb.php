<?php

namespace App\Entity;

use App\Repository\ProductbRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductbRepository::class)
 */
class Productb
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("product:read")
     * @Assert\NotBlank(message="Le nom du produit est obligatoire")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("product:read")
     * @Assert\NotBlank(message="La description est obligatoire")
     */
    private $description;


    /**
     * @ORM\Column(type="float")
     * @Groups("product:read")
     * @Assert\NotBlank(message="Le prix est obligatoire")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("product:read")
     * @Assert\NotBlank(message="La marque est obligatoire")
     */
    private $brand;

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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}
