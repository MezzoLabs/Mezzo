<?php


namespace MezzoLabs\Mezzo\Core\Files;


use MezzoLabs\Mezzo\Modules\FileManager\Disk\Exceptions\FileManagerException;
use MezzoLabs\Mezzo\Modules\Generator\CannotGenerateFileException;

class File
{
    /**
     * @var string
     */
    protected $filename;
    /**
     * @var string
     */
    protected $content;

    /**
     * @param string $filename
     * @param string $content
     */
    public function __construct($filename, $content = "")
    {
        $this->filename = $filename;
        $this->content = $content;
    }

    public static function removeExtension($fileName)
    {
        $parts = explode('.', $fileName);

        if (count($parts) == 1)
            return $fileName;

        if (empty($parts[0]))
            throw new FileManagerException('Cannot handle file names that only contain a extension.');

        $parts = array_splice($parts, 0, count($parts) - 1);


        return implode('.', $parts);

    }

    public static function getExtension($fileName)
    {
        $parts = explode('.', $fileName);

        return $parts[count($parts) - 1];
    }

    /**
     * Save the file under the given filename.
     *
     * @throws \Exception
     * @return boolean
     */
    public function save()
    {

        $saved = StorageFactory::root()->put($this->filename(), $this->content());

        if (!$saved) throw new CannotGenerateFileException($this->filename . ' cannot be written.');

        return true;
    }

    /**
     * @return string
     */
    public function filename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function content()
    {
        return $this->content;
    }

    public function dump()
    {
        echo "<pre>";
        echo $this->filename() . "\r\n";
        var_export($this->content());
        echo "</pre>";
    }
} 