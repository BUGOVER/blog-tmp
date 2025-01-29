<?php

declare(strict_types=1);

namespace App\Entity;

use App\Dbal\Type\BlogStatus;
use App\Dbal\Timestamp\Timestampable;
use App\Repository\BlogRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Blog
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    private Category|null $category = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User|null $user = null;

    #[Assert\NotBlank(message: 'Заголовок обязательный к заполнению')]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT, length: 65000)]
    private ?string $description = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT, length: 65000)]
    private ?string $text = null;

    #[ORM\JoinTable(name: 'tags_to_blog')]
    #[ORM\JoinColumn(name: 'blog_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'tag_id', referencedColumnName: 'id', unique: true)]
    #[ORM\ManyToMany(targetEntity: Tag::class, cascade: ['persist'])]
    private ArrayCollection|PersistentCollection $tags;

    #[ORM\Column(name: 'status', type: 'blog_status', nullable: false, enumType: BlogStatus::class)]
    private BlogStatus $status = BlogStatus::active;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $percent = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private DateTime|null $blockedAt;

    public function __construct(UserInterface|User $user)
    {
        $this->user = $user;
    }

    public function getBlockedAt(): ?DateTime
    {
        return $this->blockedAt;
    }

    public function setBlockedAt(?DateTime $blockedAt): Blog
    {
        $this->blockedAt = $blockedAt;

        return $this;
    }

    public function getStatus(): BlogStatus
    {
        return $this->status;
    }

    public function setStatus(BlogStatus $status): Blog
    {
        $this->status = $status;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getTags(): ArrayCollection|PersistentCollection
    {
        return $this->tags;
    }

    public function setTags(ArrayCollection $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function addTag(Tag $tag): void
    {
        $this->tags[] = $tag;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPercent(): ?int
    {
        return $this->percent;
    }

    public function setPercent(int|float $percent): static
    {
        $this->percent = $percent;

        return $this;
    }
}
