<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\Model\Vo;


use Symfony\Component\Validator\Constraints as Assert;

class RolePermissionVo
{
    #[Assert\NotBlank()]
    private ?int $roleId = null;

    #[Assert\NotBlank()]
    private ?array $pages = null;

    public function getRoleId(): ?int
    {
        return $this->roleId;
    }

    public function setRoleId(?int $roleId): void
    {
        $this->roleId = $roleId;
    }

    public function getPages(): ?array
    {
        return $this->pages;
    }

    public function setPages(?array $pages): void
    {
        $this->pages = $pages;
    }
}