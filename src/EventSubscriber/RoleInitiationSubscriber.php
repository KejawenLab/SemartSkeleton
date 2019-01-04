<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use KejawenLab\Semart\Skeleton\AppEvent;
use KejawenLab\Semart\Skeleton\Entity\FilterEntity;
use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Security\Service\RoleService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RoleInitiationSubscriber implements EventSubscriberInterface
{
    private $roleAssigner;

    public function __construct(RoleService $roleAssigner)
    {
        $this->roleAssigner = $roleAssigner;
    }

    public function initiate(FilterEntity $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof Group && !$entity->getId()) {
            $this->roleAssigner->assignToGroup($entity);
        }

        if ($entity instanceof Menu && !$entity->getId()) {
            $this->roleAssigner->assignToMenu($entity);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            AppEvent::PRE_COMMIT_EVENT => [['initiate']],
        ];
    }
}
