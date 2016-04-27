<?php

namespace BehatBundle\Handler;

use BehatBundle\Repository\BannerRepositoryInterface;

class BannerHandler
{
    /**
     * @var BannerRepositoryInterface
     */
    private $bannerRepository;

    /**
     * @param BannerRepositoryInterface $bannerRepository
     */
    public function __construct(BannerRepositoryInterface $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    public function disable($bannerId)
    {
        $banner = $this->bannerRepository->find($bannerId);
        $banner->disable();
        $this->bannerRepository->save($banner);
    }
}
