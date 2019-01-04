<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Generator;

use Doctrine\Common\Inflector\Inflector;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GeneratorExtension extends \Twig_Extension
{
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter('pluralize', [$this, 'pluralize']),
            new \Twig_SimpleFilter('humanize', [$this, 'humanize']),
            new \Twig_SimpleFilter('underscore', [$this, 'underscore']),
            new \Twig_SimpleFilter('dash', [$this, 'dash']),
            new \Twig_SimpleFilter('camelcase', [$this, 'camelcase']),
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
