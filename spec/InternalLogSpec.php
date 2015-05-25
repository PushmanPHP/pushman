<?php

namespace spec\Pushman;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InternalLogSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Pushman\InternalLog');
    }
}
