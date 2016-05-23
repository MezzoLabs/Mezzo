<?php


namespace MezzoLabs\Mezzo\Http\Transformers;


use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class CarbonTransformer extends TransformerAbstract
{
    public function transform(Carbon $date)
    {
        return $date->toDateTimeString();
    }
}