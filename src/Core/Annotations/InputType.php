<?php


namespace MezzoLabs\Mezzo\Core\Annotations;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
class InputType extends Annotation
{
    public $type;
}