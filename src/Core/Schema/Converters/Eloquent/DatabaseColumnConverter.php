<?php

namespace MezzoLabs\Mezzo\Core\Schema\Converters\Eloquent;


use MezzoLabs\Mezzo\Core\Database\DatabaseColumn;
use MezzoLabs\Mezzo\Core\Fluent\FluentAttribute;
use MezzoLabs\Mezzo\Core\Schema\Attributes\AtomicAttribute;
use MezzoLabs\Mezzo\Core\Schema\Attributes\RelationAttribute;
use MezzoLabs\Mezzo\Core\Schema\Columns\JoinColumn;
use MezzoLabs\Mezzo\Core\Schema\Converters\Converter;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;

class DatabaseColumnConverter extends Converter
{

    public function run($toConvert)
    {
        if ($toConvert instanceof DatabaseColumn)
            return $this->viaDatabaseColumn($toConvert);

        if ($toConvert instanceof JoinColumn)
            return $this->viaJoinColumn($toConvert);

        throw new InvalidArgumentException($toConvert);
    }

    /**
     * @param DatabaseColumn $databaseColumn
     * @return AtomicAttribute|RelationAttribute
     */
    public function viaDatabaseColumn(DatabaseColumn $databaseColumn)
    {
        $fluentAttribute = new FluentAttribute();

        $model = $databaseColumn->table()->modelReflection();

        $ruleString = $model->rules($databaseColumn->name());

        if ($databaseColumn->isForeignKey()) {
            $fluentAttribute
                ->joinColumn($databaseColumn->joinColumn());
        } else {
            $fluentAttribute
                ->name($databaseColumn->name())
                ->type($databaseColumn->type())
                ->table($databaseColumn->table()->name())
                ->rules($ruleString);
        }

        $attribute = $fluentAttribute->make();


        return $attribute;

    }

    /**
     * @param JoinColumn $column
     * @return AtomicAttribute|RelationAttribute
     */
    public function viaJoinColumn(JoinColumn $column)
    {
        $fluentAttribute = (new FluentAttribute())->joinColumn($column);

        return $fluentAttribute->make();
    }


}