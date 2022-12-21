<?php

namespace WebHelpers\WHCallback\Controller;

use PrestaShop\PrestaShop\Core\Grid\Data\Factory\DoctrineGridDataFactory;
use PrestaShop\PrestaShop\Core\Grid\GridFactory;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Exception\TableExistsException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\CommandBus\TacticianCommandBusAdapter;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteria;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Service\Grid\ResponseBuilder;

use WebHelpers\WHCallback\Domain\WHCallbackRequest\Command\DeleteWHCallbackRequestCommand;
use WebHelpers\WHCallback\Entity\WHCallbackRequest;
use WebHelpers\WHCallback\Filter\WHCallbackRequestFilter;
use WebHelpers\WHCallback\Grid\Definition\Factory\WHCallbackRequestDefinitionFactory;

class AdminWHCallbackRequestController extends FrameworkBundleAdminController
{
    private $cache;
    private $commandBus;

    public function __construct(CacheProvider $cache, TacticianCommandBusAdapter $commandBus)
    {
        $this->cache = $cache;
        $this->commandBus = $commandBus;
    }

    public function listAction(WHCallbackRequestFilter $filters)
    {
        $callbackRequestGridFactory = $this->get('webhelpers.whcallback.grid.whcallbackrequest_grid_factory');
        $callbackRequestGrid = $callbackRequestGridFactory->getGrid($filters);

        return $this->render('@Modules/whcallback/views/templates/admin/list.html.twig',[
            'callbackRequestGrid' => $this->presentGrid($callbackRequestGrid)
        ]);
    }

    public function deleteAction(int $idRequest)
    {
        $this->commandBus->handle(new DeleteWHCallbackRequestCommand($idRequest));
        return $this->redirectToRoute('admin_whcallbackrequest_list');
    }

    public function searchAction(Request $request)
    {
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');
        return $responseBuilder->buildSearchResponse(
            $this->get('webhelpers.whcallback.grid.definition.factory.whcallbackrequest_grid_definition_factory'),
            $request,
            WHCallbackRequestDefinitionFactory::GRID_ID,
            'admin_whcallbackrequest_list'
        );
    }
}
