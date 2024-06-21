<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\Service;

use App\Const\ResponseMessage;
use App\Entity\Main\Api;
use App\Entity\Main\Menu;
use App\Entity\Main\Permission;
use App\Entity\Main\Role;
use App\Entity\Main\User;
use App\Exception\CustomException;
use App\Model\Vo\RoleVo;
use App\Model\Vo\RolePermissionVo;
use App\Repository\Main\ApiRepository;
use App\Repository\Main\MenuRepository;
use App\Repository\Main\PermissionRepository;
use App\Repository\Main\RoleRepository;
use App\Util\ArrayUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

class PermissionService
{
    private ApiRepository $apiRepository;
    private MenuRepository $menuRepository;
    private RoleRepository $roleRepository;
    private EntityManagerInterface $entityManager;
    private PermissionRepository $permissionRepository;

    public function __construct(
        ApiRepository          $apiRepository,
        MenuRepository         $menuRepository,
        RoleRepository         $roleRepository,
        PermissionRepository   $permissionRepository,
        EntityManagerInterface $entityManager,
    )
    {
        $this->apiRepository        = $apiRepository;
        $this->menuRepository       = $menuRepository;
        $this->roleRepository       = $roleRepository;
        $this->entityManager        = $entityManager;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * token中用户的权限
     * @param User $user
     * @return array|array[]
     */
    public function userPermissions(User $user): array
    {
        $result = [
            'pages'   => [],
            'buttons' => [],
        ];
        $pages  = $this->menuRepository->findBy(['type' => 'page', 'isPermission' => true]);
        foreach ($pages as $page) {
            $route                   = $page->getRoute();
            $result['pages'][$route] = false;
            foreach ($page->getPermissions() as $permission) {
                foreach ($permission->getRoles() as $role) {
                    if (true === in_array($role->getCode(), $user->getRoles(), true)) {
                        $result['pages'][$route] = true;
                    }
                }
            }
        }
        $buttons = $this->menuRepository->findBy(['type' => 'button', 'isPermission' => true]);
        foreach ($buttons as $button) {
            $route                     = $button->getRoute();
            $result['buttons'][$route] = false;
            foreach ($button->getPermissions() as $permission) {
                foreach ($permission->getRoles() as $role) {
                    if (true === in_array($role->getCode(), $user->getRoles(), true)) {
                        $result['buttons'][$route] = true;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 树形菜单
     * @return Menu[]
     */
    public function menus(): array
    {
        return $this->menuRepository->menus();
    }

    /**
     * Request观察者中权限检查
     * @param string $routeName
     * @param array $roles
     * @return bool
     */
    public function access(string $routeName, array $roles): bool
    {
        $api = $this->apiRepository->findOneBy(['routeName' => $routeName]);
        if (null === $api) {
            return true;
        }
        $access = false;
        foreach ($api->getPermissions() as $permission) {
            foreach ($permission->getRoles() as $role) {
                if (true === in_array($role->getCode(), $roles, true)) {
                    $access = true;
                    break;
                }
            }
            if (true === $access) {
                break;
            }
        }
        return $access;
    }

    /**
     * 所有用户的权限，权限页面回显数据用
     * @param string|null $roleCode
     * @return array
     */
    public function rolesPermission(?string $roleCode = null): array
    {
        $result  = [];
        $roles   = $this->roleRepository->findAll();
        $pages   = $this->menuRepository->findBy(['type' => 'page', 'isPermission' => true]);
        $buttons = $this->menuRepository->findBy(['type' => 'button', 'isPermission' => true]);
        $pages   = $this->mappingMenu($pages);
        $buttons = $this->mappingMenu($buttons);
        foreach ($roles as $role) {
            $result[$role->getCode()]['role_id'] = $role->getId();
            $result[$role->getCode()]['pages']   = $pages;
            $result[$role->getCode()]['buttons'] = $buttons;
            foreach ($role->getMenus() as $menu) {
                $id = $menu->getId();
                if ('button' === $menu->getType() && true === array_key_exists($id, $buttons)) {
                    $result[$role->getCode()]['buttons'][$id]['permission'] = true;
                } else if ('page' === $menu->getType() && true === array_key_exists($id, $pages)) {
                    $result[$role->getCode()]['pages'][$id]['permission'] = true;
                }
            }
        }
        if (null !== $roleCode && true === array_key_exists($roleCode, $result)) {
            return [$roleCode => $result[$roleCode]];
        }
        return $result;
    }

    public function addRole(RoleVo $roleDao): Role
    {
        // unique
        $exist = $this->roleRepository->findOneBy(['code' => $roleDao->getCode()]);
        if (null !== $exist) {
            throw new CustomException(ResponseMessage::USER_ROLE_CODE_DUPLICATE);
        }
        $role = new Role();
        $role->setCode($roleDao->getCode());
        $role->setComment($roleDao->getComment());
        return $role;
    }

    public function roles(): array
    {
        return $this->roleRepository->findAll();
    }

    public function refreshRolePermission(RolePermissionVo $dao): Role
    {
        $role = $this->roleRepository->find($dao->getRoleId());
        if (null === $role) {
            throw new BadRequestHttpException('The role not exist.');
        }
        $role->clearMenus();
        $role->clearPermissions();
        foreach ($dao->getPages() as $pageId) {
            $page = $this->menuRepository->find($pageId);
            if (null === $page || false === $page->isPermission()) {
                throw new BadRequestHttpException('The page not exist.');
            }
            $role->addMenu($page);
            foreach ($page->getPermissions() as $permission) {
                $role->addPermission($permission);
            }
        }
        return $role;
    }

    public function isPermissionApi(string $routeName): bool
    {
        $api = $this->apiRepository->findOneBy(['routeName' => $routeName]);
        return null !== $api;
    }

    public function permissionsForVoter(UserInterface $user): array
    {
        $result = [];
        $roles  = $user->getRoles();
        foreach ($roles as $value) {
            $role = $this->roleRepository->findOneBy(['code' => $value]);
            if (null !== $role) {
                foreach ($role->getPermissions() as $permission) {
                    $result[] = $permission->getName();
                }
            }
        }
        return array_unique($result);
    }

    public function yaml2database($data): void
    {
        $permissions = [];
        foreach ($data['app_permission'] as $value) {
            $useVoter = false;
            if (array_key_exists('use_voter', $value) && true === $value['use_voter']) {
                $useVoter = true;
            }
            if (isset($value['id'])) {
                $permission = $this->permissionRepository->find($value['id']);
                if (null === $permission) {
                    throw new \LogicException("permission id: {$value['id']}不存在");
                }
            } else {
                $permission = new Permission();
            }
            $permission->setName($value['name']);
            $permission->setUseVoter($useVoter);
            $permission->setComment($value['comment']);
            if (null === $permission->getId()) {
                $this->entityManager->persist($permission);
            }
            $permissions[$value['name']] = $permission;
        }
        foreach ($data['app_api'] as $value) {
            if (isset($value['id'])) {
                $api = $this->apiRepository->find($value['id']);
                if (null === $api) {
                    throw new \LogicException("api id: {$value['id']}不存在");
                }
            } else {
                $api = new Api();
            }

            $api->setRouteName($value['route_name']);
            $api->setRoute($value['route']);
            $api->setComment($value['comment']);
            $api->setMethod($value['method']);
            foreach ($value['permission'] as $pName) {
                $api->clearPermission();
                $permission = $permissions[$pName];
                if (null === $permission) {
                    throw new \LogicException("{$value['route_name']}需要{$pName}权限");
                }
                $api->addPermission($permission);
            }
            if (null === $api->getId()) {
                $this->entityManager->persist($api);
            }
        }
        $menus = $this->setMenu($data['app_menu'], $permissions);
        foreach ($menus as $menu) {
            if (null === $menu->getId()) {
                $this->entityManager->persist($menu);
            }
        }
        $this->entityManager->flush();
    }


    private function setMenu($menus, array $permissions): array
    {
        $result = [];
        foreach ($menus as $value) {
            if (isset($value['id'])) {
                $menu = $this->menuRepository->find($value['id']);
                if (null === $menu) {
                    throw new \LogicException("menu id: {$value['id']}不存在");
                }
            } else {
                $menu = new Menu();
            }
            $menu->setRoute($value['route']);
            $menu->setType($value['type']);
            $menu->setComment($value['comment']);
            $menu->setOrderNum($value['order'] ?? null);
            if (array_key_exists('permission', $value)) {
                $menu->setIsPermission(1);
                $menu->clearPermission();
                foreach ($value['permission'] ?? [] as $pName) {
                    $permission = $permissions[$pName];
                    if (null === $permission) {
                        throw new \LogicException("{$value['route_name']}需要{$pName}权限");
                    }
                    $menu->addPermission($permission);
                }
            } else {
                $menu->clearPermission();
                $menu->setIsPermission(0);
            }
            if (array_key_exists('children', $value) && true === ArrayUtil::isValid($value['children'])) {
                $children = $this->setMenu($value['children'], $permissions);
                foreach ($children as $child) {
                    $menu->addChild($child);
                }
            }
            $result[] = $menu;
        }
        return $result;
    }

    /**
     * @param Menu[] $data
     * @return array
     */
    private function mappingMenu(array $data): array
    {
        $result = [];
        foreach ($data as $menu) {
            $result[$menu->getId()] = [
                'route'      => $menu->getRoute(),
                'permission' => false
            ];
        }
        return $result;
    }
}