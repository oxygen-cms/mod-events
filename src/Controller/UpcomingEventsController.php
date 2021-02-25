<?php

namespace OxygenModule\Events\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Oxygen\Crud\Controller\BasicCrudApi;
use Oxygen\Crud\Controller\Previewable;
use Oxygen\Crud\Controller\VersionableCrudApi;
use Oxygen\Data\Repository\QueryParameters;
use Oxygen\Preferences\PreferencesManager;
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
     * @var PreferencesManager
     */
    private $preferences;

    /**
     * Constructs the UpcomingEventsController.
     *
     * @param UpcomingEventRepositoryInterface $repository
     * @param BlueprintManager $manager
     * @param UpcomingEventFieldSet $fields
     * @param PreferencesManager $preferences
     */
    public function __construct(UpcomingEventRepositoryInterface $repository, BlueprintManager $manager, UpcomingEventFieldSet $fields, PreferencesManager $preferences) {
        parent::__construct($repository, $manager, $fields);
        $this->preferences = $preferences;
    }

    protected function decoratePreviewContent($content) {
        return view($this->preferences->get('appearance.events::contentView'))->with('content', $content);
    }

    /**
     * Find bookings from TryBooking's `bookingUrlId` and ticket index.
     *
     * @param Request $request
     * @return JsonResponse
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
