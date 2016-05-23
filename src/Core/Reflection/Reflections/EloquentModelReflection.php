<?php

namespace MezzoLabs\Mezzo\Core\Reflection\Reflections;

use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Database\Table;
use MezzoLabs\Mezzo\Core\Reflection\ModelParser;

class EloquentModelReflection extends ModelReflection
{
    /**
     * @var Collection
     */
    protected $relationshipReflections;

    /**
     * @var \MezzoLabs\Mezzo\Core\Database\Table
     */
    protected $databaseTable;

    /**
     * @var ModelParser
     */
    protected $parser;

    /**
     * @return EloquentRelationshipReflections
     */
    public function relationshipReflections()
    {
        if (!$this->relationshipReflections) {
            $this->relationshipReflections = $this->parser()->relationships();
        }

        return $this->relationshipReflections;
    }

    /**
     * Get the ReflectionClass object of the underlying model
     *
     * @return ModelParser
     */
    public function parser()
    {
        if (!$this->parser)
            $this->parser = new ModelParser($this);

        return $this->parser;
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Database\Table
     */
    public function databaseTable()
    {
        if (!$this->databaseTable)
            $this->databaseTable = Table::fromModelReflection($this);

        return $this->databaseTable;
    }


}