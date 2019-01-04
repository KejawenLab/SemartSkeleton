<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Security\Authorization;

use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Security\Authorization\ShowDeletedRecordPermission;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\FilterCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ShowDeletedRecordPermissionTest extends TestCase
{
    public function testShowDeletedRecord()
    {
        $group = new Group();
        $group->setCode(Group::SUPER_ADMINISTRATOR_CODE);

        $user = new User();
        $user->setGroup($group);

        $tokenMock = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $tokenMock
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user)
        ;

        $tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)->disableOriginalConstructor()->getMock();
        $tokenStorageMock
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($tokenMock)
        ;

        $filtersMock = $this->getMockBuilder(FilterCollection::class)->disableOriginalConstructor()->getMock();
        $filtersMock
            ->expects($this->once())
            ->method('isEnabled')
            ->with(ShowDeletedRecordPermission::SOFTDELETABLE_FILTER_NAME)
            ->willReturn(true)
        ;
        $filtersMock
            ->expects($this->once())
            ->method('disable')
            ->with(ShowDeletedRecordPermission::SOFTDELETABLE_FILTER_NAME)
        ;

        $managerMock = $this->getMockBuilder(EntityManagerInterface::class)->disableOriginalConstructor()->getMock();
        $managerMock
            ->expects($this->once())
            ->method('getFilters')
            ->willReturn($filtersMock)
        ;

        $this->assertNull((new ShowDeletedRecordPermission($tokenStorageMock, $managerMock))->showDeletedRecord());
    }

    public function testGetSubscribedEvents()
    {
        $this->assertCount(1, ShowDeletedRecordPermission::getSubscribedEvents());
    }
}
