<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\EventSubscriber;

use App\Service\PermissionService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RequestSubscriber implements EventSubscriberInterface
{
    private PermissionService $permissionService;
    private Security $security;

    public function __construct(
        PermissionService $permissionService,
        Security          $security,
    )
    {
        $this->permissionService = $permissionService;
        $this->security          = $security;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        // 接口权限检查
        $this->checkPermission($request->attributes->get('_route'));
        // 空字符串转为null
        $parameters = $request->query->all();
        foreach ($parameters as $k => $v) {
            if (is_string($v) && '' === trim($v)) {
                $request->query->set($k, null);
            }
        }
        // trace_id
        $request->attributes->set('trace_id', $this->generateTraceId());
        // 解决无查询字符串时无法初始化DTO的问题
        $request->query->set(1, 1);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    private function generateTraceId(): string
    {
        return bin2hex(random_bytes(16));
    }

    private function checkPermission(string $routeName): void
    {
        if (false === $this->permissionService->isPermissionApi($routeName)) {
            return;
        }
        $roles = $this->security->getUser()->getRoles();
        if (false === $this->permissionService->access($routeName, $roles)) {
            throw new AccessDeniedException();
        }
    }
}
