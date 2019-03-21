<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Setting;

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\Setting;
use KejawenLab\Semart\Skeleton\Repository\SettingRepository;
use PHLAK\Twine\Str;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SettingService implements ServiceInterface
{
    private $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * @param string $parameter
     *
     * @return null|string|int|float
     */
    public function getValue(string $parameter)
    {
        if ($setting = $this->settingRepository->findOneBy(['parameter' => Str::make($parameter)->uppercase()])) {
            return $setting->getValue();
        }

        return null;
    }

    /**
     * @param string $id
     *
     * @return Setting|null
     */
    public function find(string $id): ?object
    {
        return $this->settingRepository->find($id);
    }
}
