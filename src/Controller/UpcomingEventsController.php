<?php

namespace OxygenModule\Events\Controller;

use OxygenModule\Events\Fields\UpcomingEventFieldSet;
use OxygenModule\Events\Repository\UpcomingEventRepositoryInterface;
use Oxygen\Core\Blueprint\BlueprintManager;
use Oxygen\Crud\Controller\Publishable;
use Oxygen\Crud\Controller\VersionableCrudController;

class UpcomingEventsController extends VersionableCrudController {

    use Publishable, Previewable;

    /**
     * Constructs the UpcomingEventsController.
     *
     * @param \OxygenModule\Events\Repository\UpcomingEventRepositoryInterface $repository
     * @param \Oxygen\Core\Blueprint\BlueprintManager                          $manager
     * @param \OxygenModule\Events\Fields\UpcomingEventFieldSet                $fields
     */
    public function __construct(UpcomingEventRepositoryInterface $repository, BlueprintManager $manager, UpcomingEventFieldSet $fields) {
        parent::__construct($repository, $manager, $fields);
    }

}
