<?php namespace Pushman\Validators;

use Pushman\Exceptions\InvalidSiteException;
use Pushman\Interfaces\EventObject;
use Pushman\Interfaces\Validator;
use Pushman\Site;

class DemoValidator implements Validator
{

    /**
     * @var EventObject
     */
    private $event;

    /**
     * @param EventObject $event
     */
    public function __construct(EventObject $event)
    {
        $this->event = $event;
    }

    public function validate()
    {
        $demo_key = 'this_is_a_60_char_string_that_looks_like_a_valid_private_key';
        if ($this->event->getPrivateKey() == $demo_key) {
            $site = Site::where('name', 'demo')->where('url', 'http://pushman.dfl.mn')->first();

            if (!$site) {
                throw new InvalidSiteException('This site does not exist.');
            }

            $this->event->setSite($site);
        }
    }
}