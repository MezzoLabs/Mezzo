<?php


namespace MezzoLabs\Mezzo\Http\Responses;

use Closure;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Http\Response as DingoResponse;
use Dingo\Api\Http\Response\Factory as DingoResponseFactory;
use Illuminate\Support\Collection;


class ApiResponseFactory extends DingoResponseFactory
{
    /**
     * @param $code
     * @param string $message
     * @param string $title
     * @return DingoResponse
     */
    public function result($code, $message = "", $title = "Success")
    {
        if ($code == -1 || $code == false)
            throw new ResourceException('Exit with code:' . $code);

        return $this->withArray([
            'data' => [
                'success' => $code == 1,
                'code' => $code,
                'message' => $message,
                'title' => $title
            ]
        ]);
    }

    /**
     * @param $array
     * @return DingoResponse
     */
    public function withArray($array)
    {
        return $this->array($array);
    }

    public function collection(Collection $collection, $transformer, array $parameters = [], Closure $after = null)
    {
        $collection = parent::collection($collection, $transformer, $parameters, $after);

        return $collection;
    }


}