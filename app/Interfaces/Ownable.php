<?php

namespace Pushman\Interfaces;

use Pushman\User;

interface Ownable
{
    /**
     * Checks to see if this resources is owned by a user.
     *
     * @param \Pushman\User $user
     *
     * @return mixed
     */
    public function ownedBy(User $user);
}
