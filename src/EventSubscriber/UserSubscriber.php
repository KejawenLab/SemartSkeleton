<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Request\FilterRequest;
use KejawenLab\Semart\Skeleton\Security\Service\GroupService;
use KejawenLab\Semart\Skeleton\Security\Service\PasswordEncoderService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UserSubscriber implements EventSubscriberInterface
{
    private $passwordEncoder;

    private $groupService;

    public function __construct(PasswordEncoderService $encodePasswordService, GroupService $groupService)
    {
        $this->passwordEncoder = $encodePasswordService;
        $this->groupService = $groupService;
    }

    public function filterRequest(FilterRequest $event)
    {
        $request = $event->getRequest();
        $user = $event->getObject();
        if (!$user instanceof User) {
            return;
        }

        if ($group = $this->groupService->find($request->request->get('group'))) {
            $user->setGroup($group);
        }

        $request->request->remove('group');
    }

    public function setPassword(FilterRequest $event)
    {
        $user = $event->getObject();
        if (!$user instanceof User) {
            return;
        }

        $this->passwordEncoder->encode($user);
    }

    public static function getSubscribedEvents()
    {
        return [
            Application::REQUEST_EVENT => [['filterRequest']],
            Application::PRE_VALIDATION_EVENT => [['setPassword']],
        ];
    }
}
