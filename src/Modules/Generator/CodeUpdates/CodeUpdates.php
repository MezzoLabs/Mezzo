<?php


namespace MezzoLabs\Mezzo\Modules\Generator\Generators\CodeUpdates;


use Illuminate\Support\Collection;

class CodeUpdates extends Collection
{
    public function addUpdate(CodeUpdate $update)
    {
        $this->push($update);
    }
}