<?php

namespace WebHelpers\WHBlog\Entity;

use Doctrine\ORM\Mapping as ORM;
use PrestaShopBundle\Entity\Lang;
use WebHelpers\WHBlog\Entity\WHBlogCategory;

/**
 * @ORM\Table(name="ps_whblog_category_lang")
 * @ORM\Entity()
 */
class WHBlogCategoryLang
{
    /**
     * @var WHBlogCategory
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="WebHelpers\WHBlog\Entity\WHBlogCategory", inversedBy="categoryLangs")
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id_category", nullable=false)
     */
    private $category;

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
     * @return WHBlogCategoryLang
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
}
