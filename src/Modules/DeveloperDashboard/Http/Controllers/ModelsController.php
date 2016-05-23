<?php


namespace MezzoLabs\Mezzo\Modules\DeveloperDashboard\Http\Controllers;


use MezzoLabs\Mezzo\Core\Reflection\ReflectionManager;
use MezzoLabs\Mezzo\Http\Controllers\CockpitController;
use MezzoLabs\Mezzo\Modules\DeveloperDashboard\Http\Pages\ModelsPage;

class ModelsController extends CockpitController
{
    /**
     * @var ReflectionManager
     */
    private $reflectionManager;

    public function __construct(ReflectionManager $reflectionManager)
    {
        parent::__construct();

        $this->reflectionManager = $reflectionManager;
    }

    public function show()
    {


        return $this->page(ModelsPage::class, [
            'models' => [
                'mezzo' => $this->reflectionManager->sets()->mezzoReflections(),
                'eloquent' => $this->reflectionManager->sets()->eloquentReflections()
            ]

        ]);
    }
}