<?php


namespace MezzoLabs\Mezzo\Core\Files\Types;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Exceptions\FileTypeException;

abstract class FileType
{
    public static $fileTypes = [
        AudioFileType::class,
        ImageFileType::class,
        VideoFileType::class,
        TextFileType::class
    ];


    /**
     * @var array
     */
    protected $extensions = ["txt"];
    /**
     * @var string
     */
    protected $name;
    /**
     * @var Collection
     */
    private $extensionCollection;

    public function __construct()
    {
        $this->extensionCollection = new Collection();

        foreach ($this->extensions as $extension) {
            $this->extensionCollection->push($this->normExtension($extension));
        }
    }

    /**
     * Norm the extension to lower case and remove any dots.
     *
     * @param $extension
     * @return mixed
     */
    private function normExtension($extension)
    {
        return str_replace('.', '', strtolower($extension));
    }

    /**
     * Find a FileType based on a extension.
     *
     * @param $extension
     * @return FileType|null
     */
    public static function find($extension)
    {
        foreach (static::$fileTypes as $fileTypeClass) {
            $fileType = static::makeByClass($fileTypeClass);

            if ($fileType->matchesExtension($extension))
                return $fileType;
        }


        return app()->make(UnknownFileType::class);
    }

    /**
     * @param $fileTypeClass
     * @return FileType
     * @throws FileTypeException
     */
    public static function makeByClass($fileTypeClass)
    {
        if (!$fileTypeClass || !is_string($fileTypeClass) || $fileTypeClass == UnknownFileType::class)
            throw new FileTypeException('Cannot find a file type.');

        $fileType = mezzo()->make($fileTypeClass);

        if (!$fileType instanceof FileType)
            throw new FileTypeException($fileTypeClass . ' is not a real file type.');

        return $fileType;

    }

    /**
     * Check if a extension fits this type of file.
     *
     * @param $extensionToMatch
     * @return bool
     */
    public function matchesExtension($extensionToMatch)
    {
        $extensionToMatch = $this->normExtension($extensionToMatch);

        return $this->extensionCollection->contains($extensionToMatch);
    }

    /**
     * @return static
     * @internal param $fileTypeClass
     */
    public static function make()
    {
        return mezzo()->make(static::class);
    }

    /**
     * @return string
     */
    public function name()
    {
        if (!$this->name) {
            $this->name = strtolower(str_replace('FileType', '', Singleton::reflection($this)->getShortName()));
        }

        return $this->name;
    }

    public function isImage()
    {
        return ($this instanceof ImageFileType);
    }


}