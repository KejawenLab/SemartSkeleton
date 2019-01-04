<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Authorization;

use KejawenLab\Semart\Skeleton\Security\Service\SuperAdministratorCheckerService;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SuperAdministratorExtension extends \Twig_Extension
{
    private $superAdminChecker;

    public function __construct(SuperAdministratorCheckerService $superAdminChecker)
    {
        $this->superAdminChecker = $superAdminChecker;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('is_admin', [$this, 'isAdmin']),
        ];
    }

    public function isAdmin()
    {
        return $this->superAdminChecker->isAdmin();
    }
}
