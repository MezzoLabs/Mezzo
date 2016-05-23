<?php

namespace MezzoLabs\Mezzo\Core\Fluent;


class Fluent extends \Illuminate\Support\Fluent
{

    public function make()
    {
        return $this->toArray();
    }
} 