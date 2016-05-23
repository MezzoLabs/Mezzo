<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Http\Controllers;


use MezzoLabs\Mezzo\Http\Controllers\CockpitController;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\FilePublisher;
use MezzoLabs\Mezzo\Modules\FileManager\Domain\Repositories\FileRepository;
use MezzoLabs\Mezzo\Modules\FileManager\Exceptions\PathPatternInvalidException;
use MezzoLabs\Mezzo\Modules\FileManager\Http\Requests\PublishFileRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublishFilesController extends CockpitController
{
    protected $pathRegex = '|((?:[a-zA-Z0-9 \_\-]+\/)*)([a-zA-ZäöüßÄÖÜ0-9 \_\-]+)\.([a-z0-9]+)|';

    /**
     * @var PublishFileRequest
     */
    protected $request;

    protected $noAuth = true;



    public function publish(PublishFileRequest $request, $path)
    {
        $path = ltrim($path, '/');

        $parts = $this->matchPath($path);

        $this->request = $request;

        $options = [
            'forceDownload' => ($request->get('download', 0) == 1),
            'imageSize' => $request->get('size', 'medium')
        ];

        $publish = $this->publishFileInFolder($parts['filename'], $parts['folder'], $options);

        return $publish;
    }

    protected function matchPath($path)
    {
        $matches = array();
        preg_match($this->pathRegex, $path, $matches);

        if (count($matches) != 4 || !($matches[0] == $path))
            throw new PathPatternInvalidException();

        return [
            'folder' => $matches[1],
            'filename' => $matches[2] . '.' . $matches[3],
            'extension' => $matches[3]
        ];


    }

    /**
     * @param string $filename
     * @param string $folder
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    protected function publishFileInFolder($filename, $folder, array $options = [])
    {
        $repo = $this->filesRepository();

        $file = $repo->findByFilenameAndFolder($filename, $folder);

        if (!$file)
            throw new NotFoundHttpException();

        $publisher = app()->make(FilePublisher::class);
        return $publisher->publish($file, $options);


    }


    /**
     * @return FileRepository
     * @throws \MezzoLabs\Mezzo\Exceptions\RepositoryException
     */
    protected function filesRepository()
    {
        return FileRepository::makeRepository();
    }



}