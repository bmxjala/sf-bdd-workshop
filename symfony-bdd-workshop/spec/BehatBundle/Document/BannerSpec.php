<?php

namespace spec\BehatBundle\Document;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BannerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BehatBundle\Document\Banner');
    }
}
