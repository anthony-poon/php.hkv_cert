<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
/**
 * @ORM\Table(name="user_roles")
 * @ORM\Entity
 */
class UserRole extends Role{
    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ManyToMany(targetEntity="User", mappedBy="user_roles")
     * @ORM\JoinTable(name="user_roles_mapping")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=128, unique=true, nullable=True)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=128, unique=true, nullable=True, name="role_name")
     */
    private $role;

    public function __construct(string $role) {
        parent::__construct($role);
        $this->users = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @return self
     * @param string
     */
    public function setDescription(string $description): self {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string {
        return $this->role;
    }

    /**
     * @return self
     * @param string
     */
    public function setRole(string $role): self {
        $this->role = $role;
        return $this;
    }


}