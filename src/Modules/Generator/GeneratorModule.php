<?php


namespace MezzoLabs\Mezzo\Modules\Generator;


use App\Tutorial;
use App\User;
use Illuminate\Support\Facades\Blade;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Modules\Generator\Commands\GenerateForeignFields;
use MezzoLabs\Mezzo\Modules\Generator\Commands\GenerateModelParent;

class GeneratorModule extends ModuleProvider
{

    /**
     * @var GeneratorFactory
     */
    protected $generatorFactory;

    protected $options = [
        'icon' => 'ion-ios-color-wand',
        'permissions' => 'developer'
    ];

    protected $commands = [
        GenerateForeignFields::class,
        GenerateModelParent::class
    ];

    protected $group = "development";

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('annotation', function ($string) {
            $re = "/\\('(\\w+)',\\s*(.*)\\)/";
            preg_match($re, $string, $matches);

            $type = $matches[1];
            $value = $matches[2];


            return "<?php echo '* @{$type} ' . trim(with({$value})); ?>
            ";
        });

        $this->loadViews();


    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Called when module is ready, model reflections are loaded.
     *
     * @return mixed
     */
    public function ready()
    {
    }

    /**
     * @return GeneratorModule
     */
    public static function make()
    {
        return parent::make();
    }

    /**
     * Get an instance of the factory
     *
     * @return \MezzoLabs\Mezzo\Modules\Generator\GeneratorFactory
     */
    public function generatorFactory()
    {
        if (!$this->generatorFactory)
            $this->generatorFactory = new GeneratorFactory($this->mezzo, $this);

        return $this->generatorFactory;
    }
}