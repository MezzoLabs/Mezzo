<?php


namespace MezzoLabs\Mezzo\Cockpit\Http\Controllers;


class MainController extends NonModuleController
{
    public function __construct()
    {
        $this->middleware('mezzo.auth');
    }

    public function start()
    {
        return view('cockpit::start');
    }
}