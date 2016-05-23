<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk\Publishers;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Http\Responses\ModuleResponseFactory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class AbstractFilePublisher
{
    public static $imageSizes = [
        'thumb' => [75, 75],
        'small' => [300, 300],
        'small_square' => [250, 300],
        'small_wide' => [300, 200],
        'medium' => [750, 750],
        'medium_square' => [750, 750],
        'large' => [1920, 1080],
        'original' => [0,0]
    ];

    /**
     * @var \App\File
     */
    protected $file;
    /**
     * @var Collection
     */
    protected $options;

    public function __construct(\App\File $file, array $options)
    {
        $this->file = $file;
        $this->options = new Collection($options);
    }

    /**
     * @return Collection
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * @return \App\File
     */
    public function file()
    {
        return $this->file;
    }

    public function forceDownload()
    {
        return $this->options()->get('forceDownload');
    }

    public function imageSizeKey()
    {
        return $this->options()->get('imageSize', 'medium');
    }

    public function response()
    {
        return app(ModuleResponseFactory::class);
    }

    /**
     * @return mixed
     */
    protected function imageSizes()
    {
        $sizeKey = $this->imageSizeKey();

        if (!isset(static::$imageSizes[$sizeKey]))
            throw new BadRequestHttpException('Image size is not supported.');

        return static::$imageSizes[$sizeKey];
    }
}