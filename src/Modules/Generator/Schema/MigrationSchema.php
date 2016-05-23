<?php

namespace MezzoLabs\Mezzo\Modules\Generator\Schema;


use MezzoLabs\Mezzo\Modules\Generator\Migration\Actions\Actions;

class MigrationSchema extends FileSchema
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var Actions
     */
    protected $actions;

    /**
     * @param $table
     * @param Actions $actions
     * @internal param Attributes $toAdd
     * @internal param Attributes $toRemove
     */
    public function __construct($table, Actions $actions)
    {
        $this->table = $table;
        $this->actions = $actions;
    }

    /**
     * The content of the generated migration file.
     *
     * @return string
     */
    public function content()
    {
        return $this->fillTemplate(['migration' => $this]);
    }

    /**
     * The name of the template inside the
     *
     * @return string
     */
    protected function templateName()
    {
        return 'migration';
    }

    /**
     * Return the class name of the migration
     *
     * @return string
     */
    public function name()
    {
        return implode('', $this->nameParts());
    }

    public function shortFileName()
    {
        $parts = $this->nameParts();

        foreach ($parts as &$part) $part = strtolower($part);

        $date = date('Y_m_d_His');

        return $date . '_' . implode('_', $parts) . '.php';
    }

    protected function fillTemplate($data)
    {
        return parent::fillTemplate($data);
    }

    protected function nameParts()
    {
        $parts = [];

        $parts[] = "Mezzo";

        if (!$this->tableIsPersisted()) {
            $parts[] = 'Create';
        } else {
            $parts[] = 'Update';
        }

        $parts[] = ucfirst($this->table);
        $parts[] = 'Table';

        return $parts;
    }

    /**
     * @return string
     */
    public function table()
    {
        return $this->table;
    }

    /**
     * @return bool
     */
    public function tableIsPersisted()
    {
        return mezzo()->makeDatabaseReader()->tableIsPersisted($this->table());
    }


    /**
     * @return Actions
     */
    public function actions()
    {
        return $this->actions;
    }


}