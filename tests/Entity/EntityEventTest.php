<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Entity;

use Doctrine\Persistence\ObjectManager;
use KejawenLab\Semart\Skeleton\Entity\PersistEntityEvent;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EntityEventTest extends TestCase
{
    public function testObject()
    {
        $objectManagerMock = $this->getMockBuilder(ObjectManager::class)->disableOriginalConstructor()->getMock();

        $object = new \stdClass();

        $filterEntity = new PersistEntityEvent($objectManagerMock, $object);

        $this->assertSame($object, $filterEntity->getEntity());
        $this->assertSame($objectManagerMock, $filterEntity->getManager());
    }
}
