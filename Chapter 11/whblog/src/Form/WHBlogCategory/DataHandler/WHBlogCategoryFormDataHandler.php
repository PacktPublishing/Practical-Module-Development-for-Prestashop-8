<?php
namespace WebHelpers\WHBlog\Form\WHBlogCategory\DataHandler;

use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;

use WebHelpers\WHBlog\Domain\WHBlogCategory\Command\CreateCategoryCommand;
use WebHelpers\WHBlog\Domain\WHBlogCategory\Command\EditCategoryCommand;

final class WHBlogCategoryFormDataHandler implements FormDataHandlerInterface
{
    private $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function create(array $data)
    {
        $createCategoryCommand = new CreateCategoryCommand($data['title']);
        $this->commandBus->handle($createCategoryCommand);
    }

    public function update($id, array $data)
    {
        $editCategoryCommand = new EditCategoryCommand($id,$data['title']);
        $this->commandBus->handle($editCategoryCommand);
    }
}
