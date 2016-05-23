<?php


namespace MezzoLabs\Mezzo\Modules\General\Options;


use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Permission\PermissionGuard;
use MezzoLabs\Mezzo\Modules\General\Domain\Repositories\OptionRepository;
use MezzoLabs\Mezzo\Modules\General\Exceptions\OptionNotFoundException;

class OptionsService
{
    /**
     * @var EloquentCollection
     */
    protected static $options;

    protected static $booted = false;

    public function __construct()
    {
        $this->boot();
    }


    /**
     * Load the options from the database.
     */
    public function boot()
    {
        if (static::$booted)
            return;

        static::$options = $this->repository()->all();
        static::$booted = true;
    }

    /**
     * Create a new
     *
     * @param $name
     * @param $value
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function set($name, $value)
    {
        $option = new \App\Option();

        if (!PermissionGuard::make()->allowsCreate($option))
            PermissionGuard::fail();

        $data = ['name' => $name, 'value' => $value];
        $exists = $this->has($name);

        if ($exists)
            unset($data['name']);

        if ($this->has($name))
            $option->validateOrFail($data, 'update');
        else
            $option->validateOrFail($data, 'create');


        if ($exists)
            $model = $this->repository()->update($data, $this->getOption($name)->id);
        else
            $model = $this->repository()->create($data);

        $this->collection()->push($model);

        return $model;
    }

    public function get($name, $default = null, $orFail = false)
    {
        $found = $this->getOption($name);

        if (!$found && $orFail) {
            throw new OptionNotFoundException($name);
        }

        if (!$found)
            return $default;

        return $found->getAttribute('value');
    }

    /**
     * @param $name
     * @return mixed|null
     * @throws OptionNotFoundException
     */
    public function getOrFail($name)
    {
        return $this->get($name, null, true);
    }

    /*
     * Get a option from the cached option array.
     *
     * @return \App\Option|null
     */
    public function getOption($name, $default = null)
    {
        $index = $this->search($name);

        if ($index === false)
            return $default;

        return $this->collection()->get($index);
    }

    /**
     * @return Collection
     */
    public function all()
    {
        return $this->collection()->keyBy('name')->pluck('value', 'name');
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return $this->search($name) !== false;
    }

    /**
     * @param $name
     * @return false|integer
     */
    public function search($name)
    {
        return $this->collection()->search(function (\App\Option $item, $key) use ($name) {
            return $item->name === $name;
        });
    }

    /**
     * @return EloquentCollection
     */
    public function collection()
    {
        return static::$options;
    }

    /**
     * @return OptionRepository
     */
    public function repository()
    {
        return app()->make(OptionRepository::class);
    }
}