<?php

namespace OxygenModule\Events\Controller;

use Illuminate\Http\Request;
use Oxygen\Crud\Controller\BasicCrudApi;
use Oxygen\Crud\Controller\Previewable;
use Oxygen\Crud\Controller\VersionableCrudApi;
use Oxygen\Data\Repository\QueryParameters;
use OxygenModule\Events\Fields\UpcomingEventFieldSet;
use OxygenModule\Events\Repository\UpcomingEventRepositoryInterface;
use Oxygen\Core\Blueprint\BlueprintManager;
use Oxygen\Crud\Controller\Publishable;
use Oxygen\Crud\Controller\VersionableCrudController;
use Oxygen\Preferences\Facades\Preferences;

class UpcomingEventsController extends VersionableCrudController {

    use Publishable;
    use Previewable;
    use BasicCrudApi, VersionableCrudApi {
        VersionableCrudApi::getListQueryParameters insteadof BasicCrudApi;
    }

    const LANG_MAPPINGS = [
        'resource' => 'Event',
        'pluralResource' => 'Events'
    ];
    
    const PER_PAGE = 10;

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

    protected function decoratePreviewContent($content) {
        return view(Preferences::get('appearance.events::contentView'))->with('content', $content);
    }

    /**
     * Find bookings from TryBooking's `bookingUrlId` and ticket index.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function findByTrybookingSessionId(Request $request) {
        $queryParameters = QueryParameters::make()
            ->excludeTrashed()
            ->excludeVersions()
            ->orderBy('id', QueryParameters::DESCENDING);

        $item = $this->repository->findByTrybookingSessionId(
            $request->get('trybookingSessionId'),
            $queryParameters
        );

        return response()->json([
            'item' => $item === null ? null : $item->toArray()
        ]);
    }

}
