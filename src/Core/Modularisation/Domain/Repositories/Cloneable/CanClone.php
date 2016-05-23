<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\Cloneable;


use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;

trait CanClone
{
    /**
     * @param $id
     * @return MezzoModel
     */
    public function duplicate($id) : MezzoModel
    {
        $original = $this->findOrFail($id);

        $attributes = collect($original->getOriginal())->except('id');

        $new = $this->modelInstance();

        $new->forceFill($attributes->toArray());

        $new->save();


        return $new;
    }

}