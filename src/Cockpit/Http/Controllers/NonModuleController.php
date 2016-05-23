<?php


namespace MezzoLabs\Mezzo\Cockpit\Http\Controllers;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

abstract class NonModuleController extends Controller
{
    use DispatchesJobs, ValidatesRequests, AuthorizesRequests;
}