<?php

namespace MezzoLabs\Mezzo\Core\Schema;


use Illuminate\Support\Collection;

class ModelSchemas extends Collection
{

    public function addSchema(ModelSchema $schema)
    {
        $this->put($schema->className(), $schema);
    }
}