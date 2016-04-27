<?php

namespace BehatBundle\Repository;

use BehatBundle\Document\Banner;

interface BannerRepositoryInterface
{

    public function find($bannerId);

    public function save(Banner $banner);
}
