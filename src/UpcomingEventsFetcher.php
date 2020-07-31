<?php


namespace OxygenModule\Events;


use Illuminate\Container\Container;
use Oxygen\Core\Templating\TwigTemplateCompiler;
use OxygenModule\Events\Entity\UpcomingEvent;

class UpcomingEventsFetcher {
    /**
     * @var Container
     */
    private $app;
    /**
     * @var string
     */
    private $repositoryName;
    /**
     * @var TwigTemplateCompiler
     */
    private $compiler;
    
    private $results = [];

    /**
     * UpcomingEventsFetcher constructor.
     * @param Container $app
     * @param string $repositoryName
     * @param TwigTemplateCompiler $compiler
     */
    public function __construct(Container $app, string $repositoryName, TwigTemplateCompiler $compiler) {
        $this->app = $app;
        $this->compiler = $compiler;
        $this->repositoryName = $repositoryName;
    }
    
    public function getLatest($amount) {
        $this->loadFromDatabase($amount);
        return array_map(function(UpcomingEvent $event) {
            $this->compiler->render($event);
        }, $this->results[$amount]);
    }
    
    private function loadFromDatabase($amount) {
        if(isset($this->results[$amount])) {
            return;
        }
        $this->results[$amount] = $this->app[$this->repositoryName]->getLatest($amount);
    }

}