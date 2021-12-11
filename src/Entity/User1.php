<?php

namespace App\Entity;

use App\Repository\User1Repository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
//use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiResource(formats={"json"})
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=User1Repository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User1 implements UserInterface, PasswordAuthenticatedUserInterface , \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $CIN;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $FirstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $LastName;

    /**
     * @ORM\Column(type="date")
     */
    private $Birthdate;

    /**
     * @ORM\Column(type="string", length=255)

    private $username;
*/
    /**
     * @var string | null
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $picture;
    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="picture")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=RDV::class, mappedBy="User",cascade={"All"})
     */
    private $rDVs;

    public function __construct()
    {
        $this->rDVs = new ArrayCollection();
        $this->records = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    public function setImageFile( $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
        /*if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }*/
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isVerified = false;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="users")
     */
    private $Category;

    /**
     * @ORM\ManyToOne(targetEntity=RDV::class, inversedBy="User")
     */
    private $rDV;

    /**
     * @ORM\OneToMany(targetEntity=Records::class, mappedBy="User1",cascade={"All"})
     */
    private $records;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="User",cascade={"All"})
     */
    private $notifications;

    /**
     * @ORM\OneToMany(targetEntity=Contact::class, mappedBy="User",cascade={"All"})
     */
    private $contacts;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCIN(): ?string
    {
        return $this->CIN;
    }

    public function setCIN(string $CIN): self
    {
        $this->CIN = $CIN;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(string $LastName): self
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->Birthdate;
    }

    public function setBirthdate(\DateTimeInterface $Birthdate): self
    {
        $this->Birthdate = $Birthdate;

        return $this;
    }



    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture( $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }


    /**
     * public function serialize() {

    return serialize(array(
    $this->id,
    $this->username,
    $this->password,
    ));

    }

    public function unserialize($serialized) {

    list (
    $this->id,
    $this->username,
    $this->password,
    ) = unserialize($serialized);
    }
     *
     *
     */

    /**
     *@see \Serializable::serialize()
     */

     public function serialize()
     { return serialize(array( $this->id, $this->email, $this->password, )); }

    /**
     * @see \Serializable::unserialize()
     */

    public function unserialize($serialized) { list ( $this->id, $this->email, $this->password, )
        = unserialize($serialized, array('allowed_classes' => false)); }

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    public function getLog():String
    {
        $log=substr($this->email,0,strpos($this->email,"@"));
        return $log;
    }

    public function getRDV(): ?RDV
    {
        return $this->rDV;
    }

    public function setRDV(?RDV $rDV): self
    {
        $this->rDV = $rDV;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getLog();
    }

    /**
     * @return Collection|Records[]
     */
    public function getRecords(): Collection
    {
        return $this->records;
    }

    public function addRecord(Records $record): self
    {
        if (!$this->records->contains($record)) {
            $this->records[] = $record;
            $record->setUser1($this);
        }

        return $this;
    }

    public function removeRecord(Records $record): self
    {
        if ($this->records->removeElement($record)) {
            // set the owning side to null (unless already changed)
            if ($record->getUser1() === $this) {
                $record->setUser1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setUser($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getUser() === $this) {
                $contact->setUser(null);
            }
        }

        return $this;
    }

    public function getLabel()
    {
        return 'User';
    }

    public function toString(): string
    {
        return $this->getFirstName().' '.$this->getLastName() ;
    }

    public function notifsize():?int
    {
        $result=0;
        foreach ($this->notifications as $n){
            if($n->getOpened()==false)
            {
                $result=1;
            }
        }
        return $result;
    }

}


