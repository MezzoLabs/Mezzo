<?php


namespace MezzoLabs\Mezzo\Events\Core;


use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Events\Event;

class MezzoBooted extends Event
{
    /**
     * @var Mezzo
     */
    private $mezzo;

    public function __construct($mezzo)
    {

        $this->mezzo = $mezzo;
    }
} 