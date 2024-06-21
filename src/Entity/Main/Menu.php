<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\Entity\Main;

use App\Repository\Main\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'app_menu')]
#[ORM\UniqueConstraint(columns: ['route'])]
#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[Serializer\Groups(['permission.menus'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Serializer\Groups(['permission.menus'])]
    #[ORM\Column(length: 255)]
    private ?string $route = null;

    #[Serializer\Groups(['permission.menus'])]
    #[Assert\Choice(['menu', 'page', 'button'])]
    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[Serializer\Groups(['permission.menus'])]
    #[ORM\Column(type: Types::SMALLINT, length: 2, nullable: true)]
    private ?int $orderNum = null;

    #[Serializer\Groups(['permission.menus'])]
    #[ORM\Column(length: 255)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?int $parentId = 0;

    #[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'menus')]
    private Collection $permissions;


    #[Serializer\Groups(['permission.menus'])]
    #[ORM\Column]
    private bool $isPermission = false;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    #[Serializer\Accessor(getter: 'getChildren')]
    #[Serializer\Groups(['permission.menus'])]
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class, cascade: ['persist'])]
    private Collection $children;


    public function __construct()
    {
        $this->permissions = new ArrayCollection();
        $this->children    = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): static
    {
        $this->parentId = $parentId;

        return $this;
    }


    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): void
    {
        $this->route = $route;
    }

    public function getOrderNum(): ?int
    {
        return $this->orderNum;
    }

    public function setOrderNum(?int $order): void
    {
        $this->orderNum = $order;
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

    public function clearPermission(): static
    {
        $this->permissions->clear();
        return $this;
    }

    public function isPermission(): bool
    {
        return $this->isPermission;
    }

    public function setIsPermission(bool $isPermission): void
    {
        $this->isPermission = $isPermission;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        $children = $this->children->toArray();
        usort($children, function ($a, $b) {
            return $a->getOrderNum() <=> $b->getOrderNum();
        });
        $this->children = new ArrayCollection($children);
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }
}
