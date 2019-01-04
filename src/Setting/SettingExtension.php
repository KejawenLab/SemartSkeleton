<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Setting;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SettingExtension extends \Twig_Extension
{
    private $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('setting', [$this, 'findSetting']),
        ];
    }

    /**
     * @param string $parameter
     *
     * @return null|string|int|float
     */
    public function findSetting(string $parameter)
    {
        return $this->settingService->getValue($parameter);
    }
}
