<?php

namespace App\Entity;

use App\Helper\StringHelper;
use App\Path;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="comments")
     * @ORM\JoinColumn(name="project", referencedColumnName="id", onDelete="CASCADE")
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comments")
     * @ORM\JoinColumn(name="post", referencedColumnName="id", onDelete="CASCADE")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity=Lesson::class, inversedBy="comments")
     * @ORM\JoinColumn(name="lesson", referencedColumnName="id", onDelete="CASCADE")
     */
    private $lesson;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isVisible;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(?Lesson $lesson): self
    {
        $this->lesson = $lesson;

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(?bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    // Methods for twig

    /**
     * @return null|Project|Lesson|Post
     */
    public function getEntity()
    {
        return $this->getPost() ?? $this->getLesson() ?? $this->getProject();
    }

    /**
     * @return null|string
     */
    public function getEntityType()
    {
        switch ($this) {
            case $this->getPost() !== null: return Path::URL_PREFIX_BLOG;
            case $this->getLesson() !== null: return Path::URL_PREFIX_LESSONS;
            case $this->getProject() !== null: return Path::URL_PREFIX_PORTFOLIO;
            default: return null;
        }
    }

    // Methods for EasyAdmin fields

    public function getExtract(int $length = 70): string
    {
        return StringHelper::extract($this->getContent(), $length);
    }

    /**
     * @return null|Project|Lesson|Post
     */
    public function getPostTypeUrl()
    {
        if ($entityType = $this->getEntityType()) {
            $entity = $this->getEntity();
            return $entityType . '/' . $entity->getSlug() . '_' . $entity->getId();
        }
        return null;
    }
}
