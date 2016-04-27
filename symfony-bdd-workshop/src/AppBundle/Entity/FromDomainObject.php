<?php

namespace AppBundle\Entity;

use Domain\EventModel\EventBased;

interface FromDomainObject
{
    /**
     * @param EventBased $domainObject
     * @return FromDomainObject
     */
    public static function createFromDomainObject(EventBased $domainObject);
}
