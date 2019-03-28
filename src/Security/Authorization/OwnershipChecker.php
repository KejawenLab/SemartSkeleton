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
    private $username;

    private $token;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        if (!$token = $tokenStorage->getToken()) {
            throw new AccessDeniedException();
        }

        $this->token = $token;
        $this->username = $token->getUsername();
    }

    public function isOwner(string $id, ServiceInterface $service): bool
    {
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

        if ($data->getCreatedBy() === $this->username) {
            return true;
        }

        return false;
    }
}
