<?php
/**
 * Created by: simon.schneider
 * Date: 17.09.2015 - 15:20
 * Project: MezzoDemo
 */


namespace MezzoLabs\Mezzo\Core\Traits;


trait CanFireEvents
{
    /**
     * Throw a Mezzo event
     *
     * @param $event
     * @param  mixed $payload
     * @param  bool $halt
     * @return array|null
     */
    public function fire($event, $payload = [], $halt = false)
    {
        event($event, $payload, $halt);
    }
} 