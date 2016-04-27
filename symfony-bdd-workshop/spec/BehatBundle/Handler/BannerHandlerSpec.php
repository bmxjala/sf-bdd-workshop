<?php

namespace spec\BehatBundle\Handler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use BehatBundle\Document\Banner;

class BannerHandlerSpec extends ObjectBehavior
{
    /**
     * @param BehatBundle\Repository\BannerRepositoryInterface $bannerRepository
     */
    function let($bannerRepository)
    {
        $this->beConstructedWith($bannerRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BehatBundle\Handler\BannerHandler');
    }

    /**
     * @param BehatBundle\Repository\BannerRepositoryInterface $bannerRepository
     */
    function it_should_disable_banner($bannerRepository)
    {
        $banner = new Banner();
        $bannerRepository->find('left_banner')->willReturn($banner);
        $banner->visible = false;
        $bannerRepository->save($banner)->shouldBeCalled();
        
        // executor
        $this->disable('left_banner');


    }
}
