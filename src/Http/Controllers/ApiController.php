<?php


namespace MezzoLabs\Mezzo\Http\Controllers;


use Dingo\Api\Routing\Helpers as ApiHelpers;
use MezzoLabs\Mezzo\Http\Responses\ApiResponseFactory;
use MezzoLabs\Mezzo\Modules\User\Domain\Repositories\UserRepository;

/**
 * Class ApiController
 * @package MezzoLabs\Mezzo\Http\Controllers
 */
abstract class ApiController extends Controller
{
    use ApiHelpers;

    /**
     * @var \Dingo\Api\Http\Response\Factory
     */
    protected $response;

    /**
     * @return \MezzoLabs\Mezzo\Http\Requests\Request
     */
    protected function request()
    {
        return mezzo()->makeRequest();
    }

    /**
     * @return ApiResponseFactory
     */
    protected function response()
    {
        return mezzo()->make(ApiResponseFactory::class);
    }



}