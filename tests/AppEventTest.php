<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests;

use KejawenLab\Semart\Skeleton\AppEvent;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AppEventTest extends WebTestCase
{
    public function testConst()
    {
        $this->assertEquals('app.request', AppEvent::REQUEST_EVENT);
        $this->assertEquals('app.pre_validation', AppEvent::PRE_VALIDATION_EVENT);
        $this->assertEquals('app.pagination', AppEvent::PAGINATION_EVENT);
        $this->assertEquals('app.pre_commit', AppEvent::PRE_COMMIT_EVENT);
    }
}
