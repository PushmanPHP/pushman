<?php

namespace Pushman\Interfaces;

interface Validator
{
    public function __construct(EventObject $event);

    public function validate();
}
