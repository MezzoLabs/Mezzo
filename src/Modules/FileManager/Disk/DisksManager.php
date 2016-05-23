<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk;


use Illuminate\Contracts\Routing\UrlGenerator;
use MezzoLabs\Mezzo\Core\Helpers\StringHelper;
use MezzoLabs\Mezzo\Core\Traits\IsShared;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Systems\AwsS3Disk;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Systems\DiskSystemContract;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Systems\LocalDisk;

class DisksManager
{
    use IsShared;

    public static $systems = [
        's3' => AwsS3Disk::class,
        'local' => LocalDisk::class
    ];

    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;

    /**
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function formattedFileName($baseName, $extension)
    {
        $baseName = str_slug($baseName, '_');

        return $baseName . '.' . $extension;
    }

    /**
     * @param $fromPath
     * @param $fromPath
     * @param $toPath
     * @param string $disk
     * @return bool
     */
    public function moveFile($fromPath, $toPath, $disk = "local")
    {
        $system = $this->makeSystem($disk);
        return $system->move($fromPath, $toPath);
    }

    /**
     * @param $diskName
     * @param $shortPath
     * @return string
     */
    public function absolutePath($diskName, $shortPath)
    {
        $system = $this->makeSystem($diskName);
        return $system->absolutePath($shortPath);
    }

    public function sourcePath($diskName, $shortPath)
    {
        $system = $this->makeSystem($diskName);
        return $system->sourcePath($shortPath);
    }

    /**
     * @param $shortPath
     * @param string $diskName
     * @return bool
     */
    public function deleteFile($shortPath, $diskName = "local")
    {
        $system = $this->makeSystem($diskName);
        return $system->delete($shortPath);
    }


    public function exists($diskName, $shortPath)
    {
        $system = $this->makeSystem($diskName);
        return $system->exists($shortPath);
    }


    public function url($shortPath, $diskName = "local") : string
    {
        return StringHelper::path([$this->makeSystem($diskName)->baseUrl(), $shortPath]);
    }

    /**
     * @param string $system
     * @return DiskSystemContract
     */
    public static function makeSystem(string $system) : DiskSystemContract
    {
        if (isset(static::$systems[$system]))
            $system = static::$systems[$system];

        return app()->make($system);
    }

}