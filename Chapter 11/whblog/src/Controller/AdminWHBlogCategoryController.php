<?php

namespace WebHelpers\WHBlog\Controller;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Exception\TableExistsException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\CommandBus\TacticianCommandBusAdapter;
use PrestaShop\PrestaShop\Core\Grid\Data\Factory\DoctrineGridDataFactory;
use PrestaShop\PrestaShop\Core\Grid\GridFactory;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteria;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Service\Grid\ResponseBuilder;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use WebHelpers\WHBlog\Filter\WHBlogCategoryFilter;
use WebHelpers\WHBlog\Domain\WHBlogCategory\Command\DeleteCategoryCommand;

class AdminWHBlogCategoryController extends FrameworkBundleAdminController
{
    private $cache;
    private $commandBus;

    public function __construct(CacheProvider $cache, TacticianCommandBusAdapter $commandBus)
    {
        $this->cache = $cache;
        $this->commandBus = $commandBus;
    }

    private function getGridToolbarButtons()
    {
        $toolbarButtons['add'] = [
            'href' => $this->generateUrl('admin_whblog_category_create'),
            'desc' => $this->trans('Add a new category', 'Admin.WHBlog.Text'),
            'icon' => 'add_circle_outline',
        ];
        $toolbarButtons['post'] = [
            'href' => $this->generateUrl('admin_whblog_post_create'),
            'desc' => $this->trans('Add a new post', 'Admin.WHBlog.Text'),
            'icon' => 'add_circle_outline',
        ];
        $toolbarButtons['view_all'] = [
            'href' => $this->generateUrl('admin_whblog_post_list'),
            'desc' => $this->trans('View all posts', 'Admin.Whblog.Text'),
            'icon' => 'visibility',
        ];

        return $toolbarButtons;
    }

    public function listAction(WHBlogCategoryFilter $filters)
    {
        $categoryGridFactory = $this->get('webhelpers.whblog.grid.category_grid_factory');
        $categoryGrid = $categoryGridFactory->getGrid($filters);

        return $this->render('@Modules/whblog/views/templates/admin/list.html.twig',[
            'grid' => $this->presentGrid($categoryGrid),
            'layoutHeaderToolbarBtn' => $this->getGridToolbarButtons(),
        ]);
        return "";
    }

    public function deleteAction(int $idCategory)
    {
        $this->commandBus->handle(new DeleteCategoryCommand($idCategory));
        $this->addFlash('success', $this->trans('Successful deletion.', 'Admin.Notifications.Success'));
        return $this->redirectToRoute('admin_whblog_category_list');
    }

    public function createAction(Request $request)
    {
        $categoryFormBuilder = $this->get('webhelpers.whblog.form.identifiable_object.builder.category_form_builder');
        $categoryForm = $categoryFormBuilder->getForm();
        $categoryForm->handleRequest($request);
        $categoryFormHandler = $this->get('webhelpers.whblog.form.identifiable_object.handler.category_form_handler');
        $categoryFormHandler->handle($categoryForm);
        if ($categoryForm->isSubmitted() && $categoryForm->isValid())
        {
            $this->addFlash('success', $this->trans('Successful creation.', 'Admin.Notifications.Success'));
            return $this->redirectToRoute('admin_whblog_category_list');
        }

        return $this->render('@Modules/whblog/views/templates/admin/formCategory.html.twig',[
            "categoryForm"=>$categoryForm->createView()
        ]);
    }

    public function editAction($idCategory, Request $request)
    {
        $categoryFormBuilder = $this->get('webhelpers.whblog.form.identifiable_object.builder.category_form_builder');
        $categoryForm = $categoryFormBuilder->getFormFor((int)$idCategory);
        $categoryForm->handleRequest($request);
        $categoryFormHandler = $this->get('webhelpers.whblog.form.identifiable_object.handler.category_form_handler');
        $categoryFormHandler->handleFor($idCategory, $categoryForm);
        if ($categoryForm->isSubmitted() && $categoryForm->isValid())
        {
            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));
            return $this->redirectToRoute('admin_whblog_category_list');
        }

        return $this->render('@Modules/whblog/views/templates/admin/formCategory.html.twig',[
            "categoryForm"=>$categoryForm->createView()
        ]);
    }
}
