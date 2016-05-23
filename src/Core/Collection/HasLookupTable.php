<?php

namespace MezzoLabs\Mezzo\Core\Collection;


use Illuminate\Support\Collection;

trait HasLookupTable
{
    /**
     * @var Collection
     */
    protected $lookups;

    /**
     * @param $table
     * @param $key
     * @param $value
     */
    public function addLookup($table, $key, $value)
    {
        $this->lookupTable($table)->put($key, $value);
    }

    public function lookup($key, $tableName = null)
    {
        if ($tableName)
            return $this->lookupInTable($key, $tableName);

        foreach ($this->lookups() as $currentTableName => $table) {
            $value = $this->lookupInTable($key, $currentTableName);

            if ($value)
                return $value;
        }
    }

    private function lookupInTable($key, $tableName)
    {
        return $this->lookupTable($tableName)->get($key);
    }

    /**
     * @return Collection
     */
    public function lookups()
    {
        if (!$this->lookups)
            $this->lookups = new Collection();

        return $this->lookups;
    }

    /**
     * @param $tableName
     * @return Collection
     */
    public function lookupTable($tableName)
    {
        $lookups = $this->lookups();

        if (!$lookups->has($tableName))
            $lookups->put($tableName, new Collection());

        return $lookups->get($tableName);
    }


}