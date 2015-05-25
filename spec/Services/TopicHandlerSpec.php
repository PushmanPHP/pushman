<?php

namespace spec\Pushman\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TopicHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Pushman\Services\TopicHandler');
    }
}
