<?php namespace Pushman\Validators;

use Pushman\Exceptions\InvalidSiteException;
use Pushman\Interfaces\EventObject;
use Pushman\Interfaces\Validator;
use Pushman\Site;

class SiteValidator implements Validator
{

    protected $event;

    public function __construct(EventObject $event)
    {
        $this->event = $event;
    }

    public function validate()
    {
        $private = $this->event->getPrivateKey();

        $site = Site::where('private', $private)->first();
        if (!$site) {
            throw new InvalidSiteException('This site does not exist.');
        }

        $this->event->setSite($site);
    }
}
