<?php


namespace MezzoLabs\Mezzo\Http\Transformers;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Exceptions\TransformerException;
use MezzoLabs\Mezzo\Http\Transformers\Plugins\TransformerPlugin;

class TransformerRegistrar
{
    /**
     * @var Collection
     */
    protected $bindings;

    /**
     * @var Collection
     */
    protected $plugins;

    public function __construct()
    {
        $this->bindings = new Collection();
        $this->plugins = new Collection();
    }


    /**
     * @param string $class
     * @param string $transformer
     */
    public function register($class, $transformer)
    {
        app('Dingo\Api\Transformer\Factory')->register($class, $transformer);
        $this->bindings->put($class, $transformer);

    }

    /**
     * @param string $class
     * @param string $transformer
     * @throws InvalidArgumentException
     * @throws TransformerException
     */
    public static function addTransformer($class, $transformer)
    {
        if (!is_string($class))
            throw new InvalidArgumentException($class);

        if (!is_string($transformer))
            throw new InvalidArgumentException($transformer);

        if (!class_exists($class))
            throw new TransformerException('Cannot find class ' . $class);

        if (!class_exists($transformer))
            throw new TransformerException('Cannot find transformer ' . $transformer . ' for ' . $class);

        $registrar = static::make();
        $registrar->register($class, $transformer);
    }

    public static function addTransformers($transformers)
    {
        foreach ($transformers as $class => $transformer) {
            static::addTransformer($class, $transformer);
        }
    }

    public static function addPlugin($class)
    {
        $registrar = static::make();
        $registrar->registerPlugin(app()->make($class));
    }

    /**
     * @param TransformerPlugin $plugin
     */
    public function registerPlugin(TransformerPlugin $plugin)
    {
        $this->plugins->put(get_class($plugin), $plugin);
    }

    public function findTransformerClass($modelClass)
    {
        if (is_string($modelClass))
            return $this->bindings->get($modelClass);

        if (is_object($modelClass))
            return $this->bindings->get(get_class($modelClass));

        if ($modelClass instanceof Collection)
            return $this->bindings->get(get_class($modelClass->first()));

        throw new InvalidArgumentException($modelClass);
    }

    /**
     * @return static
     */
    public static function make()
    {
        return app(static::class);
    }

    /**
     * @return Collection
     */
    public function plugins()
    {
        return $this->plugins;
    }

    /**
     * Creates an array with data that is created via the registered plugins.
     *
     * @param MezzoModel $model
     * @return array
     */
    public function callPlugins(MezzoModel $model) : array
    {
        $data = new Collection();


        $this->plugins->each(function (TransformerPlugin $plugin) use ($model, &$data) {
            $data = $data->merge($plugin->transform($model));
        });

        return $data->toArray();
    }

}