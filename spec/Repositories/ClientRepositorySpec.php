<?php

namespace spec\Pushman\Repositories;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Pushman\Repositories\ClientRepository');
    }
}
