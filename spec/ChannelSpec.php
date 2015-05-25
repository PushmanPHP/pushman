<?php

namespace spec\Pushman;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChannelSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Pushman\Channel');
    }
}
