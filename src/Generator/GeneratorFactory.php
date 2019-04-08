<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Generator;

use KejawenLab\Semart\Skeleton\Contract\Generator\GeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GeneratorFactory
{
    private $generators = [];

    public function __construct(array $generators = [])
    {
        foreach ($generators as $generator) {
            $this->addGenerator($generator);
        }
    }

    public function generate(\ReflectionClass $entityClass): void
    {
        /** @var GeneratorInterface $generator */
        foreach ($this->generators as $generator) {
            $generator->generate($entityClass);
        }
    }

    private function addGenerator(GeneratorInterface $generator)
    {
        $index = 255 === $generator->getPriority() ?: \count($this->generators);

        $this->generators[$index] = $generator;
    }
}
