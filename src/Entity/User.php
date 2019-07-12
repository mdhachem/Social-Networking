<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields = {"email"},
 * message = "L'email que vous avez indique est deja utilise !"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimum 8 caracteres")
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas tape le meme mot de passe ")
     */
    public $confirm_password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Publication", mappedBy="user", orphanRemoval=true)
     */
    private $publications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostLike", mappedBy="user")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Follow", mappedBy="user")
     */
    private $follows;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="emitter")
     */
    private $messages;


    public function __construct()
    {
        $this->publications = new ArrayCollection();
        $this->follows = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getRole() : ? string
    {
        return $this->role;
    }

    public function setRole(string $role) : self
    {
        $this->role = $role;

        return $this;
    }

    public function getEmail() : ? string
    {
        return $this->email;
    }

    public function setEmail(string $email) : self
    {
        $this->email = $email;

        return $this;
    }

    public function getNom() : ? string
    {
        return $this->nom;
    }

    public function setNom(string $nom) : self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom() : ? string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom) : self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getUsername() : ? string
    {
        return $this->username;
    }

    public function setUsername(string $username) : self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword() : ? string
    {
        return $this->password;
    }

    public function setPassword(string $password) : self
    {
        $this->password = $password;

        return $this;
    }

    public function getActive() : ? string
    {
        return $this->active;
    }

    public function setActive(? string $active) : self
    {
        $this->active = $active;

        return $this;
    }

    public function getImage() : ? string
    {
        return $this->image;
    }

    public function setImage(? string $image) : self
    {
        $this->image = $image;

        return $this;
    }

    // Methode de interface UserInterface
    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * @return Collection|Publication[]
     */
    public function getPublications() : Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication) : self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications[] = $publication;
            $publication->setUser($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication) : self
    {
        if ($this->publications->contains($publication)) {
            $this->publications->removeElement($publication);
                // set the owning side to null (unless already changed)
            if ($publication->getUser() === $this) {
                $publication->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Follow[]
     */
    public function getFollows() : Collection
    {
        return $this->follows;
    }

    public function addFollow(Follow $follow) : self
    {
        if (!$this->follows->contains($follow)) {
            $this->follows[] = $follow;
            $follow->setUser($this);
        }

        return $this;
    }

    public function removeFollow(Follow $follow) : self
    {
        if ($this->follows->contains($follow)) {
            $this->follows->removeElement($follow);
            // set the owning side to null (unless already changed)
            if ($follow->getUser() === $this) {
                $follow->setUser(null);
            }
        }

        return $this;
    }

    public function isFollowByUser(User $user) : bool
    {
        foreach ($this->follows as $follow) {
            if ($follow->getFollowed() === $user) return true;
        }

        return false;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages() : Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message) : self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setEmitter($this);
        }

        return $this;
    }

    public function removeMessage(Message $message) : self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getEmitter() === $this) {
                $message->setEmitter(null);
            }
        }

        return $this;
    }




}
