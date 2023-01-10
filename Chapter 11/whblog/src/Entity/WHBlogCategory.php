<?php

namespace WebHelpers\WHBlog\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ps_whblog_category")
 * @ORM\Entity(repositoryClass="WebHelpers\WHBlog\Repository\WHBlogCategoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class WHBlogCategory
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_category", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\OneToMany(targetEntity="WebHelpers\WHBlog\Entity\WHBlogCategoryPost", cascade={"persist", "remove"}, mappedBy="category")
    */
    protected $categoryPostRelationship;

    /**
     * @ORM\OneToMany(targetEntity="WebHelpers\WHBlog\Entity\WHBlogCategoryLang", cascade={"persist", "remove"}, mappedBy="category")
     */
    private $categoryLangs;

    /**
     * @var datetime
     *
     * @ORM\Column(name="category_date_add", type="datetime")
     */
    private $categoryDateAdd;

    /**
     * @var datetime
     *
     * @ORM\Column(name="category_date_update", type="datetime")
     */
    private $categoryDateUpdate;

    public function __construct()
    {
        $this->categoryLangs = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories(): ArrayCollection
    {
        return $this->categories;
    }

    /**
     * @return datetime
     */
    public function getCategoryDateAdd()
    {
        return $this->categoryDateAdd;
    }

    public function setCategoryDateAdd()
    {
        $this->categoryDateAdd = new DateTime();
    }

    /**
     * @return datetime
     */
    public function getCategoryDateUpdate()
    {
        return $this->categoryDateUpdate;
    }

    /**
     * @return WHBlogCategory
     */
    public function setCategoryDateUpdate()
    {
        $this->categoryDateUpdate = new DateTime();
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategoryLangs(): ArrayCollection
    {
        return $this->categoryLangs;
    }

    /**
     * @param int $langId
     * @return WHBlogCategoryLang|null
     */
    public function getCategoryLangByLangId(int $langId): ?WHBlogCategoryLang
    {
        foreach ($this->categoryLangs as $categoryLang) {
            if ($langId === $categoryLang->getLang()->getId()) {
                return $categoryLang;
            }
        }
        return null;
    }

    public function getTitleLangs(): array
    {
        $titleLangs = [];
        foreach($this->categoryLangs as $categoryLang)
        {
            $titleLangs[$categoryLang->getLang()->getId()]=$categoryLang->getTitle();
        }
        return $titleLangs;
    }

    /**
     * @param WHBlogCategoryLang $categoryLang
     * @return $this
     */
    public function addCategoryLang(WHBlogCategoryLang $categoryLang): self
    {
        $categoryLang->setCategory($this);
        $this->categoryLangs->add($categoryLang);

        return $this;
    }

    /**
     * Now we tell doctrine that before we persist or update we call the updatedTimestamps() function.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setcategoryDateUpdate();

        if ($this->getCategoryDateAdd() == null) {
            $this->setCategoryDateAdd();
        }
    }
}
