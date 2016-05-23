<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk;


use MezzoLabs\Mezzo\Modules\FileManager\Disk\Publishers\AwsS3Publisher;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Publishers\FilePublisherContract;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Publishers\LocalFilePublisher;

class FilePublisher
{
    public static $publishers = [
        's3' => AwsS3Publisher::class,
        'local' => LocalFilePublisher::class
    ];


    public function publish(\App\File $file, $options = [])
    {
        $options['forceDownload'] = $options['forceDownload'] ?? false;

        $publisher = $this->makePublisher($file, $options);
        return $publisher->publish();
    }

    /**
     * @param $publisher
     * @return FilePublisherContract
     */
    public function makePublisher($file, $options) : FilePublisherContract
    {
        $class = static::$publishers[$file->disk];

        return app()->make($class, ['file' => $file, 'options' => $options]);
    }
}