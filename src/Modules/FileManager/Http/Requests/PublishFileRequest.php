<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Http\Requests;


use MezzoLabs\Mezzo\Http\Requests\Request;

class PublishFileRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}