<?php

namespace WebHelpers\WHBlog\Domain\WHBlogPost\DTO;

class WHBlogPost
{
    private $title;
    private $content;
    private $filename;
    private $categories;

    public function __construct($title, $content, $filename, $categories)
    {
        $this->title = $title;
        $this->content = $content;
        $this->filename = $filename;
        $this->categories = $categories;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getCategories()
    {
        return $this->categories;
    }
}
