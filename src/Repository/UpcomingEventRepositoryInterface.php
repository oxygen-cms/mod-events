<?php

namespace OxygenModule\Events\Repository;

use Oxygen\Data\Repository\RepositoryInterface;

interface UpcomingEventRepositoryInterface extends RepositoryInterface {

    /**
     * Returns the latest upcoming events.
     *
     * @param int $howMany
     * @return mixed
     */

    public function getLatest($howMany);

}