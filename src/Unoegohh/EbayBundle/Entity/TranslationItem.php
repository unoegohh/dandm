<?php
namespace Unoegohh\EbayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class TranslationItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    protected $engText;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\NotBlank()
     */
    protected $ruText;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $translated;


    /**
     * @ORM\ManyToOne(targetEntity="Unoegohh\AdminBundle\Entity\Category", cascade={"all"})
     * @ORM\JoinColumn(name="category_id",referencedColumnName="id")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Unoegohh\AdminBundle\Entity\GoldType", cascade={"all"})
     * @ORM\JoinColumn(name="gtype_id",referencedColumnName="id")
     */
    protected $goldType;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $currTrans;

    public function __toString(){
        if($this->getId()){
            return $this->getId();
        }
        else{
            return "Перевод";
        }
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setEngText($engText)
    {
        $this->engText = $engText;
    }

    public function getEngText()
    {
        return $this->engText;
    }

    public function setGoldType($goldType)
    {
        $this->goldType = $goldType;
    }

    public function getGoldType()
    {
        return $this->goldType;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRuText($ruText)
    {
        $this->ruText = $ruText;
    }

    public function getRuText()
    {
        return $this->ruText;
    }

    public function setTranslated($translated)
    {
        $this->translated = $translated;
    }

    public function getTranslated()
    {
        return $this->translated;
    }

    public function setCurrTrans($currTrans)
    {
        $this->currTrans = $currTrans;
    }

    public function getCurrTrans()
    {
        return $this->currTrans;
    }


}