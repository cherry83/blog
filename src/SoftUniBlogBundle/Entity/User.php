<?php

namespace SoftUniBlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="SoftUniBlogBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="SoftUniBlogBundle\Entity\Article", mappedBy="author")
     */
    private $articles;

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param \SoftUniBlogBundle\Entity\Article $article
     *
     * @return User
     */
    public function addPost(Article $article)
    {
        $this->articles[] = $article;
        return $this;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255)
     */
    private $fullName;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="SoftUniBlogBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(name="users_roles",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id",referencedColumnName="id")}
     *     )
     */
    private $roles;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $stringRoles = [];
        foreach ($this->roles as $role) {
            /** @var $role Role */
            $stringRoles[] = is_string($role)?$role:$role->getRole();
        }
        return $stringRoles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @param \SoftUniBlogBundle\Entity\Role $role
     *
     * @return User
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;
        return $this;
    }

    /**
     * @param Article $article
     * @return bool
     */
    public function isAuthor(Article $article)
    {
        return $article->getAuthorId() == $this->getId();
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return in_array('ROLE_ADMIN', $this->getRoles());
    }

    public function __toString()
    {
        return $this->getFullName();
    }
}

