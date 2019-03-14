<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Application
{
    const APP_UNIQUE_NAME = 'semart';

    const REQUEST_EVENT = 'app.request';
    const PRE_VALIDATION_EVENT = 'app.pre_validation';
    const PAGINATION_EVENT = 'app.pagination';
    const PRE_COMMIT_EVENT = 'app.pre_commit';
}
