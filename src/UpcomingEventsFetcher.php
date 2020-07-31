<?php


namespace OxygenModule\Events;


use Illuminate\Container\Container;
use Oxygen\Core\Templating\TwigTemplateCompiler;
use OxygenModule\Events\Entity\UpcomingEvent;
use OxygenModule\Events\Repository\UpcomingEventRepositoryInterface;

class UpcomingEventsFetcher {
    /**
     * @var Container
     */
    private $app;
    /**
     * @var TwigTemplateCompiler
     */
    private $compiler;
    
    private $results = [];

    /**
     * UpcomingEventsFetcher constructor.
     * @param Container $app
     * @param TwigTemplateCompiler $compiler
     */
    public function __construct(Container $app, TwigTemplateCompiler $compiler) {
        $this->app = $app;
        $this->compiler = $compiler;
    }
    
    public function getLatest($amount) {
        $this->loadFromDatabase($amount);
        return $this->results[$amount];
    }

    /**
     * Renders to string
     *
     * @param UpcomingEvent $event
     * @return string
     */
    public function render(UpcomingEvent $event) {
        return $this->compiler->render($event);
    }
    
    private function loadFromDatabase($amount) {
        if(isset($this->results[$amount])) {
            return;
        }
        $this->results[$amount] = $this->app[UpcomingEventRepositoryInterface::class]->getLatest($amount);
    }

}