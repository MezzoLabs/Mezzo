<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\Cloneable;


use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;

interface CloneRepositoryContract
{
    /**
     * @param $id
     * @return MezzoModel
     */
    public function duplicate($id) : MezzoModel;
}