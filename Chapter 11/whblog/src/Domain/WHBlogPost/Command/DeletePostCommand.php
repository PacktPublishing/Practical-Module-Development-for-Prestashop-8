<?php

namespace WebHelpers\WHBlog\Domain\WHBlogPost\Command;

class DeletePostCommand
{
    private $idPost;

    public function __construct($idPost)
    {
        $this->idPost = $idPost;
    }

    public function getIdPost()
    {
        return $this->idPost;
    }
}

