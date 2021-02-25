<?php

namespace OxygenModule\Events\Repository;

use Oxygen\Data\Repository\RepositoryInterface;
use OxygenModule\Events\Entity\UpcomingEvent;

interface UpcomingEventRepositoryInterface extends RepositoryInterface {

    /**
     * Returns the latest upcoming events.
     *
     * @param int $howMany
     * @return mixed
     */
    public function getLatest($howMany);

    /**
     * Finds event by trybooking session id
     *
     * @param int $sessionId
     * @return UpcomingEvent
     */
    public function findByTrybookingSessionId($sessionId);

}
