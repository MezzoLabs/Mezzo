<?php


namespace MezzoLabs\Mezzo\Core\Annotations;


/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
class Property extends Annotation
{
    public $name;

    public $type;

    public $mode;

}