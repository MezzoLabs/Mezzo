<?php


namespace MezzoLabs\Mezzo\Core\Schema\InputTypes\ReadOnly;

use MezzoLabs\Mezzo\Core\Schema\InputTypes\RelationInputMultiple as WriteableRelationInputMultiple;

class RelationInputMultiple extends WriteableRelationInputMultiple
{
    protected $htmlAttributes = [
        'readonly' => 'readonly',
        'disabled' => 'true'

    ];
}