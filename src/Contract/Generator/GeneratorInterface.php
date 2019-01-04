<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Contract\Generator;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface GeneratorInterface
{
    public function generate(\ReflectionClass $entityClass): void;
}
