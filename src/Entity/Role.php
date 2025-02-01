<?php

declare(strict_types=1);

namespace App\Entity;

use App\Dbal\Timestamp\Timestampable;
use App\Repository\RoleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[ORM\Table(name: 'roles')]
#[ORM\HasLifecycleCallbacks]
class Role
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'key', type: Types::STRING, length: 100, unique: true, nullable: false)]
    private string $key;

    #[ORM\Column(name: 'value', type: Types::STRING, length: 100, unique: true, nullable: false)]
    private string $value;

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): Role
    {
        $this->key = $key;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): Role
    {
        $this->value = $value;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
