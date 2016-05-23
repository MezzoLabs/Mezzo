<?php


namespace MezzoLabs\Mezzo\Core\Booting\Bootstrappers;


use Carbon\Carbon;
use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Http\Transformers\CarbonTransformer;
use MezzoLabs\Mezzo\Http\Transformers\Plugins\LabelTransformerPlugin;
use MezzoLabs\Mezzo\Http\Transformers\Plugins\PermissionsTransformerPlugin;
use MezzoLabs\Mezzo\Http\Transformers\TransformerRegistrar;

class RegisterTransformers implements Bootstrapper
{

    /**
     * Run the booting process for this service.
     *
     * @param Mezzo $mezzo
     * @return mixed
     */
    public function bootstrap(Mezzo $mezzo)
    {
        $registrar = TransformerRegistrar::make();

        $registrar->addTransformer(Carbon::class, CarbonTransformer::class);

        $registrar->addPlugin(LabelTransformerPlugin::class);
        $registrar->addPlugin(PermissionsTransformerPlugin::class);
    }
}