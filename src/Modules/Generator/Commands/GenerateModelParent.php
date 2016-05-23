<?php

namespace MezzoLabs\Mezzo\Modules\Generator\Commands;

use MezzoLabs\Mezzo\Console\Commands\MezzoCommand;
use MezzoLabs\Mezzo\Core\Files\File;
use MezzoLabs\Mezzo\Modules\Generator\GeneratorModule;

class GenerateModelParent extends MezzoCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mezzo:generate:model-parent {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the model parent based on the model code.';

    /**
     * Create a new command instance.
     *
     * @return \MezzoLabs\Mezzo\Modules\Generator\Commands\GenerateForeignFields
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $reflectionManager = mezzo()->makeReflectionManager();
        $reflection = $reflectionManager->eloquentReflection($this->argument('model'));
        $schema = $reflection->schema();

        $schemas = new \MezzoLabs\Mezzo\Core\Schema\ModelSchemas();
        $schemas->addSchema($schema);

        $generatorFactory = GeneratorModule::make()->generatorFactory();
        $modelParentGenerator = $generatorFactory->modelParentGenerator($schemas);
        $modelParentGenerator->run();

        $modelParentGenerator->files()->each(function(File $file){
            $this->info('Created: ' . $file->filename());
        });


    }


}
