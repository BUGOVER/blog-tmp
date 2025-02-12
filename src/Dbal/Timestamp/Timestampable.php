<?php

declare(strict_types=1);

namespace App\Dbal\Timestamp;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

trait Timestampable
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    #[ORM\PrePersist]
    #[Ignore]
    public function setCreatedAtAutomatically(): void
    {
        if (null === $this->getCreatedAt()) {
            $this->setCreatedAt(new DateTimeImmutable());
        }
    }

    #[ORM\PreUpdate]
    #[Ignore]
    public function setUpdatedAtAutomatically(): void
    {
        $this->setUpdatedAt(new DateTimeImmutable());
    }
}
