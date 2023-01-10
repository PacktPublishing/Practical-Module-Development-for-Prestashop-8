<?php

namespace WebHelpers\WHBlog\Entity;

use Doctrine\ORM\Mapping as ORM;
use PrestaShopBundle\Entity\Lang;
use WebHelpers\WHBlog\Entity\WHBlogPost;

/**
 * @ORM\Table(name="ps_whblog_post_lang")
 * @ORM\Entity()
 */
class WHBlogPostLang
{
    /**
     * @var WHBlogPost
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="WebHelpers\WHBlog\Entity\WHBlogPost", inversedBy="postLangs")
     * @ORM\JoinColumn(name="id_post", referencedColumnName="id_post", nullable=false)
     */
    private $post;

    /**
     * @var Lang
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PrestaShopBundle\Entity\Lang")
     * @ORM\JoinColumn(name="id_lang", referencedColumnName="id_lang", nullable=false, onDelete="CASCADE")
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

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

    /**
     * @return Lang
     */
    public function getLang(): Lang
    {
        return $this->lang;
    }

    /**
     * @param Lang $lang
     * @return $this
     */
    public function setLang(Lang $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return WHBlogPostLang
     */
    public function setTitle($title)
    {
        if(!is_null($title)){
            $this->title = $title;
        }else{
            $this->title = "";
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return WHBlogPostLang
     */
    public function setContent($content)
    {
        if(!is_null($content)){
            $this->content = $content;
        }else{
            $this->content = "";
        }
        return $this;
    }
}
