<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Domain\Models;


use App\Mezzo\Generated\ModelParents\MezzoContent;

class Content extends MezzoContent
{
    public function text()
    {
        $textArray = [];
        $this->blocks->each(function (\App\ContentBlock $block) use (&$textArray) {
            $text = $block->text();

            if (empty($text))
                return true;

            $textArray[] = $block->text();
        });

        return implode("\r\n", $textArray);
    }

    /**
     * @param $handle
     * @return ContentBlock|null
     */
    public function findBlock($handle)
    {
        return $this->blocks->keyBy('name')->get($handle);
    }
}