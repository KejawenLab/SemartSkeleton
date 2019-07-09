<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Pagination;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Pagination\PaginationEvent;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PaginationEventTest extends KernelTestCase
{
    public function testFilterPagination()
    {
        static::bootKernel();

        $filterPagination = new PaginationEvent();
        $filterPagination->setRequest(Request::createFromGlobals());
        $filterPagination->setQueryBuilder(new QueryBuilder(static::$container->get('doctrine.orm.entity_manager')));
        $filterPagination->setEntityClass(User::class);

        $this->assertInstanceOf(Event::class, $filterPagination);
        $this->assertInstanceOf(Request::class, $filterPagination->getRequest());
        $this->assertInstanceOf(QueryBuilder::class, $filterPagination->getQueryBuilder());
        $this->assertEquals(User::class, $filterPagination->getEntityClass());
    }
}
