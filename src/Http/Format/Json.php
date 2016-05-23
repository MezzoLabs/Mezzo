<?php


namespace MezzoLabs\Mezzo\Http\Format;

use Dingo\Api\Http\Response\Format\Json as DingoJsonFormat;
use Illuminate\Support\Collection;

class Json extends DingoJsonFormat
{
    public function formatArray($content)
    {
        if(mezzo()->config('api.debug'))
            $content['meta']['debug'] = $this->debugData();

        return parent::formatArray($content);
    }

    protected function debugData(){
        $data = new Collection();

        $debugbar = app('debugbar');

        $data->put('queries', $debugbar->getCollector('queries')->collect());

        return $data->toArray();
    }


}