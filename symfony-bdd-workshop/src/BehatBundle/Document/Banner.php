<?php

namespace BehatBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="BehatBundle\Repository\BannerRepository")
 */
class Banner
{
    /**
     * @MongoDB\Id(strategy="NONE", type="string")
     */
    protected $id;

    /** 
     * @MongoDB\String 
     */
    public $visible = true;


    public function disable()
    {
        $this->visible = false;

        return $this;
    }

    public function setId($bannerId)
    {
        $this->id = $bannerId;
    }
}
