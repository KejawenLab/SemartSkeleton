<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Pagination;

use KejawenLab\Semart\Skeleton\Pagination\PaginationExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PaginationExtensionTest extends TestCase
{
    public function testStartPageNumber()
    {
        $request = Request::createFromGlobals();
        $request->query->set('page', 1);

        $requestStackMock = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStackMock
            ->expects($this->atLeastOnce())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $this->assertEquals(1, (new PaginationExtension($requestStackMock))->startPageNumber());

        $request->query->set('page', 2);

        $this->assertEquals(18, (new PaginationExtension($requestStackMock))->startPageNumber());
    }

    public function testGetFunctions()
    {
        $request = Request::createFromGlobals();
        $request->query->set('page', 1);

        $requestStackMock = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStackMock
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $this->assertCount(1, (new PaginationExtension($requestStackMock))->getFunctions());
    }
}
