<?php


namespace MezzoLabs\Mezzo\Http\Transformers;

use League\Fractal\TransformerAbstract;

class Transformer extends TransformerAbstract
{
    public function transform($toTransform)
    {
        return $toTransform;
    }
}