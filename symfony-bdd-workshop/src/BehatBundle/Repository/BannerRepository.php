<?php

namespace BehatBundle\Repository;


use BehatBundle\Document\Banner;
use Doctrine\ODM\MongoDB\DocumentRepository;

class BannerRepository extends DocumentRepository implements BannerRepositoryInterface
{
    public function save(Banner $banner)
    {
        $this->getDocumentManager()->persist($banner);
        $this->getDocumentManager()->flush();
    }
}
