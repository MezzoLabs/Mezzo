<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Http\Requests;


use App\File;
use MezzoLabs\Mezzo\Http\Requests\Resource\StoreResourceRequest;

class UploadFileRequest extends StoreResourceRequest
{
    use UpdatesOrUploadsFiles;

    public function processData()
    {
        parent::processData();

        $this->processFileNameAndFolder();
    }


    public $model = File::class;


}