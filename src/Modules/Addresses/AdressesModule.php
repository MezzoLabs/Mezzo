<?php


namespace MezzoLabs\Mezzo\Modules\Addresses;


use App\Address;
use App\User;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Http\Transformers\TransformerRegistrar;
use MezzoLabs\Mezzo\Modules\Addresses\Http\Transformers\DistanceTransformerPlugin;
use MezzoLabs\Mezzo\Modules\Addresses\Schema\Rendering\AddressAttributeRenderer;

class AddressesModule extends ModuleProvider
{
    protected $models = [
        Address::class,
    ];

    protected $options = [
        'visible' => false
    ];


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
        $this->loadViews();
        $addressReflection = $this->modelReflectionSets->get(Address::class);

        $this->registerAttributeRenderer(AddressAttributeRenderer::class);

        $transformers = TransformerRegistrar::make();

        $transformers->registerPlugin(new DistanceTransformerPlugin());

        //dd($addressReflection->relationships());
    }
}