<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Generator;

use KejawenLab\Semart\Skeleton\Contract\Generator\GeneratorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ControllerGenerator implements GeneratorInterface
{
    private $twig;

    private $fileSystem;

    private $kernel;

    public function __construct(\Twig_Environment $twig, Filesystem $fileSystem, KernelInterface $kernel)
    {
        $this->twig = $twig;
        $this->fileSystem = $fileSystem;
        $this->kernel = $kernel;
    }

    public function generate(\ReflectionClass $entityClass): void
    {
        $template = $this->twig->render('generator/controller.php.twig', ['entity' => $entityClass->getShortName()]);

        $this->fileSystem->dumpFile(sprintf('%s/src/Controller/Admin/%sController.php', $this->kernel->getProjectDir(), $entityClass->getShortName()), $template);
    }
}
