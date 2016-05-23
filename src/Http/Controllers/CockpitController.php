<?php


namespace MezzoLabs\Mezzo\Http\Controllers;

use MezzoLabs\Mezzo\Http\Responses\ModuleResponseFactory;

abstract class CockpitController extends Controller
{
    protected $noAuth = false;

    public function __construct()
    {
        if (!$this->noAuth) {
            $this->middleware('mezzo.auth');
        }
    }

    /**
     * @return ModuleResponseFactory
     */
    public function response()
    {
        return app(ModuleResponseFactory::class);
    }

    /**
     * @param string $pageName
     * @param $parameters
     * @return \Illuminate\Http\RedirectResponse
     * @throws \MezzoLabs\Mezzo\Exceptions\InvalidArgumentException
     */
    protected function redirectToPage($pageName = "index", $parameters = [])
    {
        $page = $this->module()->makePage($pageName);

        return $this->redirector()->route($page->routeName(), $parameters);
    }

    /**
     * @return \Illuminate\Routing\Redirector
     */
    protected function redirector()
    {
        return app()->make(\Illuminate\Routing\Redirector::class);
    }


}