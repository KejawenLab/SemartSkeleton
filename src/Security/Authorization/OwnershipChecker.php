<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Authorization;

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Annotation()
 * @Target({"CLASS", "METHOD"})
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OwnershipChecker
{
    private $token;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage->getToken();
    }

    public function isOwner(string $id, ServiceInterface $service): bool
    {
        if (!$this->token) {
            return false;
        }

        /** @var User $user */
        $user = $this->token->getUser();
        if (Group::SUPER_ADMINISTRATOR_CODE === $user->getGroup()->getCode()) {
            return true;
        }

        if (!$data = $service->get($id)) {
            return false;
        }

        $reflection = new \ReflectionObject($data);
        if (!$reflection->hasMethod('getCreatedBy')) {
            return false;
        }

        if ($data->getCreatedBy() === $user->getUsername()) {
            return true;
        }

        return false;
    }
}
