<?php

namespace WebHelpers\WHBlog\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ps_whblog_post")
 * @ORM\Entity(repositoryClass="WebHelpers\WHBlog\Repository\WHBlogPostRepository")
 * @ORM\HasLifecycleCallbacks
 */
class WHBlogPost
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_post", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\OneToMany(targetEntity="WebHelpers\WHBlog\Entity\WHBlogCategoryPost", cascade={"persist", "remove"}, mappedBy="post")
    */
    protected $categoryPostRelationship;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\OneToMany(targetEntity="WebHelpers\WHBlog\Entity\WHBlogPostLang", cascade={"persist", "remove"}, mappedBy="post")
     */
    private $postLangs;

    /**
     * @var datetime
     *
     * @ORM\Column(name="post_date_add", type="datetime")
     */
    private $postDateAdd;

    /**
     * @var datetime
     *
     * @ORM\Column(name="post_date_update", type="datetime")
     */
    private $postDateUpdate;

    public function __construct()
    {
        $this->postLangs = new ArrayCollection();
        $this->categoryPostRelationship = new ArrayCollection();
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
        $categories = new ArrayCollection();
        foreach($this->categoryPostRelationship as $relation)
        {
            $categories->add($relation->getCategory()->getId());
        }
        return $categories;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     *
     * @return WHBlogPost
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return datetime
     */
    public function getPostDateAdd()
    {
        return $this->postDateAdd;
    }

    /**
     * @return WHBlogPost
     */
    public function setPostDateAdd()
    {
        $this->postDateAdd = new DateTime();
        return $this;
    }

    /**
     * @return datetime
     */
    public function getPostDateUpdate()
    {
        return $this->postDateUpdate;
    }

    /**
     * @return WHBlogPost
     */
    public function setPostDateUpdate()
    {
        $this->postDateUpdate = new DateTime();
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPostLangs(): ArrayCollection
    {
        return $this->postLangs;
    }

    /**
     * @param int $langId
     * @return WHBlogPostLang|null
     */
    public function getPostLangByLangId(int $langId): ?WHBlogPostLang
    {
        foreach ($this->postLangs as $postLang) {
            if ($langId === $postLang->getLang()->getId()) {
                return $postLang;
            }
        }
        return null;
    }

    public function getTitleLangs(): array
    {
        $titleLangs = [];
        foreach($this->postLangs as $postLang)
        {
            $titleLangs[$postLang->getLang()->getId()]=$postLang->getTitle();
        }
        return $titleLangs;
    }

    public function getContentLangs(): array
    {
        $contentLangs = [];
        foreach($this->postLangs as $postLang)
        {
            $contentLangs[$postLang->getLang()->getId()]=$postLang->getContent();
        }
        return $contentLangs;
    }

    /**
     * @param WHBlogPostLang $postLang
     * @return $this
     */
    public function addPostLang(WHBlogPostLang $postLang): self
    {
        $postLang->setPost($this);
        $this->postLangs->add($postLang);

        return $this;
    }

    public function getCategoryRelationShip()
    {
        return $this->categoryPostRelationship;
    }


    /**
     * Now we tell doctrine that before we persist or update we call the updatedTimestamps() function.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setPostDateUpdate();

        if ($this->getPostDateAdd() == null) {
            $this->setPostDateAdd();
        }
    }
}
