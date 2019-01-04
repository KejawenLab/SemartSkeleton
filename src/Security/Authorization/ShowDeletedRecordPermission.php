<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Authorization;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ShowDeletedRecordPermission implements EventSubscriberInterface
{
    const SOFTDELETABLE_FILTER_NAME = 'semart_softdeletable';

    private $token;

    /**
     * @var EntityManagerInterface
     */
    private $objectManager;

    public function __construct(TokenStorageInterface $tokenStorage, ObjectManager $objectManager)
    {
        $this->token = $tokenStorage->getToken();
        $this->objectManager = $objectManager;
    }

    public function showDeletedRecord(): void
    {
        if (!$this->token) {
            return;
        }

        $user = $this->token->getUser();
        if ($user instanceof User) {
            if (Group::SUPER_ADMINISTRATOR_CODE === $user->getGroup()->getCode()) {
                $filters = $this->objectManager->getFilters();

                if ($filters->isEnabled(self::SOFTDELETABLE_FILTER_NAME)) {
                    $filters->disable(self::SOFTDELETABLE_FILTER_NAME);
                }
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'showDeletedRecord',
        ];
    }
}
