<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk\Publishers;

use Illuminate\Support\Facades\File;
use Intervention\Image\Image as InterventionImage;
use Intervention\Image\ImageManager;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Systems\DiskSystemContract;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Systems\LocalDisk;

class LocalFilePublisher extends AbstractFilePublisher implements FilePublisherContract
{


    /**
     * @var ImageManager
     */
    protected $intervention;

    public function __construct(\App\File $file, array $options)
    {
        parent::__construct($file, $options);

        $this->intervention = new ImageManager(array('driver' => 'gd'));
    }

    /**
     * The underlying file disk system.
     *
     * @return DiskSystemContract
     */
    public function system() : DiskSystemContract
    {
        return app(LocalDisk::class);
    }

    /**
     * @return bool
     */
    public function publish()
    {
        if ($this->file->fileType()->isImage() && !$this->forceDownload())
            return $this->publishImage($this->file);

        return $this->response()->download($this->file->sourcePath());
    }

    /**
     * @param \App\File $file
     * @return mixed
     */
    protected function publishImage(\App\File $file)
    {
        $imageSizes = $this->imageSizes();

        $cachePath = $file->cachePath(['mode' => $this->imageSizeKey()]);

        if (!file_exists($cachePath)) {
            $fit = in_array($this->imageSizeKey(), ["thumb", "small_square", "small_wide", "slider", "medium_square"]);
            $image = $this->resize($file->absolutePath(), $imageSizes[0], $imageSizes[1], $fit);

            $this->cacheImage($cachePath, $image);
            return $image->response();
        }

        /** @var \Illuminate\Http\Response $response */
        $response = $this->intervention->make($cachePath)->response();
        return $response;
    }

    protected function resize($path, $width, $height, $fit = false)
    {
        if ($width == 0 && $height == 0) {
            return $this->intervention->make($path);
        }

        if ($fit) {
            return $this->intervention
                ->make($path)
                ->fit($width, $height);
        }

        return $this->intervention
            ->make($path)
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
    }

    protected function cacheImage($path, InterventionImage $file)
    {
        $file->save($path);
    }

}