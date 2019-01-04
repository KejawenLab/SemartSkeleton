<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Service;

use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SuperAdministratorCheckerService
{
    private $token;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage->getToken();
    }

    public function isAdmin(): bool
    {
        if (!$this->token) {
            return false;
        }

        $user = $this->token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if (Group::SUPER_ADMINISTRATOR_CODE !== $user->getGroup()->getCode()) {
            return false;
        }

        return true;
    }
}
