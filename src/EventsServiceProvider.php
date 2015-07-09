<?php

namespace OxygenModule\Events;

use Doctrine\ORM\EntityManager;
use Oxygen\Core\Blueprint\BlueprintManager;
use OxygenModule\Events\Repository\DoctrineUpcomingEventRepository;
use OxygenModule\Events\Repository\UpcomingEventRepositoryInterface;
use OxygenModule\Media\Presenter\HtmlPresenter;
use OxygenModule\Media\Presenter\PresenterInterface;
use OxygenModule\Media\Repository\DoctrineMediaRepository;
use OxygenModule\Media\Repository\MediaRepositoryInterface;
use OxygenModule\Media\Repository\MediaSubscriber;
use Oxygen\Data\BaseServiceProvider;

class EventsServiceProvider extends BaseServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boots the package.
     *
     * @return void
     */
    public function boot() {
        $this->app[BlueprintManager::class]->loadDirectory(__DIR__ . '/../resources/blueprints');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->loadEntitiesFrom(__DIR__ . '/Entity');

        // Repositories
        $this->app->bind(UpcomingEventRepositoryInterface::class, DoctrineUpcomingEventRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */

    public function provides() {
        return [
            UpcomingEventRepositoryInterface::class
        ];
    }

}
