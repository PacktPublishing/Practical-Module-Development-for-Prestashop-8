<?php
namespace WebHelpers\WHBlog\Form\WHBlogCategory\DataProvider;

use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

use WebHelpers\WHBlog\Domain\WHBlogCategory\DTO\WHBlogCategory;
use WebHelpers\WHBlog\Domain\WHBlogCategory\Query\GetCategoryForEditing;


final class WHBlogCategoryFormDataProvider implements FormDataProviderInterface
{
    private $queryBus;

    public function __construct(CommandBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function getData($id)
    {
        $categoryForEditing = $this->queryBus->handle(new GetCategoryForEditing($id));
        return [
            'title' => $categoryForEditing->getTitle(),
        ];
    }

    public function getDefaultData()
    {
        return [
            'title' => [],
        ];
    }
}
