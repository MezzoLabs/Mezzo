<?php

namespace MezzoLabs\Mezzo\Modules\FileManager\Http\ApiControllers;


use MezzoLabs\Mezzo\Core\Configuration\MezzoConfig;
use MezzoLabs\Mezzo\Http\Controllers\ApiResourceController;
use MezzoLabs\Mezzo\Http\Controllers\GenericApiResourceController;
use MezzoLabs\Mezzo\Http\Controllers\HasDefaultApiResourceFunctions;
use MezzoLabs\Mezzo\Http\Responses\ApiResponseFactory;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Exceptions\FileUploadException;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\FileUploadManager;
use MezzoLabs\Mezzo\Modules\FileManager\Http\Requests\UpdateFileRequest;
use MezzoLabs\Mezzo\Modules\FileManager\Http\Requests\UploadFileRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FileApiController extends ApiResourceController
{
    use HasDefaultApiResourceFunctions {
        update as defaultUpdate;
    }

    public function upload(UploadFileRequest $request)
    {
        try {
            $file = $this->uploader()->uploadInput($request, mezzo()->config('filemanager.active_disk'));

        } catch (FileUploadException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return $this->response()->item($file, $this->bestModelTransformer());
    }

    /**
     * @return FileUploadManager
     */
    protected function uploader()
    {
        return app()->make(FileUploadManager::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFileRequest $request
     * @param  int $id
     * @return ApiResponseFactory
     */
    public function update(UpdateFileRequest $request, $id)
    {
        return $this->defaultUpdate($request, $id);
    }
}