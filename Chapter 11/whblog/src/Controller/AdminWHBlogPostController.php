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

use WebHelpers\WHBlog\Domain\WHBlogPost\Command\DeletePostCommand;
use WebHelpers\WHBlog\Filter\WHBlogPostFilter;


class AdminWHBlogPostController extends FrameworkBundleAdminController
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
            'href' => $this->generateUrl('admin_whblog_post_create'),
            'desc' => $this->trans('Add a new Post', 'Admin.Whblog.Text'),
            'icon' => 'add_circle_outline',
        ];
        $toolbarButtons['view_all'] = [
            'href' => $this->generateUrl('admin_whblog_post_list'),
            'desc' => $this->trans('View all posts', 'Admin.Whblog.Text'),
            'icon' => 'visibility',
        ];

        return $toolbarButtons;
    }

    public function listAction(WHBlogPostFilter $filters)
    {
        $postGridFactory = $this->get('webhelpers.whblog.grid.post_grid_factory');
        $postGrid = $postGridFactory->getGrid($filters);

        return $this->render('@Modules/whblog/views/templates/admin/list.html.twig',[
            'grid' => $this->presentGrid($postGrid),
            'layoutHeaderToolbarBtn' => $this->getGridToolbarButtons(),
        ]);
        return "";
    }

    public function listFromCategoryAction(int $idCategory)
    {
        $filters = new WHBlogPostFilter();
        $filters->replace([
            'limit' => 10,
            'offset' => 0,
            'orderBy' => 'id_post',
            'sortOrder' => 'asc',
            'filters' => [
                'id_category'=>$idCategory
            ],
        ]);
        return $this->listAction($filters);
    }

    public function deleteAction(int $idPost)
    {
        $this->commandBus->handle(new DeletePostCommand($idPost));
        $this->addFlash('success', $this->trans('Successful deletion.', 'Admin.Notifications.Success'));
        return $this->redirectToRoute('admin_whblog_post_list');
    }

    public function createAction(Request $request)
    {
        $postFormBuilder = $this->get('webhelpers.whblog.form.identifiable_object.builder.post_form_builder');
        $postForm = $postFormBuilder->getForm();
        $postForm->handleRequest($request);
        $postFormHandler = $this->get('webhelpers.whblog.form.identifiable_object.handler.post_form_handler');
        $postFormHandler->handle($postForm);
        if ($postForm->isSubmitted() && $postForm->isValid())
        {
            $this->addFlash('success', $this->trans('Successful creation.', 'Admin.Notifications.Success'));
            return $this->redirectToRoute('admin_whblog_post_list');
        }

        return $this->render('@Modules/whblog/views/templates/admin/formPost.html.twig',[
            "postForm"=>$postForm->createView()
        ]);
    }

    public function editAction($idPost, Request $request)
    {
        $postFormBuilder = $this->get('webhelpers.whblog.form.identifiable_object.builder.post_form_builder');
        $postForm = $postFormBuilder->getFormFor((int)$idPost);
        $postForm->handleRequest($request);
        $postFormHandler = $this->get('webhelpers.whblog.form.identifiable_object.handler.post_form_handler');
        $postFormHandler->handleFor($idPost, $postForm);
        if ($postForm->isSubmitted() && $postForm->isValid())
        {
            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));
            return $this->redirectToRoute('admin_whblog_post_list');
        }

        return $this->render('@Modules/whblog/views/templates/admin/formPost.html.twig',[
            "postForm"=>$postForm->createView()
        ]);
    }
}
