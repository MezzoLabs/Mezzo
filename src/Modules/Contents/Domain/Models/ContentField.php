<?php

namespace MezzoLabs\Mezzo\Modules\Contents\Domain\Models;

use App\Mezzo\Generated\ModelParents\MezzoContentField;
use MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes\TextField;

class ContentField extends MezzoContentField
{
    /**
     * @return \MezzoLabs\Mezzo\Modules\Contents\Contracts\ContentFieldTypeContract|null
     */
    public function getType()
    {
        return $this->getBlock()->typeOfField($this->name);
    }

    /**
     * @return mixed|string
     */
    public function text()
    {
        if (!$this->getType() instanceof TextField)
            return "";

        return $this->getAttribute('value');
    }

    /**
     * @return \App\ContentBlock
     */
    public function getBlock()
    {
        return $this->block;
    }
}