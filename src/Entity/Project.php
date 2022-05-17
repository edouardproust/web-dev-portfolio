<?php

namespace App\Entity;

use App\Config;
use App\Helper\StringHelper;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProjectRepository; // don't remove
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * @Vich\Uploadable
 */
class Project
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(min=3, max=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=10, max=255)
     */
    private $headline;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(min=200)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     * @Assert\Length(max=255)
     */
    private $thumbnail;

    /**
     * @Vich\UploadableField(mapping="projects_thumb", fileNameProperty="thumbnail")
     * @var File
     */
    private $thumbnailFile;

    /**
     * @ORM\OneToMany(targetEntity=GalleryItem::class, mappedBy="project", cascade={"persist", "remove"})
     */
    private $gallery;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=3, max=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=3, max=255)
     */
    private $repository;

    /**
     * @ORM\ManyToMany(targetEntity=ProjectCategory::class, inversedBy="projects")
     */
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity=CodingLanguage::class, inversedBy="projects")
     */
    private $codingLanguages;

    /**
     * @ORM\OneToMany(
     *     targetEntity=Comment::class,
     *     mappedBy="project",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $comments;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $featured;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="projects")
     * @ORM\JoinColumn(name="author", referencedColumnName="id", onDelete="SET NULL")
     */
    private $author;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $completedOn;

    /**
     * @ORM\ManyToMany(targetEntity=Technology::class, inversedBy="projects")
     */
    private $technologies;

    /**
     * @ORM\ManyToMany(targetEntity=Tool::class, inversedBy="projects")
     */
    private $tools;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->codingLanguages = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->gallery = new ArrayCollection();
        $this->technologies = new ArrayCollection();
        $this->tools = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTime
    {
        if (!$this->createdAt) {
            $this->setCreatedAt(new \DateTime('now'));
        }
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUdpatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function setHeadline(?string $headline): self
    {
        $this->headline = $headline;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getThumbnailFile()
    {
        return $this->thumbnailFile;
    }

    public function setThumbnailFile(?File $thumbnailFile = null)
    {
        $this->thumbnailFile = $thumbnailFile;

        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($thumbnailFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return Collection<int, GalleryItem>
     */
    public function getGallery(): Collection
    {
        return $this->gallery;
    }

    public function addGallery(GalleryItem $gallery): self
    {
        if (!$this->gallery->contains($gallery)) {
            $this->gallery[] = $gallery;
            $gallery->setProject($this);
        }

        return $this;
    }

    public function removeGallery(GalleryItem $gallery): self
    {
        if ($this->gallery->removeElement($gallery)) {
            // set the owning side to null (unless already changed)
            if ($gallery->getProject() === $this) {
                $gallery->setProject(null);
            }
        }

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getRepository(): ?string
    {
        return $this->repository;
    }

    public function setRepository(?string $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return Collection|ProjectCategory[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(ProjectCategory $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(ProjectCategory $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection|CodingLanguage[]
     */
    public function getCodingLanguages(): Collection
    {
        return $this->codingLanguages;
    }

    public function addCodingLanguage(CodingLanguage $codingLanguage): self
    {
        if (!$this->codingLanguages->contains($codingLanguage)) {
            $this->codingLanguages[] = $codingLanguage;
        }

        return $this;
    }

    public function removeCodingLanguage(CodingLanguage $codingLanguage): self
    {
        $this->codingLanguages->removeElement($codingLanguage);

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProject($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProject() === $this) {
                $comment->setProject(null);
            }
        }

        return $this;
    }

    public function getFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(?bool $featured): self
    {
        $this->featured = $featured;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTitleExtract()
    {
        return StringHelper::extract(
            $this->getTitle(),
            Config::ADMIN_CRUD_ENTITY_TITLE_MAX_LENGTH
        );
    }

    public function getCompletedOn(): ?\DateTimeInterface
    {
        return $this->completedOn;
    }

    public function setCompletedOn(\DateTimeInterface $completedOn): self
    {
        $this->completedOn = $completedOn;

        return $this;
    }

    /**
     * @return Collection<int, Technology>
     */
    public function getTechnologies(): Collection
    {
        return $this->technologies;
    }

    public function addTechnology(Technology $technology): self
    {
        if (!$this->technologies->contains($technology)) {
            $this->technologies[] = $technology;
        }

        return $this;
    }

    public function removeTechnology(Technology $technology): self
    {
        $this->technologies->removeElement($technology);

        return $this;
    }

    /**
     * @return Collection<int, Tool>
     */
    public function getTools(): Collection
    {
        return $this->tools;
    }

    public function addTool(Tool $tool): self
    {
        if (!$this->tools->contains($tool)) {
            $this->tools[] = $tool;
        }

        return $this;
    }

    public function removeTool(Tool $tool): self
    {
        $this->tools->removeElement($tool);

        return $this;
    }
}
