<?php

namespace App\Entity;

use App\Repository\LivresCreationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivresCreationRepository::class)]
class LivresCreation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $livre_id = null;

    #[ORM\Column(length: 50)]
    private ?string $titre = null;

    #[ORM\Column]
    private ?\DateTime $datePublication = null;

    #[ORM\Column]
    private ?bool $available = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLivreId(): ?int
    {
        return $this->livre_id;
    }

    public function setLivreId(int $livre_id): static
    {
        $this->livre_id = $livre_id;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDatePublication(): ?\DateTime
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTime $datePublication): static
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }
}
