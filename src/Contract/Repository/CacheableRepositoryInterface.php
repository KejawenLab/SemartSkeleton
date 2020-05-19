<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Contract\Repository;

/**
 * @deprecated
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface CacheableRepositoryInterface
{
    /**
     * @deprecated
     */
    public function isCacheable(): bool;

    /**
     * @deprecated
     */
    public function setCacheable(bool $cacheable): void;
}
