<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Domain\Models;


use App\ImageFile;
use App\Mezzo\Generated\ModelParents\MezzoContentBlock;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Modules\Contents\Contracts\ContentBlockTypeContract;
use MezzoLabs\Mezzo\Modules\Contents\Contracts\ContentFieldTypeContract;
use MezzoLabs\Mezzo\Modules\Contents\Exceptions\ContentBlockException;
use MezzoLabs\Mezzo\Modules\FileManager\Domain\Repositories\ImageFileRepository;

abstract class ContentBlock extends MezzoContentBlock
{
    /**
     * @var ContentBlockTypeContract
     */
    protected $blockType;

    /**
     * @param ContentBlockTypeContract $blockType
     */
    public function setType(ContentBlockTypeContract $blockType)
    {
        $this->blockType = $blockType;
    }

    /**
     * Creates the content block instance via the class that is set in the database.
     */
    private function setTypeByClass()
    {
        $blockTypeClass = $this->getAttribute('class');
        $this->setType(app()->make($blockTypeClass));
    }

    /**
     * @return ContentBlockTypeContract
     */
    public function getType()
    {
        if (!$this->blockType) {
            $this->setTypeByClass();
        }

        return $this->blockType;
    }

    public function hasField($name)
    {
        return $this->fields->keyBy('name')->has($name);
    }

    public function getField($name)
    {
        foreach ($this->fields as $field) {
            if ($name == $field->name) return $field;
        }

        throw new ContentBlockException('Field with the name "' . $name . '" not found.');
    }

    public function getFieldValue($name)
    {
        return $this->getField($name)->value;
    }

    public function getWidgetOption($key)
    {
        $options = $this->buildWidgetOptions();

        if (!array_has($options, $key)) {
            return null;
        }

        return $options[$key];
    }

    protected function buildWidgetOptions()
    {
        $lines = explode("\r\n", $this->getOption('options'));

        $options = [];
        foreach ($lines as $line) {
            $parts = explode('=', $line);

            if (count($parts) != 2) continue;

            $options[strtolower(trim($parts[0]))] = trim($parts[1]);
        }

        return $options;
    }

    public function getOption($name, $default = null)
    {
        return $this->options()->get($name, $default);
    }

    public function options()
    {
        return new Collection(json_decode($this->options));
    }

    /**
     * @return Collection
     * @throws ContentBlockException
     */
    public function getImages()
    {
        $imagesString = $this->getField('images')->value;

        $images = new Collection();

        foreach (explode(',', $imagesString) as $imageId) {
            if (!ImageFileRepository::instance()->exists($imageId))
                continue;

            $images->push(ImageFileRepository::instance()->find($imageId));
        }

        return $images;
    }

    /**
     * @return ImageFile|null
     * @throws ContentBlockException
     */
    public function getImage()
    {
        $imageId = $this->getField('image')->value;


        if (!ImageFileRepository::instance()->exists($imageId))
            return null;


        return ImageFileRepository::instance()->find($imageId);
    }

    public function getImageUrl($size = "medium")
    {
        $image = $this->getImage();

        if (!$image) {
            return "";
        }

        return $image->url($size);
    }

    /**
     * @return string
     */
    public function shortTypeKey()
    {
        return $this->getType()->shortKey();
    }

    /**
     * Get the rules for this type.
     *
     * @return array
     */
    public function fieldsRules()
    {
        return $this->getType()->fieldsRules();
    }

    public static function makeByType($contentBlockType)
    {
        $block = new static();

        if (is_string($contentBlockType) && class_exists($contentBlockType))
            $contentBlockType = app()->make($contentBlockType);

        $block->setType($contentBlockType);

        return $block;
    }

    /**
     * @param $name
     * @return ContentFieldTypeContract|null
     */
    public function typeOfField($name)
    {
        return $this->getType()->fields()->get($name, null);
    }


    public function text()
    {
        $textArray = [];
        $this->fields->each(function (\App\ContentField $field) use (&$textArray) {
            $text = $field->text();

            if (empty($text))
                return true;

            $textArray[] = $text;
        });

        return implode("\r\n", $textArray);
    }

}