<?php

namespace OxygenModule\Events\Controller;

use Oxygen\Crud\Controller\Previewable;
use OxygenModule\Events\Fields\UpcomingEventFieldSet;
use OxygenModule\Events\Repository\UpcomingEventRepositoryInterface;
use Oxygen\Core\Blueprint\BlueprintManager;
use Oxygen\Crud\Controller\Publishable;
use Oxygen\Crud\Controller\VersionableCrudController;
use Preferences;

class UpcomingEventsController extends VersionableCrudController {

    use Publishable;
    use Previewable { getContent as getPreviewContent; }

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

    /**
     * Renders the content for this resource as HTML.
     *
     * @param $item
     * @return Response
     */
    public function getContent($item = null) {
        $content = $this->getPreviewContent($item)->render();

        return view(Preferences::get('appearance.events::contentView'))->with('content', $content);
    }

}
