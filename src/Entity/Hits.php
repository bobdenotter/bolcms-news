<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HitsRepository")
 */
class Hits
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $php;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $dbdriver;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $dbversion;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $local;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $remote;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getPhp(): ?string
    {
        return $this->php;
    }

    public function setPhp(string $php): self
    {
        $this->php = $php;

        return $this;
    }

    public function getDbdriver(): ?string
    {
        return $this->dbdriver;
    }

    public function setDbdriver(string $dbdriver): self
    {
        $this->dbdriver = $dbdriver;

        return $this;
    }

    public function getDbversion(): ?string
    {
        return $this->dbversion;
    }

    public function setDbversion(string $dbversion): self
    {
        $this->dbversion = $dbversion;

        return $this;
    }

    public function getLocal(): ?string
    {
        return $this->local;
    }

    public function setLocal(string $local): self
    {
        $this->local = $local;

        return $this;
    }

    public function getRemote(): ?string
    {
        return $this->remote;
    }

    public function setRemote(string $remote): self
    {
        $this->remote = $remote;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
