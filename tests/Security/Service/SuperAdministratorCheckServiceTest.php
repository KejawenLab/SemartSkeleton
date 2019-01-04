<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Security\Service;

use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Security\Service\SuperAdministratorCheckerService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SuperAdministratorCheckServiceTest extends TestCase
{
    public function testIsAdmin()
    {
        $superAdministratorCheckerService = new SuperAdministratorCheckerService($this->getTokenStorageMock(Group::SUPER_ADMINISTRATOR_CODE));

        $this->assertTrue($superAdministratorCheckerService->isAdmin());
    }

    public function testIsNotAdmin()
    {
        $superAdministratorCheckerService = new SuperAdministratorCheckerService($this->getTokenStorageMock('Test'));

        $this->assertFalse($superAdministratorCheckerService->isAdmin());
    }

    private function getTokenStorageMock(string $groupCode): MockObject
    {
        $group = new Group();
        $group->setCode($groupCode);

        $user = new User();
        $user->setGroup($group);

        $tokenMock = $this->getMockBuilder(TokenInterface::class)->getMock();
        $tokenMock
            ->expects($this->atLeastOnce())
            ->method('getUser')
            ->willReturn($user)
        ;

        $tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $tokenStorageMock
            ->expects($this->atLeastOnce())
            ->method('getToken')
            ->willReturn($tokenMock)
        ;

        return $tokenStorageMock;
    }
}
