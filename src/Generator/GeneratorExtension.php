<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Generator;

use Doctrine\Common\Inflector\Inflector;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GeneratorExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'pluralize']),
            new TwigFunction('humanize', [$this, 'humanize']),
            new TwigFunction('underscore', [$this, 'underscore']),
            new TwigFunction('dash', [$this, 'dash']),
            new TwigFunction('camelcase', [$this, 'camelcase']),
        ];
    }

    public function pluralize(string $value): string
    {
        return Inflector::pluralize($value);
    }

    public function humanize(string $value): string
    {
        return ucfirst($value);
    }

    public function underscore(string $value): string
    {
        return Inflector::tableize($value);
    }

    public function dash(string $value): string
    {
        return str_replace('_', '-', Inflector::tableize($value));
    }

    public function camelcase(string $value): string
    {
        return Inflector::camelize($value);
    }
}
