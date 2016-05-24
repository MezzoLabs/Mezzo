<?php


namespace MezzoLabs\Mezzo\Http\Format;

use Dingo\Api\Http\Response\Format\Json as DingoJsonFormat;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Helpers\DebugService;

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

        $debugService = app(DebugService::class);

        $data->put('queries', $debugService->getQueries());

        return $data->toArray();
    }


}