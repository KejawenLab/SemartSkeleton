<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Util;

use KejawenLab\Semart\Skeleton\Application;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
final class UniqueAlias
{
    public static function generate(array $excludes = []): string
    {
        $random = Application::APP_UNIQUE_NAME;
        $alias = $random[rand(0, \strlen($random) - 1)];

        if (in_array($alias, $excludes)) {
            return self::generate($excludes);
        }

        return $alias;
    }
}
