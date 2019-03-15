<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Service;

use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Repository\GroupRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GroupService
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function getSuperAdmin(): Group
    {
        return $this->groupRepository->findOneBy(['code' => Group::SUPER_ADMINISTRATOR_CODE]);
    }

    public function addGroup(Group $group): void
    {
        $this->groupRepository->commit($group);
    }

    /**
     * @return Group[]
     */
    public function getActiveGroups(): array
    {
        return $this->groupRepository->findAll();
    }
}
