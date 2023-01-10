<?php

namespace WebHelpers\WHBlog\Domain\WHBlogCategory\QueryHandler;

use WebHelpers\WHBlog\Domain\WHBlogCategory\DTO\WHBlogCategory;
use WebHelpers\WHBlog\Domain\WHBlogCategory\Query\GetCategoryForEditing;

class GetCategoryForEditingHandler
{
    private $categoryRepository;

    public function __construct($categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function handle(GetCategoryForEditing $getCategoryForEditing)
    {
        $blogCategory = $this->categoryRepository->findOneBy(['id' => $getCategoryForEditing->getIdCategory()]);
        if(!is_null($blogCategory)){
            return new WHBlogCategory($blogCategory->getTitleLangs());
        }else{

        }

    }
}
