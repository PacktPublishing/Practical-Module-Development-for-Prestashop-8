<?php
namespace WebHelpers\WHBlog\Form\WHBlogPost\DataProvider;

use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;
use WebHelpers\WHBlog\Domain\WHBlogPost\Query\GetPostForEditing;
use WebHelpers\WHBlog\Domain\WHBlogPost\DTO\WHBlogPost;

final class WHBlogPostFormDataProvider implements FormDataProviderInterface
{
    private $queryBus;

    public function __construct(CommandBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function getData($id)
    {
        $postForEditing = $this->queryBus->handle(new GetPostForEditing($id));
        $filename = "";
        if($postForEditing->getFilename() != "")
        {
            $filename = "/modules/whblog/views/img/uploads/".$postForEditing->getFilename();
        }
        return [
            'title' => $postForEditing->getTitle(),
            'content' => $postForEditing->getContent(),
            'image_file_preview' => ['filenameUrl' => $filename],
            'categories' => $postForEditing->getCategories(),
        ];
    }

    public function getDefaultData()
    {
        return [
            'title' => [],
            'content' => [],
            'filename' => "",
            'categories' => [],
        ];
    }
}
