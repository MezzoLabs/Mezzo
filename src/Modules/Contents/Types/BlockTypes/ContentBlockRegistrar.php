<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Types\BlockTypes;


use MezzoLabs\Mezzo\Core\Helpers\Parameter;
use MezzoLabs\Mezzo\Modules\Contents\Contracts\ContentBlockTypeContract;

class ContentBlockTypeRegistrar
{
    /**
     * @var ContentBlockTypeCollection
     */
    protected $blocks;

    /**
     * @param ContentBlockTypeCollection $blocks
     */
    public function __construct(ContentBlockTypeCollection $blocks)
    {
        $this->blocks = $blocks;
    }

    /**
     * Register a content block in the registry.
     *
     * @param $contentBlocks
     */
    public static function register($contentBlocks)
    {
        if (!is_array($contentBlocks))
            $contentBlocks = [$contentBlocks];

        $registrar = static::make();

        foreach ($contentBlocks as $contentBlock) {
            $contentBlock = $registrar->makeContentBlock($contentBlock);
            $registrar->registerContentBlock($contentBlock);
        }

    }

    /**
     * @param $contentBlock
     * @return ContentBlockTypeContract
     * @throws \MezzoLabs\Mezzo\Exceptions\ClassNotFoundException
     * @throws \MezzoLabs\Mezzo\Exceptions\InvalidArgumentException
     * @throws \MezzoLabs\Mezzo\Exceptions\MezzoException
     */
    private function makeContentBlock($contentBlock)
    {
        return Parameter::toInstance($contentBlock, ContentBlockTypeContract::class);
    }

    /**
     * @param ContentBlockTypeContract $contentBlock
     */
    public function registerContentBlock(ContentBlockTypeContract $contentBlock)
    {
        $this->blocks->put($contentBlock->key(), $contentBlock);
        $this->blocks->addLookup('hashes', $contentBlock->hash(), $contentBlock);
    }

    /**
     * Checks if a content block is registered or not.
     *
     * @param $key
     * @return mixed
     */
    public function has($key)
    {
        return $this->blocks->has($key);
    }

    /**
     * Get all registered content blocks.
     *
     * @return ContentBlockTypeCollection
     */
    public function all()
    {
        return $this->blocks->collection();
    }

    /**
     * Get an item from the collection by key.
     *
     * @param  mixed $key
     * @param  mixed $default
     * @return ContentBlockTypeContract
     */
    public function get($key, $default = null)
    {
        $block = $this->blocks->get($key, $default);

        if ($block)
            return $block;

        return $this->blocks->lookup($key);
    }

    /**
     * Returns the singleton instance of the block registrar
     *
     * @return ContentBlockTypeRegistrar
     */
    public static function make()
    {
        return app()->make(static::class);
    }

}