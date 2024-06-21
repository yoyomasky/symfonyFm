<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\Entity\Main;

use App\Repository\Main\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Table(name: 'app_role')]
#[ORM\UniqueConstraint(columns: ['code'])]
#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[Serializer\Groups(['permission.roles'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Serializer\Groups(['permission.roles'])]
    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[Serializer\Groups(['permission.roles'])]
    #[ORM\Column(length: 255)]
    private ?string $comment = null;

    #[ORM\ManyToMany(targetEntity: Permission::class)]
    private Collection $permissions;

    #[ORM\ManyToMany(targetEntity: Menu::class)]
    private Collection $menus;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
        $this->menus       = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, Permission>
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(Permission $permission): static
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }

        return $this;
    }

    public function removePermission(Permission $permission): static
    {
        $this->permissions->removeElement($permission);

        return $this;
    }

    public function clearPermissions(): static
    {
        $this->permissions->clear();

        return $this;
    }

    public function clearMenus(): static
    {
        $this->menus->clear();

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
        }

        return $this;
    }

    public function removeMenu(Menu $menu): static
    {
        $this->menus->removeElement($menu);

        return $this;
    }
}
