<?php

namespace MezzoLabs\Mezzo\Modules\Generator\Migration\Actions;

class RemoveAction extends AttributeAction
{

    /**
     * @var CreateAction
     */
    protected $reverseAction;

    /**
     * The line that will be copied in the migration file inside the "up" function.
     *
     */
    public function migrationUp()
    {
        $this->reverse()->migrationDown();
    }

    /**
     * The line that will be copied in the migration file inside the "down" function.
     *
     * @return string
     */
    public function migrationDown()
    {
        $this->reverse()->migrationUp();
    }


    /**
     * Returns the reversed action
     *
     * @return CreateAction
     */
    protected function reverse()
    {
        if (!$this->reverseAction)
            $this->reverseAction = new CreateAction($this->attribute);

        return $this->reverseAction;
    }
}