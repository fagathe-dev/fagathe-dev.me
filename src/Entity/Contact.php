<?php

namespace App\Entity;

use App\Enum\ContactSubjectEnum;
use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable;


#[ORM\Entity(repositoryClass: ContactRepository::class)]
final class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Saisie obligatoire')]
    private ?string $message = null;

    #[Assert\NotBlank(message: 'Veuillez sélectionner une valeur !')]
    #[ORM\Column]
    private ?string $subject = null;

    #[Assert\NotBlank(message: 'Saisie obligatoire')]
    #[ORM\Column]
    private ?string $email = null;

    #[Assert\NotBlank(message: 'Saisie obligatoire')]
    #[ORM\Column]
    private ?string $telephone = null;

    #[Assert\NotBlank(message: 'Saisie obligatoire')]
    #[ORM\Column]
    private ?string $fullname = null;

    #[Assert\NotBlank(message: 'Saisie obligatoire')]
    #[ORM\Column]
    private ?bool $is_company = false;

    #[Assert\NotBlank(message: 'Saisie obligatoire', allowNull: true)]
    #[ORM\Column(length: 120, nullable: true)]
    private ?string $company_name = null;

    #[Assert\NotBlank(message: 'Saisie obligatoire', allowNull: true)]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $job_offer_link = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Fichier obligatoire', allowNull: true)]
    private ?string $contact_file = null;

    #[ORM\Column(length: 120, nullable: true)]
    #[Assert\NotBlank(message: 'Saisie obligatoire', allowNull: true)]
    private ?string $error_page = null;

    #[ORM\Column]
    private ?DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of message
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */
    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */
    public function setCreatedAt(?DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of subject
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * Set the value of subject
     *
     * @return  self
     */
    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set the value of subject
     *
     * @return  self
     */
    public function getNiceSubject(?string $subject): ?string
    {
        return ContactSubjectEnum::match($this->subject);
    }

    /**
     * Get the value of email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of telephone
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * Set the value of telephone
     *
     * @return  self
     */
    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get the value of fullname
     */
    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    /**
     * Set the value of fullname
     *
     * @return  self
     */
    public function setFullname(?string $fullname): static
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get the value of is_company
     */
    public function getIsCompany(): ?bool
    {
        return $this->is_company;
    }

    /**
     * Set the value of is_company
     *
     * @return  self
     */
    public function setIsCompany(?string $is_company): static
    {
        $this->is_company = $is_company;

        return $this;
    }

    /**
     * Get the value of company_name
     */
    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    /**
     * Set the value of company_name
     *
     * @return  self
     */
    public function setCompanyName(?string $company_name): static
    {
        $this->company_name = $company_name;

        return $this;
    }

    /**
     * Get the value of job_offer_link
     */
    public function getJobOfferLink(): ?string
    {
        return $this->job_offer_link;
    }

    /**
     * Set the value of job_offer_link
     *
     * @return  self
     */
    public function setJobOfferLink(?string $job_offer_link): static
    {
        $this->job_offer_link = $job_offer_link;

        return $this;
    }

    /**
     * Get the value of contact_file
     */
    public function getContactFile(): ?string
    {
        return $this->contact_file;
    }

    /**
     * Set the value of contact_file
     *
     * @return  self
     */
    public function setContactFile(?string $contact_file): static
    {
        $this->contact_file = $contact_file;

        return $this;
    }

    /**
     * Get the value of error_page
     */
    public function getErrorPage(): ?string
    {
        return $this->error_page;
    }

    /**
     * Set the value of error_page
     *
     * @return  self
     */
    public function setErrorPage(?string $error_page): static
    {
        $this->error_page = $error_page;

        return $this;
    }
}
