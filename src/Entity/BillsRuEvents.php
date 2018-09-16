<?php

namespace Main\Entity;

use Doctrine\ORM\Mapping as ORM;
use Main\Util\SmartProperties;

/**
 * BillsRuEvents
 *
 * @ORM\Table(name="bills_ru_events")
 * @ORM\Entity
 */
class BillsRuEvents
{
    use SmartProperties;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=230, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=240, nullable=true)
     */
    private $url;


    /**
     * Заполнение свойств при создании объекта
     *
     * @param $attributes
     */
    public function __construct($attributes = [])
    {
        foreach ($attributes as $attribute => $value) {
            if (property_exists($this, $attribute)) {
                $this->smartSet($attribute, $value);
            }
        }
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return BillsRuEvents
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return BillsRuEvents
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return BillsRuEvents
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
