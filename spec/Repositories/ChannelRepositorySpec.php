<?php namespace spec\Pushman\Repositories;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Pushman\Repositories\ChannelRepository;
use Pushman\Site;

/**
 * Class ChannelRepositorySpec
 * @package spec\Pushman\Repositories
 * @mixin ChannelRepository
 */
class ChannelRepositorySpec extends ObjectBehavior {

    function it_is_initializable()
    {
        $this->shouldHaveType('Pushman\Repositories\ChannelRepository');
    }
}
