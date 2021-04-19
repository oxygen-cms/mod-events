<?php

namespace OxygenModule\Events;

use Oxygen\Core\Blueprint\BlueprintManager;
use Oxygen\Core\Templating\DoctrineResourceLoader;
use Oxygen\Core\Templating\TwigTemplateCompiler;
use OxygenModule\Events\Repository\DoctrineUpcomingEventRepository;
use OxygenModule\Events\Repository\UpcomingEventRepositoryInterface;
use Oxygen\Data\BaseServiceProvider;
use Oxygen\Preferences\PreferencesManager;

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
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->loadRoutesFrom(__DIR__ . '/../resources/routes.php');

        $this->app->resolving(TwigTemplateCompiler::class, function(TwigTemplateCompiler $c, $app) {
            $c->getLoader()->addResourceType('events', new DoctrineResourceLoader($app, UpcomingEventRepositoryInterface::class));
            $c->getTwig()->addGlobal('upcomingEvents', new UpcomingEventsFetcher($app, $c));
            $c->setAllowedMethods(UpcomingEventsFetcher::class, ['getLatest', 'render']);
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
