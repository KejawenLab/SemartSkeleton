<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Menu;

use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Repository\RoleRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuLoader
{
    private $roleRepository;

    private $tokenStorage;

    private $childMenu;

    public function __construct(RoleRepository $roleRepository, TokenStorageInterface $tokenStorage)
    {
        $this->roleRepository = $roleRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return Menu[]
     */
    public function getParentMenu(): array
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return [];
        }

        /** @var User $user */
        $user = $token->getUser();
        if (!$group = $user->getGroup()) {
            return [];
        }

        return $this->roleRepository->findParentMenuByGroup($group);
    }

    public function hasChildMenu(Menu $menu): bool
    {
        $this->childMenu = $this->findChildMenu($menu);
        if (empty($this->childMenu)) {
            return false;
        }

        return true;
    }

    /**
     * @param Menu $menu
     *
     * @return Menu[]
     */
    public function getChildMenu(Menu $menu): array
    {
        if (null === $this->childMenu) {
            return $this->findChildMenu($menu);
        }

        $childs = $this->childMenu;
        $this->childMenu = null;

        return $childs;
    }

    private function findChildMenu(Menu $menu): array
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return [];
        }

        /** @var User $user */
        $user = $token->getUser();
        if (!$group = $user->getGroup()) {
            return [];
        }

        return $this->roleRepository->findChildMenuByGroupAndMenu($group, $menu);
    }
}
