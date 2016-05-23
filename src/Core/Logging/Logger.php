<?php


namespace MezzoLabs\Mezzo\Core\Logging;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Logger extends \Monolog\Logger
{
    public function logEloquentEvent($eventType = "saving", Model $model)
    {
        $message = ucfirst($eventType) . ' ' . get_class($model) . ':' . $model->id;

        if ($eventType == 'updating')
            $message .= ' | dirty: [' . implode(',', array_keys($model->getDirty())) . '] ';

        $this->info($message);

    }

    /**
     * @return Logger
     */
    public static function make()
    {
        return app()->make(Logger::class);
    }

    protected function userIdentifier()
    {
        $user = Auth::user();

        if (!$user)
            return "<guest>";

        return $user->email;
    }

    protected function userIp()
    {
        return Request::getClientIp();
    }

    protected function currentUrl()
    {
        return Request::url();
    }

    public function currentRequestInfo()
    {
        return $this->info('--- New request by: ' . $this->userIdentifier() . ' [' . $this->userIp() . '] -> ' . $this->currentUrl());
    }

    public function logMissingPermission($missingPermission)
    {
        $info = [
            $this->userIdentifier(),
            $this->userIp(),
            $this->currentUrl()
        ];
        return $this->critical('Missing permissions: ' . $missingPermission . ' | ' . implode(', ', $info) );
    }
}