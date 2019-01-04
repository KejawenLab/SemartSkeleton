<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use Doctrine\Common\Annotations\Reader;
use KejawenLab\Semart\Skeleton\Menu\MenuService;
use KejawenLab\Semart\Skeleton\Repository\MenuRepository;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AuthorizationSubscriber implements EventSubscriberInterface
{
    private $reader;

    private $menuService;

    private $authorizationChecker;

    public function __construct(Reader $reader, MenuService $menuService, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->reader = $reader;
        $this->menuService = $menuService;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function authorize(FilterControllerEvent $event)
    {
        if (!is_array($controllerObject = $event->getController())) {
            return;
        }

        $controller = new \ReflectionObject($controllerObject[0]);
        $method = $controller->getMethod($controllerObject[1]);

        /** @var Permission $classAnnotation */
        $classAnnotation = $this->reader->getClassAnnotation($controller, Permission::class);
        /** @var Permission $methodAnnotation */
        $methodAnnotation = $this->reader->getMethodAnnotation($method, Permission::class);

        if ($classAnnotation && $methodAnnotation) {
            $authorize = 0;
            foreach ($methodAnnotation->getActions() as $action) {
                if ($this->authorizationChecker->isGranted($action, $this->menuService->getMenuByCode($classAnnotation->getMenu()))) {
                    $authorize++;
                }
            }

            if (0 === $authorize) {
                throw new AccessDeniedException();
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => [['authorize']],
        ];
    }
}
