<?php
namespace WebHelpers\WHBlog\Form\WHBlogPost\DataHandler;

use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use WebHelpers\WHBlog\Domain\WHBlogPost\Command\EditPostCommand;
use WebHelpers\WHBlog\Domain\WHBlogPost\Command\CreatePostCommand;

final class WHBlogPostFormDataHandler implements FormDataHandlerInterface
{
    private $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function create(array $data)
    {
        $createPostCommand = new CreatePostCommand($data['title'],$data['content'],$data['image_file'],$data['categories']);
        $this->commandBus->handle($createPostCommand);
    }

    public function update($id, array $data)
    {
        $editPostCommand = new EditPostCommand($id,$data['title'],$data['content'],$data['image_file'],$data['categories']);
        $this->commandBus->handle($editPostCommand);
    }
}
