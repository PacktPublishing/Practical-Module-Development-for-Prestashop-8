<?php

namespace WebHelpers\WHCategoryFields\Controller;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Common\Cache\CacheProvider;

use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\CommandBus\TacticianCommandBusAdapter;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

use WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Command\DeleteExtensionFileCommand;

class AdminWHCategoryFieldsController extends FrameworkBundleAdminController
{
    private $cache;
    private $commandBus;

    public function __construct(CacheProvider $cache, TacticianCommandBusAdapter $commandBus)
    {
        $this->cache = $cache;
        $this->commandBus = $commandBus;
    }

    public function deleteFileAction(Request $request, $idCategory)
    {
        $this->commandBus->handle(new DeleteExtensionFileCommand($idCategory));
        return $this->redirectToRoute('admin_categories_edit', [
            'categoryId' => $idCategory,
        ]);
    }
}
