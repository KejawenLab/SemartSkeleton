<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Setting;

use KejawenLab\Semart\Skeleton\Repository\SettingRepository;
use PHLAK\Twine\Str;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SettingService
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
        if ($setting = $this->settingRepository->findOneBy(['parameter' => Str::make($parameter)->uppercase()->__toString()])) {
            return $setting->getValue();
        }

        return null;
    }
}
