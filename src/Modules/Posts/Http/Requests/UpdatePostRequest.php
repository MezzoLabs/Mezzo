<?php


namespace packages\mezzolabs\mezzo\src\Modules\Posts\Http\Requests;


use Illuminate\Support\Arr;
use MezzoLabs\Mezzo\Http\Requests\Resource\UpdateResourceRequest;
use MezzoLabs\Mezzo\Modules\Contents\Http\Requests\IsRequestWithContentBlocks;

class UpdatePostRequest extends UpdateResourceRequest
{
    public $model = \App\Post::class;

    use IsRequestWithContentBlocks;

    protected function makeFormObject()
    {
        return $this->makeContentBlocksFormObject();
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Arr::dot(parent::rules());

        return $rules;
    }
}