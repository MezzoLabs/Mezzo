<?php

namespace MezzoLabs\Mezzo\Modules\Generator\Migration\Actions;

use MezzoLabs\Mezzo\Core\Schema\Attributes\Attribute;
use MezzoLabs\Mezzo\Modules\Generator\GeneratorException;
use MezzoLabs\Mezzo\Modules\Generator\NoTableFoundException;

class UpdateAction extends Action
{

    /**
     * @var Attribute
     */
    protected $old;

    /**
     * @var Attribute
     */
    protected $new;

    /**
     * @param Attribute $old
     * @param Attribute $new
     * @throws GeneratorException
     * @throws NoTableFoundException
     */
    public function __construct(Attribute $old, Attribute $new)
    {
        $this->old = $old;
        $this->new = $new;

        if (!$this->old->hasTable())
            throw new NoTableFoundException($this->old);

        if (!$this->new->hasTable())
            throw new NoTableFoundException($this->old);

        if ($this->old->getTable() !== $this->new->getTable())
            throw new GeneratorException('Cannot perform an update action from one table to another: From ' .
                $this->new->qualifiedName() . ' to ' . $this->old->qualifiedName());

    }

    public function isRename()
    {
        return $this->old->name() !== $this->new->name();
    }

    /**
     * The line that will be copied in the migration file inside the "up" function.
     *
     */
    public function migrationUp()
    {
        // TODO: Implement migrationUp() method.
    }

    /**
     * The line that will be copied in the migration file inside the "down" function.
     *
     * @return string
     */
    public function migrationDown()
    {
        // TODO: Implement migrationDown() method.
    }

    /**
     * Will return a qualified name that is different from all other possible actions.
     *
     * @return string
     */
    public function qualifiedName()
    {
        if (!$this->isRename())
            return 'rename.' . $this->old->qualifiedName() . '.to.' . $this->new->name();

        return 'update.' . $this->old->qualifiedName();
    }

    /**
     * @return Attribute
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * @return Attribute
     */
    public function getOld()
    {
        return $this->old;
    }


    /**
     * Returns the name of the table which this action affects.
     *
     * @return string
     */
    public function tableName()
    {
        return $this->new->getTable();
    }
}