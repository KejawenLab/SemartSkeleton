<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Menu;

use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Repository\MenuRepository;
use PHLAK\Twine\Str;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuService
{
    private $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    public function getMenuByCode(string $code)
    {
        return $this->menuRepository->findByCode(Str::make($code)->uppercase()->__toString());
    }

    /**
     * @return Menu[]
     */
    public function getActiveMenus(): array
    {
        return $this->menuRepository->findUnDeletedRecords();
    }
}
