<?php

namespace MezzoLabs\Mezzo\Core\Schema\Relations;


class OneToMany extends OneToOneOrMany
{

    /**
     * @return OneToMany
     */
    static function make()
    {
        return parent::makeByType(static::class);
    }

    public function manySide($columnName, $tableName = false)
    {
        return $this->connectVia($columnName, $tableName);
    }

}