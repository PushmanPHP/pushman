<?php

namespace spec\Pushman\Repositories;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Pushman\Repositories\SiteRepository;

/**
 * Class SiteRepositorySpec
 * @package spec\Pushman\Repositories
 * @mixin SiteRepository
 */
class SiteRepositorySpec extends ObjectBehavior {

    function it_is_initializable()
    {
        $this->shouldHaveType('Pushman\Repositories\SiteRepository');
    }
}
