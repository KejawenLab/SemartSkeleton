<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Menu;

use KejawenLab\Semart\Collection\Collection;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuExtension extends AbstractExtension
{
    private $menuLoader;

    private $menuService;

    private $urlGenerator;

    public function __construct(MenuLoader $menuLoader, MenuService $menuService, UrlGeneratorInterface $urlGenerator)
    {
        $this->menuLoader = $menuLoader;
        $this->menuService = $menuService;
        $this->urlGenerator = $urlGenerator;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_menu', [$this, 'renderMenu']),
            new TwigFunction('code_to_menu', [$this, 'findMenu']),
        ];
    }

    public function renderMenu(): string
    {
        return Collection::collect($this->menuLoader->getParentMenu())
            ->map(function ($value) {
                /** @var Menu $value */
                if (!$this->menuLoader->hasChildMenu($value)) {
                    return $this->buildHtml($value);
                }

                $html = sprintf('<li class="treeview %s"><a href="#" id="%s"><i class="fa fa-%s"></i> <span>%s</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">', strtolower($value->getCode()), $value->getId(), $value->getIconClass() ?: 'circle-o', $value->getName());
                Collection::collect($this->menuLoader->getChildMenu($value))
                    ->each(function ($value) use (&$html) {
                        $html .= $this->buildHtml($value);
                    })
                ;

                return $html.'</ul></li>';
            })
            ->implode()
        ;
    }

    public function findMenu(string $code): ?Menu
    {
        return $this->menuService->getMenuByCode($code);
    }

    private function buildHtml(Menu $menu): string
    {
        $href = $menu->getRouteName() ? $this->urlGenerator->generate($menu->getRouteName(), ['m' => strtolower((string) $menu->getCode())]) : '#';

        return sprintf('<li class="%s %s"><a href="%s" id="%s"><i class="fa fa-%s"></i> <span>%s</span></a></li>', \is_object($menu->getParent()) ? strtolower((string) $menu->getParent()->getCode()): '', strtolower((string) $menu->getCode()), $href, $menu->getId(), $menu->getIconClass() ?: 'circle-o', $menu->getName());
    }
}
