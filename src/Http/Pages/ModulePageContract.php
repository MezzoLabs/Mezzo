<?php


namespace MezzoLabs\Mezzo\Http\Pages;


interface ModulePageContract
{
    public function slug();

    public function title();

    public function name();

    public function qualifiedName();

    public function template();

    public function latestData();
}