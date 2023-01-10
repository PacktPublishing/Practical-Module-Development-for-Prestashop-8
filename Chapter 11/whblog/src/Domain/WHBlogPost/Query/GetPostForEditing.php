<?php

namespace WebHelpers\WHBlog\Domain\WHBlogPost\Query;

class GetPostForEditing
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
