<?php

namespace MezzoLabs\Mezzo\Modules\Generator\Migration\Actions;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Schema\Attributes\Attribute;

class Actions extends Collection
{
    /**
     * @param Action $action
     * @return $this
     */
    public function register(Action $action)
    {
        return $this->put($action->qualifiedName(), $action);
    }

    /**
     * Add a new attribute to the system via a CreateAction.
     *
     * @param Attribute $attribute
     * @return \MezzoLabs\Mezzo\Modules\Generator\Migration\Actions\Actions
     */
    public function registerCreate(Attribute $attribute)
    {
        return $this->register(new CreateAction($attribute));
    }

    /**
     * Remove an attribute from the system (database, models etc.)
     *
     * @param Attribute $attribute
     * @return \MezzoLabs\Mezzo\Modules\Generator\Migration\Actions\Actions
     */
    public function registerRemove(Attribute $attribute)
    {
        return $this->register(new RemoveAction($attribute));
    }

    /**
     * Register a new Update Action that performs a rename of an attribute.
     *
     * @param string $oldName
     * @param Attribute $newAttribute
     * @return \MezzoLabs\Mezzo\Modules\Generator\Migration\Actions\Actions
     * @internal param $table
     * @internal param $from
     * @internal param $to
     */
    public function registerRename($oldName, Attribute $newAttribute)
    {
        $oldAttribute = clone($newAttribute);
        $oldAttribute->setName($oldName);

        return $this->register(new UpdateAction($oldAttribute, $newAttribute));
    }

    /**
     * Returns multiple Actions grouped by their table name.
     *
     * @return Collection
     */
    public function groupByTables()
    {
        $grouped = new Collection();

        $this->each(function (Action $action, $key) use ($grouped) {
            $table = $action->tableName();

            if (!$grouped->has($table))
                $grouped->put($table, new Actions());


            $grouped = $grouped->get($table);
            $grouped->register($action);
        });

        return $grouped;
    }


} 