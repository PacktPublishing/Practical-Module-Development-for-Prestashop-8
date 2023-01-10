<?php

namespace WebHelpers\WHBlog\Domain\WHBlogCategory\Command;

class DeleteCategoryCommand
{
    private $idCategory;

    public function __construct($idCategory)
    {
        $this->idCategory = $idCategory;
    }

    public function getIdCategory()
    {
        return $this->idCategory;
    }
}

