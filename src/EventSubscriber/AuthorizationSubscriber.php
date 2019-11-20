<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use Doctrine\Common\Annotations\Reader;
use KejawenLab\Semart\Collection\Collection;
use KejawenLab\Semart\Skeleton\Controller\Admin\AdminController;
use KejawenLab\Semart\Skeleton\Menu\MenuService;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use KejawenLab\Semart\Skeleton\Security\Service\OwnershipService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AuthorizationSubscriber implements EventSubscriberInterface
{
    private $reader;

    private $menuService;

    private $ownershipService;

    private $authorizationChecker;

    public function __construct(Reader $reader, MenuService $menuService, OwnershipService $ownershipService, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->reader = $reader;
        $this->menuService = $menuService;
        $this->ownershipService = $ownershipService;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function authorize(ControllerEvent $event)
    {
        $controllerArray = $event->getController();
        if (!\is_array($controllerArray)) {
            return;
        }

        $controller = $controllerArray[0];
        if (!$controller instanceof AdminController) {
            return;
        }

        $controllerReflection = new \ReflectionObject($controller);
        $method = $controllerReflection->getMethod($controllerArray[1]);

        /** @var Permission|null $classAnnotation */
        $classAnnotation = $this->reader->getClassAnnotation($controllerReflection, Permission::class);
        /** @var Permission|null $methodAnnotation */
        $methodAnnotation = $this->reader->getMethodAnnotation($method, Permission::class);

        if ($classAnnotation && $methodAnnotation) {
            $authorize = 0;
            Collection::collect($methodAnnotation->getActions())
                ->each(function ($value) use (&$authorize, &$controller, $classAnnotation) {
                    $menu = $this->menuService->getMenuByCode($classAnnotation->getMenu());
                    $controller->setPageTitle($menu->getName());

                    if ($this->authorizationChecker->isGranted($value, $menu)) {
                        ++$authorize;
                    }
                })
            ;

            if (0 === $authorize) {
                throw new AccessDeniedException();
            }
        }

        if ($classAnnotation && $classAnnotation->isOwnership()) {
            if (!$this->ownershipService->isOwner($event->getRequest(), $method)) {
                throw new AccessDeniedException();
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'authorize',
        ];
    }
}
