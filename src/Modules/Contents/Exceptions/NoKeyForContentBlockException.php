<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Exceptions;


use MezzoLabs\Mezzo\Modules\Contents\Contracts\ContentBlockTypeContract;

class NoKeyForContentBlockException extends ContentsModuleException
{
    public function __construct(ContentBlockTypeContract $contentBlock)
    {
        return "You have to define a valid key for " . get_class($contentBlock);
    }
}