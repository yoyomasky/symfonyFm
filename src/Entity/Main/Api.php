<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\Entity\Main;

use App\Repository\Main\ApiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'app_api')]
#[ORM\UniqueConstraint(columns: ['route_name'])]
#[ORM\Entity(repositoryClass: ApiRepository::class)]
class Api
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $routeName = null;

    #[ORM\Column(length: 255)]
    private ?string $route = null;

    #[ORM\Column(type: Types::JSON)]
    private ?array $method = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'apis')]
    private Collection $permissions;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): static
    {
        $this->routeName = $routeName;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function getMethod(): ?array
    {
        return $this->method;
    }

    public function setMethod(array $method): static
    {
        $this->method = $method;

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

    public function clearPermission(): static
    {
        $this->permissions->clear();
        return $this;
    }
}
