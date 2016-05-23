<?php


namespace MezzoLabs\Mezzo\Core\Annotations\Relations;


use MezzoLabs\Mezzo\Core\Annotations\Annotation;

abstract class RelationSideAnnotation extends Annotation
{
    public $table;

    public $primaryKey;

    public $naming;
}