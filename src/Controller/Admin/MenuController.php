<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Menu\MenuService;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Repository\MenuRepository;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/menus")
 *
 * @Permission(menu="MENU")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="menus_index", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index(Request $request, Paginator $paginator, MenuService $menuService)
    {
        $menus = $paginator->paginate(Menu::class, (int) $request->query->get('page', 1));

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('menu/table-content.html.twig', ['menus' => $menus]);
            $pagination = $this->renderView('menu/pagination.html.twig', ['menus' => $menus]);

            return new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
            ]);
        }

        return $this->render('menu/index.html.twig', ['title' => 'Menu', 'menus' => $menus, 'parents' => $menuService->getActiveMenus()]);
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="menus_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, MenuRepository $repository, SerializerInterface $serializer)
    {
        $menu = $repository->find($id);
        if (!$menu) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($menu, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="menus_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, MenuRepository $repository, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $menu = $repository->find($primary);
        } else {
            $menu = new Menu();
        }

        $requestHandler->handle($request, $menu);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($menu);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="menus_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, MenuRepository $repository)
    {
        if (!$menu = $repository->find($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($menu);

        return new JsonResponse(['status' => 'OK']);
    }
}
