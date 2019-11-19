<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of EIDImport
 *
 * @author PKom
 * @ORM\Entity(repositoryClass="App\Repository\EIDImportRepository")
 * @ORM\Table(name="eid_import")
 */
class EIDImport {

    public function __construct() {
        $this->date_import = new \DateTime();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\column(type="decimal",precision=12,scale=2,name="file_size",nullable=true)
     */
    protected $fileSize;

    /**
     * @ORM\Column(type="datetime", name="date_import",nullable=true)
     */
    protected $dateImport;

    /**
     * @ORM\column(type="string",length=100,name="file_name",nullable=true)
     */
    protected $fileName;

    /**
     * @ORM\column(type="integer",name="rows_number",nullable=true)
     */
    protected $rowsNumber;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileSize(): ?string
    {
        return $this->fileSize;
    }

    public function setFileSize(string $fileSize): self
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    public function getDateImport(): ?\DateTimeInterface
    {
        return $this->dateImport;
    }

    public function setDateImport(\DateTimeInterface $dateImport): self
    {
        $this->dateImport = $dateImport;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getRowsNumber(): ?int
    {
        return $this->rowsNumber;
    }

    public function setRowsNumber(int $rowsNumber): self
    {
        $this->rowsNumber = $rowsNumber;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
