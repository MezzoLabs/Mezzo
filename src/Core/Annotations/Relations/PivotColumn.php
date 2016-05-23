<?php


namespace MezzoLabs\Mezzo\Core\Annotations\Relations;

use MezzoLabs\Mezzo\Core\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
class PivotColumn extends Annotation
{
    public $name;

    public $type;

    public $rules = "";
}