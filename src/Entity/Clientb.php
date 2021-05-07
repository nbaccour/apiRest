<?php

namespace App\Entity;

use App\Repository\ClientbRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientbRepository::class)
 */
class Clientb
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Userb::class, mappedBy="clientb")
     */
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
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

    /**
     * @return Collection|Userb[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(Userb $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setClientb($this);
        }

        return $this;
    }

    public function removeUser(Userb $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getClientb() === $this) {
                $user->setClientb(null);
            }
        }

        return $this;
    }
}
