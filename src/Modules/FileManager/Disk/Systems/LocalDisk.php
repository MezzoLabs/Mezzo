<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk\Systems;


use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Filesystem\Filesystem as DefaultFileSystem;
use MezzoLabs\Mezzo\Core\Configuration\MezzoConfig;
use MezzoLabs\Mezzo\Core\Helpers\StringHelper;
use MezzoLabs\Mezzo\Exceptions\FileException;

class LocalDisk implements DiskSystemContract
{
    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;
    /**
     * @var MezzoConfig
     */
    private $config;

    /**
     * @param UrlGenerator $urlGenerator
     * @param MezzoConfig $config
     */
    public function __construct(UrlGenerator $urlGenerator, MezzoConfig $config)
    {
        $this->urlGenerator = $urlGenerator;
        $this->config = $config;
    }

    /**
     * Move a file from one path to another.
     *
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function move(string $from, string $to) : bool
    {
        $absoluteFrom = $this->absolutePath($from);
        $absoluteTo = $this->absolutePath($to);

        if ($absoluteFrom == $absoluteTo)
            return true;

        $parts = explode('/', $absoluteTo);
        $folderTo = implode('/', array_splice($parts, 0, count($parts) - 1));

        $old = umask(0);
        $this->fileSystem()->makeDirectory($folderTo, 0777, true, true);
        umask($old);

        return $this->fileSystem()->move($absoluteFrom, $absoluteTo);
    }

    /**
     * Remove a file from this path.
     *
     * @param string $path
     * @return bool
     * @throws FileException
     */
    public function delete(string $path): bool
    {
        if (!$this->fileSystem()->exists($this->absolutePath($path))) {
            throw new FileException('Cannot delete a file that does not exist: "' . $this->absolutePath($path) . '"');
        }

        $deleted = $this->fileSystem()->delete($this->absolutePath($path));
        return $deleted;
    }

    /**
     * Check if there is a file on the given path.
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path) : bool
    {
        return $this->fileSystem()->exists($this->absolutePath($path));
    }

    /**
     * Returns the absolute path of a file.
     * This is needed when you want a base folder that doesnt appear in the database representation.
     *
     * @param string $path
     * @return string
     */
    public function absolutePath(string $path) : string
    {
        return StringHelper::path([$this->folder(), $path]);
    }

    public function folder()
    {
        $folder = $this->config->get('filemanager.disks.local.folder');

        $this->makeDirectory($folder);

        return $folder;
    }

    public function makeDirectory($directory)
    {
        if (!$this->fileSystem()->isDirectory($directory)) {
            $old = umask(0);
            $this->fileSystem()->makeDirectory($directory, 0777, true);
            umask($old);
        }
    }

    /**
     * Returns the default
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function fileSystem()
    {
        return mezzo()->make(DefaultFileSystem::class);
    }

    public function sourcePath(string $path) : string
    {
        return $this->absolutePath($path);
    }

    /**
     * A unqique key for this disk.
     *
     * @return string
     */
    public function key() : string
    {
        return 'local';
    }

    /**
     * Returns the public http directory.
     *
     * @return string
     */
    public function baseUrl() : string
    {
        return $this->urlGenerator->to('mezzo/upload/');
    }
}