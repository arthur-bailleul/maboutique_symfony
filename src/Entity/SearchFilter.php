<?php

namespace App\Entity;

use App\Repository\SearchFilterRepository;
use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity(repositoryClass: SearchFilterRepository::class)]
class SearchFilter
{
    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column]
    private ?int $id = null;

    // #[ORM\Column]
    private array $categories = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): static
    {
        $this->categories = $categories;

        return $this;
    }
}
