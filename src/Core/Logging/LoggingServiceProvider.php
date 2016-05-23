<?php


namespace MezzoLabs\Mezzo\Core\Logging;


use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;

class LoggingServiceProvider extends ServiceProvider
{
    protected $eloquentEventTypesToLog = [
        'creating', 'created',
        'updating', 'updated',
        'deleting', 'deleted'
    ];

    const LEVEL_DEBUG = 200;
    const LEVEL_WARNING = 100;
    const LEVEL_ERROR = 1;

    /**
     * @var DispatcherContract
     */
    protected $dispatcher;

    /**
     * @var Logger
     */
    protected $logger;


    /**
     * Register any other events for mezzo
     *
     * @param  DispatcherContract $dispatcher
     * @return void
     */
    public function boot(DispatcherContract $dispatcher, Logger $logger)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;


        $this->listenForRoutes();
        $this->listenForEloquentEvents();
    }

    protected function listenForRoutes()
    {
        if(!$this->inLevel(LoggingServiceProvider::LEVEL_DEBUG)){
            return false;
        }

        $this->dispatcher->listen('router.matched', function () {
            $this->logger->currentRequestInfo();
        });
    }

    protected function listenForEloquentEvents()
    {

        if(!$this->inLevel(LoggingServiceProvider::LEVEL_DEBUG)){
            return false;
        }

        $this->dispatcher->listen('eloquent.*', function (Model $model) {
            $eventType = $this->getEloquentEventType($this->dispatcher->firing());

            if (!$this->logsEloquentEventType($eventType))
                return true;

            $this->logger->logEloquentEvent($eventType, $model);
        });
    }

    protected function getEloquentEventType($longEventName)
    {
        $parts = explode(':', $longEventName);
        $parts = explode('.', $parts[0]);

        return $parts[1];
    }

    /**
     * @param $eventName
     * @return bool
     */
    protected function logsEloquentEventType($eventName)
    {
        return in_array($eventName, $this->eloquentEventTypesToLog);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->bindLogger();
    }

    protected function bindLogger()
    {
        $this->app->singleton(Logger::class, function () {
            $logger = new Logger('Mezzo Logger');
            $logger->pushHandler(new StreamHandler(storage_path('logs/mezzo/' . date("Y-m-d") . '_mezzo.log'), Logger::INFO));
            $logger->pushHandler(new StreamHandler(storage_path('logs/mezzo.log'), Logger::INFO));

            if (env('SLACK_TOKEN')) {
                try {
                    //TODO: Check for internet connectivity before registering slack handler
                    //$logger->pushHandler(new SlackHandler(env('SLACK_TOKEN'), 'mezzo'));
                } catch (\Exception $e) {
                    mezzo_dd('slack failed');
                    // We don`t have any internet connection, go on without the Slack handler.
                }
            }

            return $logger;
        });

    }

    public function level() : int
    {
        return (env('MEZZO_LOGGER_LEVEL', LoggingServiceProvider::LEVEL_ERROR));
    }

    public function inLevel(int $level)
    {
        return $this->level() >= $level;
    }
}