<?php

namespace WebHelpers\WHCategoryFields\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ps_whcategoryfields_extension")
 * @ORM\Entity(repositoryClass="WebHelpers\WHCategoryFields\Repository\WHCategoryFieldsExtensionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class WHCategoryFieldsExtension
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_extension", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_category", type="integer")
     */
    private $idCategory;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\OneToMany(targetEntity="WebHelpers\WHCategoryFields\Entity\WHCategoryFieldsExtensionLang", cascade={"persist", "remove"}, mappedBy="extension")
     */
    private $extensionLangs;

    /**
     * @var datetime
     *
     * @ORM\Column(name="extension_date_add", type="datetime")
     */
    private $extensionDateAdd;

    /**
     * @var datetime
     *
     * @ORM\Column(name="extension_date_update", type="datetime")
     */
    private $extensionDateUpdate;

    public function __construct()
    {
        $this->extensionLangs = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getIdCategory()
    {
        return $this->idCategory;
    }

    /**
     * @param int $idCategory
     * @return WHCategoryFieldsExtension
     */
    public function setIdCategory(int $idCategory)
    {
        $this->idCategory = $idCategory;
        return $this;
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
     * @return WHCategoryFieldsExtension
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return datetime
     */
    public function getExtensionDateAdd()
    {
        return $this->extensionDateAdd;
    }
    
    /**
     * @return WHCategoryFieldsExtension
     */
    public function setExtensionDateAdd()
    {
        $this->extensionDateAdd = new DateTime();
        return $this;
    }

    /**
     * @return datetime
     */
    public function getExtensionDateUpdate()
    {
        return $this->extensionDateUpdate;
    }

    /**
     * @return WHCategoryFieldsExtension
     */
    public function setExtensionDateUpdate()
    {
        $this->extensionDateUpdate = new DateTime();
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getExtensionLangs(): ArrayCollection
    {
        return $this->extensionLangs;
    }

    /**
     * @param int $langId
     * @return WHCategoryFieldsExtensionLang|null
     */
    public function getExtensionLangByLangId(int $langId): ?WHCategoryFieldsExtensionLang
    {
        foreach ($this->extensionLangs as $extensionLang) {
            if ($langId === $extensionLang->getLang()->getId()) {
                return $extensionLang;
            }
        }
        return null;
    }

    public function getShortTextLangs(): array
    {
        $shortTextLangs = [];
        foreach($this->extensionLangs as $extensionLang)
        {
            $shortTextLangs[$extensionLang->getLang()->getId()]=$extensionLang->getShortText();
        }
        return $shortTextLangs;
    }

    public function getLongTextLangs(): array
    {
        $longTextLangs = [];
        foreach($this->extensionLangs as $extensionLang)
        {
            $longTextLangs[$extensionLang->getLang()->getId()]=$extensionLang->getShortText();
        }
        return $longTextLangs;
    }

    /**
     * @param WHCategoryFieldsExtensionLang $extensionLang
     * @return $this
     */
    public function addExtensionLang(WHCategoryFieldsExtensionLang $extensionLang): self
    {
        $extensionLang->setExtension($this);
        $this->extensionLangs->add($extensionLang);

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
        $this->setExtensionDateUpdate();

        if ($this->getExtensionDateAdd() == null) {
            $this->setExtensionDateAdd();
        }
    }
}
