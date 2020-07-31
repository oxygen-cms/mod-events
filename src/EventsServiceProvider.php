<?php

namespace OxygenModule\Events;

use Doctrine\ORM\EntityManager;
use Oxygen\Core\Blueprint\BlueprintManager;
use Oxygen\Core\Templating\TwigTemplateCompiler;
use OxygenModule\Events\Repository\DoctrineUpcomingEventRepository;
use OxygenModule\Events\Repository\UpcomingEventRepositoryInterface;
use OxygenModule\Media\Presenter\HtmlPresenter;
use OxygenModule\Media\Presenter\PresenterInterface;
use OxygenModule\Media\Repository\DoctrineMediaRepository;
use OxygenModule\Media\Repository\MediaRepositoryInterface;
use OxygenModule\Media\Repository\MediaSubscriber;
use Oxygen\Data\BaseServiceProvider;
use Oxygen\Preferences\PreferencesManager;
use Oxygen\Core\Database\AutomaticMigrator;

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
        $this->app[PreferencesManager::class]->loadDirectory(__DIR__ . '/../resources/preferences');
        $this->app[AutomaticMigrator::class]->loadMigrationsFrom(__DIR__ . '/../migrations', 'oxygen/mod-events');

        $this->loadRoutesFrom(__DIR__ . '/../resources/routes.php');

        $this->app->resolving(TwigTemplateCompiler::class, function(TwigTemplateCompiler $c, $app) {
            $c->getTwig()->addGlobal('upcomingEvents', new UpcomingEventsFetcher($app, UpcomingEventRepositoryInterface::class, $c));
        });
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
            TwigTemplateCompiler::class,
            UpcomingEventRepositoryInterface::class
        ];
    }

}
