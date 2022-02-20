<?php

namespace App\Entity;

use App\Config;
use App\Helper\StringHelper;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminOptionRepository;

/**
 * @ORM\Entity(repositoryClass=AdminOptionRepository::class)
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
        if ($this->type === Config::FIELD_BOOL && $this->value === null) {
            return $this->isActive ? 'Active' : 'Inactive';
        }
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

    public function getUnifiedValue(): ?string
    {
        return $this->getValue();
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
}
