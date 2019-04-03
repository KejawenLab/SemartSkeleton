<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Service;

use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Security\Authorization\OwnershipChecker;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OwnershipService
{
    private $ownershipChecker;

    private $application;

    public function __construct(OwnershipChecker $ownershipChecker, Application $application)
    {
        $this->ownershipChecker = $ownershipChecker;
        $this->application = $application;
    }

    public function isOwner(Request $request, \ReflectionMethod $reflectionMethod): bool
    {
        $arguments = $reflectionMethod->getParameters();
        /** @var string|null $id */
        $id = null;
        /** @var ServiceInterface $service */
        $service = null;
        foreach ($arguments as $argument) {
            if ($id && $service) {
                break;
            }

            if (! $argumentType = $argument->getType()) {
                continue;
            }

            if ('id' === $argument->getName() && 'string' === $argumentType->getName()) {
                $id = $request->get($argument->getName());
            }

            if ('service' === $argument->getName()) {
                $reflectionClass = new \ReflectionClass($argumentType->getName());
                if ($reflectionClass->implementsInterface(ServiceInterface::class)) {
                    $service = $this->application->getService($reflectionClass);
                }
            }
        }

        if ($id && $service) {
            return $this->ownershipChecker->isOwner($id, $service);
        }

        return true;
    }
}
