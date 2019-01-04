<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Security\Authorization;

use KejawenLab\Semart\Skeleton\Security\Authorization\SuperAdministratorExtension;
use KejawenLab\Semart\Skeleton\Security\Service\SuperAdministratorCheckerService;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SuperAdministratorExtensionTest extends TestCase
{
    public function testIsAdmin()
    {
        $serviceMock = $this->getMockBuilder(SuperAdministratorCheckerService::class)->disableOriginalConstructor()->getMock();
        $serviceMock
            ->expects($this->at(0))
            ->method('isAdmin')
            ->willReturn(false)
        ;
        $serviceMock
            ->expects($this->at(1))
            ->method('isAdmin')
            ->willReturn(true)
        ;

        $extension = new SuperAdministratorExtension($serviceMock);

        $this->assertFalse($extension->isAdmin());
        $this->assertTrue($extension->isAdmin());
    }

    public function testGetFunctions()
    {
        $serviceMock = $this->getMockBuilder(SuperAdministratorCheckerService::class)->disableOriginalConstructor()->getMock();

        $this->assertCount(1, (new SuperAdministratorExtension($serviceMock))->getFunctions());
    }
}
