<?php

namespace WebHelpers\WHBlog\Domain\WHBlogCategory\DTO;

class WHBlogCategory
{
    private $title;

    public function __construct($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
