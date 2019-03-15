<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Service;

use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Entity\Role;
use KejawenLab\Semart\Skeleton\Repository\GroupRepository;
use KejawenLab\Semart\Skeleton\Repository\MenuRepository;
use KejawenLab\Semart\Skeleton\Repository\RoleRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RoleService
{
    private $roleRepository;

    private $menuRepository;

    private $groupRepository;

    public function __construct(RoleRepository $roleRepository, MenuRepository $menuRepository, GroupRepository $groupRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->menuRepository = $menuRepository;
        $this->groupRepository = $groupRepository;
    }

    public function assignToGroup(Group $group): void
    {
        $menus = $this->menuRepository->findAll();
        /** @var Menu $menu */
        foreach ($menus as $key => $menu) {
            $role = new Role();
            $role->setGroup($group);
            $role->setMenu($menu);

            $this->roleRepository->persist($role);

            if ($key > 0 && 0 === 17 % $key) {
                $this->roleRepository->commit();
            }
        }

        $this->roleRepository->commit();
    }

    public function assignToMenu(Menu $menu): void
    {
        $groups = $this->groupRepository->findAll();
        /** @var Group $group */
        foreach ($groups as $key => $group) {
            $role = new Role();
            $role->setGroup($group);
            $role->setMenu($menu);

            $this->roleRepository->persist($role);

            if ($key > 0 && 0 === 17 % $key) {
                $this->roleRepository->commit();
            }
        }

        $this->roleRepository->commit();
    }

    public function addRole(Role $role): void
    {
        $this->roleRepository->persist($role);
        $this->roleRepository->commit();
    }
}
