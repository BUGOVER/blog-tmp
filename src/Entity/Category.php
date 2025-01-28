<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFullName(): ?string
    {
        return '<p>' . $this->name . ' полное имя категории</p>';
    }

    public function __toString(): string
    {
        return $this->name . ' категория';
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
