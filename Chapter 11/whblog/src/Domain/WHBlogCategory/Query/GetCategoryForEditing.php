<?php

namespace WebHelpers\WHBlog\Domain\WHBlogCategory\Query;

class GetCategoryForEditing
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
