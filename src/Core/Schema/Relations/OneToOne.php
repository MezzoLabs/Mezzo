<?php

namespace MezzoLabs\Mezzo\Core\Schema\Relations;


class OneToOne extends OneToOneOrMany
{

    static function make()
    {
        return parent::makeByType(static::class);
    }


}