<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Menu;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuServiceAbstract extends KernelTestCase
{
    public function setUp()
    {
        static::bootKernel();
    }
}
