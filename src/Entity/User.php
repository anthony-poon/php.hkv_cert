<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 18/5/2018
 * Time: 5:09 PM
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\UserRole;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable {

    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *      pattern="/^[\w_\.]+$/",
     *      message="Username contained invalid character"
     * )
     */
    private $username;

    /**
     * @ManyToMany(targetEntity="UserRole", inversedBy="users")
     * @JoinTable(name="user_roles_mapping")
     */
    private $userRoles;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max=4096,
     *     min=5,
     *     maxMessage="Password too long",
     *     minMessage="Passowrd too short (5 characters or more)"
     * )
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=128, unique=true, nullable=True)
     * @Assert\Email()
     */
    private $email = Null;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = True;

    public function __construct() {
        $this->userRoles = new ArrayCollection();
    }

    public function serialize() {
        return serialize([
            "id" => $this->id,
            "username" => $this->username,
            "password" => $this->password
        ]);
    }

    public function unserialize($serialized) {
        $arr = unserialize($serialized, ['allowed_classes' => false]);
        $this->id = $arr["id"];
        $this->username = $arr["username"];
        $this->password = $arr["password"];
    }

    public function getUserRoles() {
        return $this->userRoles;
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
    public function getRoles() {
        return $this->userRoles->getValues();
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt() {
        return Null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials() {
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setUsername(string $username): self {
        $this->username = $username;
        return $this;
    }

    public function setPassword(string $password): self {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): self {
        $this->email = $email;

        return $this;
    }

    public function getIsActive(): ?bool {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return User
     */
    public function setPlainPassword(string $plainPassword): self {
        $this->plainPassword = $plainPassword;
        return $this;
    }
}