<?php

namespace WebHelpers\WHBlog\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ps_whblog_category_post")
 * @ORM\Entity(repositoryClass="WebHelpers\WHBlog\Repository\WHBlogCategoryPostRepository")
 * @ORM\HasLifecycleCallbacks
 */
class WHBlogCategoryPost
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_category_post", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var WHBlogCategory
     * @ORM\ManyToOne(targetEntity="WebHelpers\WHBlog\Entity\WHBlogCategory", inversedBy="categoryPostRelationship")
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id_category", nullable=false)
     */
    private $category;

    /**
     * @var WHBlogPost
     * @ORM\ManyToOne(targetEntity="WebHelpers\WHBlog\Entity\WHBlogPost", inversedBy="categoryPostRelationship")
     * @ORM\JoinColumn(name="id_post", referencedColumnName="id_post", nullable=false)
     */
    private $post;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return WHBlogCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param WHBlogCategory $category
     * @return $this
     */
    public function setCategory(WHBlogCategory $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return WHBlogPost
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param WHBlogPost $post
     * @return $this
     */
    public function setPost(WHBlogPost $post): self
    {
        $this->post = $post;
        return $this;
    }
}
