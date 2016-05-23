<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk\Uploaders;


use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Publishers\AbstractFilePublisher;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AwsS3Uploader extends AbstractFileUploader
{

    /**
     * @var ImageManager
     */
    protected $intervention;

    public function __construct()
    {
        $this->intervention = new ImageManager(array('driver' => 'gd'));
    }

    /**
     * Returns the default
     *
     * @return Filesystem
     */
    public function fileSystem()
    {
        return Storage::disk('s3');
    }

    /**
     * Upload a file to the disk onto a given path.
     *
     * @param $path
     * @param UploadedFile $file
     * @return bool
     */
    public function upload($path, UploadedFile $file, \App\File $databaseFile) : bool
    {
        if ($databaseFile->fileType()->isImage()) {
            $this->uploadImageSizesCache($file, $databaseFile);
        }

        return $this->fileSystem()->put($path, file_get_contents($file));
    }

    protected function uploadImageSizesCache(UploadedFile $file, \App\File $databaseFile)
    {
        $sizes = AbstractFilePublisher::$imageSizes;

        foreach ($sizes as $key => $dimensions) {
            $resizedStream = $this->resize($file->getRealPath(), $dimensions[0], $dimensions[1])->stream()->__toString();

            $this->fileSystem()->put('__cache/' . $databaseFile->id . '/' . $key . '.' . $databaseFile->extension, $resizedStream);
        }
    }

    protected function resize($path, $width, $height, $fit = false)
    {
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

    public function key() : string
    {
        return 's3';
    }
}