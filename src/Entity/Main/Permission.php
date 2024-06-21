<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\Entity\Main;

use App\Repository\Main\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'app_permission')]
#[ORM\UniqueConstraint(columns: ['name'])]
#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private bool $useVoter = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToMany(targetEntity: Api::class, mappedBy: 'permissions')]
    private Collection $apis;

    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'permissions')]
    private Collection $menus;

    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: 'permissions')]
    private Collection $roles;

    public function __construct()
    {
        $this->apis  = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, Api>
     */
    public function getApis(): Collection
    {
        return $this->apis;
    }

    public function addApi(Api $api): static
    {
        if (!$this->apis->contains($api)) {
            $this->apis->add($api);
            $api->addPermission($this);
        }

        return $this;
    }

    public function removeApi(Api $api): static
    {
        if ($this->apis->removeElement($api)) {
            $api->removePermission($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): static
    {
        if (!$this->menus->contains($menu)) {
            $this->menus->add($menu);
            $menu->addPermission($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): static
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removePermission($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): static
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
            $role->addPermission($this);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        if ($this->roles->removeElement($role)) {
            $role->removePermission($this);
        }

        return $this;
    }

    public function isUseVoter(): bool
    {
        return $this->useVoter;
    }

    public function setUseVoter(bool $useVoter): void
    {
        $this->useVoter = $useVoter;
    }
}
