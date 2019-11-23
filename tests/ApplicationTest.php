<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests;

use KejawenLab\Semart\Skeleton\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ApplicationTest extends WebTestCase
{
    public function testConst()
    {
        $this->assertEquals('semart', Application::APP_UNIQUE_NAME);
    }
}
