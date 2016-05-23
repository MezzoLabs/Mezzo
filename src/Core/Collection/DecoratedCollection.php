<?php


namespace MezzoLabs\Mezzo\Core\Collection;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection as IlluminateCollection;
use IteratorAggregate;
use Traversable;

abstract class DecoratedCollection implements ArrayAccess, Arrayable, Countable, IteratorAggregate
{
    use HasLookupTable;

    /**
     * @var IlluminateCollection
     */
    protected $collection;

    public function __construct(IlluminateCollection $collection = null)
    {
        if (!$collection)
            $collection = new IlluminateCollection();

        $this->collection = $collection;
    }

    /**
     * @return IlluminateCollection
     */
    public function collection()
    {
        return $this->collection;
    }


    /**
     * Execute a callback over each item.
     *
     * @param  callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        return $this->collection()->each($callback);
    }

    /**
     * Run a filter over each of the items.
     *
     * @param  callable|null $callback
     * @return static
     */
    public function filter(callable $callback = null)
    {
        return new static($this->collection()->filter($callback));
    }

    /**
     * Run a map over each of the items.
     *
     * @param  callable $callback
     * @return static
     */
    public function map(callable $callback)
    {
        return new static($this->collection()->map($callback));
    }

    /**
     * Put an item in the collection by key.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return $this
     */
    public function put($key, $value)
    {
        $this->collection()->put($key, $value);
    }

    /**
     * Push an item onto the end of the collection.
     *
     * @param  mixed $value
     * @return $this
     */
    public function push($value)
    {
        return $this->collection()->push($value);
    }

    /**
     * Synonym for push.
     *
     * @param  mixed $value
     * @return $this
     */
    public function add($value)
    {
        return $this->push($value);
    }


    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->collection()->toArray();
    }

    /**
     * Get an item from the collection by key.
     *
     * @param  mixed $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->collection()->get($key, $default);
    }

    /**
     * Get the items in the collection that are not present in the given items.
     *
     * @param  mixed $items
     * @return static
     */
    public function diff($items)
    {
        return $this->collection()->diff($items);
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param  mixed $key
     * @return bool
     */
    public function has($key)
    {
        return $this->collection()->has($key);
    }

    /**
     * Determine if an item exists in the collection.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return bool
     */
    public function contains($key, $value = null)
    {
        return $this->collection()->contains($key, $value);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return $this->collection()->getIterator();
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return $this->collection()->offsetExists($offset);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->collection()->offsetGet($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->collection()->offsetSet($offset, $value);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->collection()->offsetUnset($offset);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return $this->collection()->count();
    }

    /**
     * Determine if the collection is empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->collection->isEmpty();
    }
}