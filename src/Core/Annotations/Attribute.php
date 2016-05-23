<?php


namespace MezzoLabs\Mezzo\Core\Annotations;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
class Attribute extends Annotation
{
    public $type;

    public $hidden;

}