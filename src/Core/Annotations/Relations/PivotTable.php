<?php


namespace MezzoLabs\Mezzo\Core\Annotations\Relations;

use MezzoLabs\Mezzo\Core\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
class PivotTable extends Annotation
{
    public $name;

    public $fromColumn;

    public $toColumn;
}