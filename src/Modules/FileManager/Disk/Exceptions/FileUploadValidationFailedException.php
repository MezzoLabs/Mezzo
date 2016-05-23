<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk\Exceptions;


use Illuminate\Validation\Validator;

class FileUploadValidationFailedException extends FileUploadException
{
    public function __construct(Validator $validator)
    {
        $this->validationMessages($validator);
    }
}