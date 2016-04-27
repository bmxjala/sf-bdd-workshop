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
    protected $test = 'true';

    public function __construct()
    {
        $this->test = 'true';
    }
    
    public function disable()
    {
        $this->test = 'false';

        return $this;
    }

    public function setId($bannerId)
    {
        $this->id = $bannerId;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTest()
    {
        return $this->test;
    }

}
