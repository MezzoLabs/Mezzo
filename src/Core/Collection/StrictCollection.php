<?php


namespace MezzoLabs\Mezzo\Core\Collection;

abstract class StrictCollection extends DecoratedCollection
{
    /**
     * Put an item in the collection by key.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return $this
     */
    public function put($key, $value)
    {
        $this->assertThatItemIsValid($value);
        return parent::put($key, $value);
    }

    /**
     * Push an item onto the end of the collection.
     *
     * @param  mixed $value
     * @return $this
     */
    public function push($value)
    {
        $this->assertThatItemIsValid($value);
        return parent::push($value);
    }


    protected function assertThatItemIsValid($value)
    {
        if (!$this->checkItem($value)) {
            $this->fail($value);
        }

        return true;
    }

    protected function fail($value)
    {
        $type = gettype($value);

        if($type == "object")
            $type = get_class($value);

        throw new StrictCollectionException('"'. $type . '" is an invalid argument for this ' .
            'strict collection: "' . get_class($this) . '"');
    }

    /**
     * Check if a item can be part of this collection.
     *
     * @param $value
     * @return boolean
     */
    abstract protected function checkItem($value);

}