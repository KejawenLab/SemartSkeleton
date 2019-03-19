<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Application
{
    public const APP_UNIQUE_NAME = 'semart';

    public const REQUEST_EVENT = 'app.request';
    public const PRE_VALIDATION_EVENT = 'app.pre_validation';
    public const PAGINATION_EVENT = 'app.pagination';
    public const PRE_COMMIT_EVENT = 'app.pre_commit';
}
