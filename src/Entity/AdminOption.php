<?php

namespace App\Entity;

use App\Repository\AdminOptionRepository;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="json")
     */
    private $value;

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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getLabel(): ?string
    {
        $slug = $this->constant;
        $slug = str_replace('_', ' ', $slug);
        $slug = ucfirst(strtolower($slug));
        return $slug;
    }
}
