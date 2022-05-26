<?php

namespace App\Entity;

use App\Path;
use App\Config;
use App\Helper\FileHelper;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminOptionRepository; // don't remove
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=AdminOptionRepository::class)
 * @Vich\Uploadable
 */
class AdminOption
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $constant;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $help;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;
    
    /**
    * @Vich\UploadableField(mapping="adminoptions", fileNameProperty="file")
    * @var File
    */
    private $fileFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isUploadable;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isRequired;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConstant(): ?string
    {
        return $this->constant;
    }

    public function setConstant(string $constant): self
    {
        $this->constant = $constant;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getLabel(): ?string
    {
        if (!$this->label) {
            $label = $this->constant;
            $label = str_replace('_', ' ', $label);
            $this->label = ucfirst(strtolower($label));
        }
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getHelp(): ?string
    {
        return $this->help;
    }

    public function setHelp(?string $help): self
    {
        $this->help = $help;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function setFileFile(File $fileFile = null)
    {
        $this->fileFile = $fileFile;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($fileFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getFileFile()
    {
        return $this->fileFile;
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

    public function getIsUploadable(): ?bool
    {
        return $this->isUploadable;
    }

    public function setIsUploadable(?bool $isUploadable): self
    {
        $this->isUploadable = $isUploadable;

        return $this;
    }

    public function getIsRequired(): ?bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(?bool $isRequired): self
    {
        $this->isRequired = $isRequired;

        return $this;
    }
    

    /**
     * Convert not compatible fields for the EasyAdmin index page to render correctly.
     * @return void
     */
    public function getUnifiedValue()
    {
        $placeholder = $this->getValuePlaceholder($this);
        return $placeholder ?? (string)$this->getValue();
    }

    /**
     * Return a placeholder containing JSON data.
     *
     * The Placeholder is meant to be transformed with javascript into rich HTML elements laster
     *
     * >- Placeholder template: `{%~[json]~%}`
     * >- JSON template: `{"type":"...","value":"..."}`
     * >- Result example: **`{%~{"type":"image","value":"\/uploads\/admin\/options\/favicon.ico"}~%}`**
     *
     * @return null|string
     */
    public function getValuePlaceholder($adminOption)
    {
        $value = $adminOption->getValue();
        // define conditions
        $isBoolean = $adminOption->type === Config::FIELD_BOOL && !$adminOption->value;
        $isUploadable = $adminOption->isUploadable;
        $isUrl = $value && (str_starts_with($value, 'http') || str_starts_with($value, 'www'));
        $isNull = !$adminOption->getValue();

        // check conditions and generate json
        $json = null;
        if ($isBoolean) {
            $json = json_encode([
                'type' => Config::FIELD_BOOL,
                'value' => $adminOption->isActive ? "checked" : ''
            ]);
        } elseif ($isUploadable) {
            $fileUrl = $adminOption->getFile() ? Path::UPLOADS_ADMIN_OPTIONS . '/' . $adminOption->getFile() : null;
            $fileType = FileHelper::getTypeFromExtension($fileUrl);
            // image
            if ($fileType === FileHelper::IMAGE_TYPE || $fileType === FileHelper::ICON_TYPE) {
                $json = json_encode([
                    'type' => FileHelper::IMAGE_TYPE,
                    'value' => $fileUrl
                ]);
            }
            // document
            elseif ($fileType === FileHelper::DOCUMENT_TYPE) {
                $json = json_encode([
                    'type' => FileHelper::DOCUMENT_TYPE,
                    'value' => $fileUrl
                ]);
            } else {
                $json = json_encode([
                    'type' => Config::TYPE_NULL,
                    'value' => FileHelper::FILE_TYPE
                ]);
            }
        } elseif ($isUrl) {
            $json = json_encode([
                'type' => Config::FIELD_URL,
                'value' => $value
            ]);
        }
        // null
        elseif ($isNull) {
            $json = json_encode([
                'type' => Config::TYPE_NULL,
                'value' => Config::FIELD_TEXT
            ]);
        }

        // return placeholder or original value
        if ($json) {
            return '{%~' . $json . '~%}';
        }
        return null;
    }
}
