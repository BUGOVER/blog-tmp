<?php

declare(strict_types=1);

namespace App\Entity;

use App\Dbal\Timestamp\Timestampable;
use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comments')]
#[ORM\HasLifecycleCallbacks]
class Comment
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Comment::class, inversedBy: 'childrenComments')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: false)]
//    #[ORM\Column(name: 'parent_id', type: Types::INTEGER, nullable: true)]
    private ?Comment $parentComment = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'parentComment', orphanRemoval: true)]
    private ?Collection $childrenComments = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id', nullable: false)]
    private User $author;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(name: 'blog_id', referencedColumnName: 'id')]
    private Blog $blog;

    #[ORM\Column(type: Types::STRING, length: 65000, nullable: false)]
    private string $text;

    public function __construct()
    {
        $this->childrenComments = new ArrayCollection();
    }

    public function getParentComment(): ?Comment
    {
        return $this->parentComment;
    }

    public function setParentComment(?Comment $parentComment): Comment
    {
        $this->parentComment = $parentComment;

        return $this;
    }

    public function getChildrenComments(): null|ArrayCollection|Collection
    {
        return $this->childrenComments;
    }

    public function setChildrenComments(?Comment $childrenComments): Comment
    {
        $this->childrenComments = $childrenComments;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): Comment
    {
        $this->author = $author;

        return $this;
    }

    public function getBlog(): Blog
    {
        return $this->blog;
    }

    public function setBlog(Blog $blog): Comment
    {
        $this->blog = $blog;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): Comment
    {
        $this->text = $text;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
