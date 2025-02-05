<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BlogMetaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogMetaRepository::class)]
class BlogMeta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'description', length: 1000, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'keywords', length: 100, unique: true, nullable: false)]
    private string $keywords;

    #[ORM\Column(name: 'author', nullable: false)]
    private string $author;

    #[ORM\OneToOne(targetEntity: Blog::class, inversedBy: 'meta')]
    #[ORM\JoinColumn(name: 'blog_id', referencedColumnName: 'id')]
    private Blog $blog;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): BlogMeta
    {
        $this->description = $description;

        return $this;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): BlogMeta
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): BlogMeta
    {
        $this->author = $author;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBlog(): Blog
    {
        return $this->blog;
    }

    public function setBlog(Blog $blog): BlogMeta
    {
        $this->blog = $blog;

        return $this;
    }
}
