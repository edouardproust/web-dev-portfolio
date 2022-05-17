<?php

namespace App\Entity;

use App\Helper\FileHelper;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GalleryItemRepository; // don't remove
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=GalleryItemRepository::class)
 * @Vich\Uploadable
 */
class GalleryItem
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $item;

    /**
     * @Vich\UploadableField(mapping="projects_lib", fileNameProperty="item")
     */
    private $itemFile;

    private $fileExtension;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caption;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="gallery")
     */
    private $project;

    public function __toString()
    {
        return FileHelper::getLabel($this->item);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?string
    {
        return $this->item;
    }

    public function setItem(string $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getItemFile()
    {
        return $this->itemFile;
    }

    public function setItemFile(File $itemFile = null)
    {
        $this->itemFile = $itemFile;
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($itemFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getFileExtension(): ?string
    {
        return $this->fileExtension ?: $this->setFileExtension();
    }

    public function setFileExtension(?string $extension = null)
    {
        $this->fileExtension = $extension ?: FileHelper::getExtension($this->item);
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

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
}
