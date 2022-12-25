<?php

namespace WebHelpers\WHCategoryFields\Entity;

use Doctrine\ORM\Mapping as ORM;
use PrestaShopBundle\Entity\Lang;
use WebHelpers\WHCategoryFields\Entity\WHCategoryFieldsExtension;

/**
 * @ORM\Table(name="ps_whcategoryfields_extension_lang")
 * @ORM\Entity()
 */
class WHCategoryFieldsExtensionLang
{
    /**
     * @var WHCategoryFieldsExtension
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="WebHelpers\WHCategoryFields\Entity\WHCategoryFieldsExtension", inversedBy="extensionLangs")
     * @ORM\JoinColumn(name="id_extension", referencedColumnName="id_extension", nullable=false)
     */
    private $extension;

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
     * @ORM\Column(name="short_text", type="string", length=255)
     */
    private $shortText;

    /**
     * @var string
     *
     * @ORM\Column(name="long_text", type="text")
     */
    private $longText;

    /**
     * @return WHCategoryFieldsExtension
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param WHCategoryFieldsExtension $extension
     * @return $this
     */
    public function setExtension(WHCategoryFieldsExtension $extension): self
    {
        $this->extension = $extension;
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
    public function getShortText()
    {
        return $this->shortText;
    }

    /**
     * @param string $shortText
     *
     * @return WHCategoryFieldsExtensionLang
     */
    public function setShortText($shortText)
    {
        if(!is_null($shortText)){
            $this->shortText = $shortText;
        }else{
            $this->shortText = "";
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getLongText()
    {
        return $this->longText;
    }

    /**
     * @param string $longText
     *
     * @return WHCategoryFieldsExtensionLang
     */
    public function setLongText($longText)
    {
        if(!is_null($longText)){
            $this->longText = $longText;
        }else{
            $this->longText = "";
        }
        return $this;
    }
}
