<?php

namespace MezzoLabs\Mezzo\Core\ThirdParties\NestedSet;

use Kalnoy\Nestedset\Node as NestedsetNode;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\HasMezzoAnnotations;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;

abstract class MezzoNestedSetNode extends NestedSetNode implements MezzoModel
{
    use HasMezzoAnnotations;

    protected $rules = [];
}