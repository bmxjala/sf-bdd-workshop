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
        if (empty($banner)) {
            throw new \Exception("Banner $bannerId not found");
        }
        $banner->disable();
        $this->bannerRepository->save($banner);

        $bannerNew = $this->bannerRepository->find($bannerId);
    }
}
