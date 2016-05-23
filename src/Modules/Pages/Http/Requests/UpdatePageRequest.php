<?php


use Illuminate\Support\Arr;
use MezzoLabs\Mezzo\Http\Requests\Resource\StoreResourceRequest;
use MezzoLabs\Mezzo\Modules\Contents\Http\Requests\IsRequestWithContentBlocks;

class UpdatePageRequest extends \MezzoLabs\Mezzo\Http\Requests\Resource\UpdateResourceRequest
{
    use IsRequestWithContentBlocks;

    public $model = \App\Page::class;



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
        return Arr::dot(parent::rules());
    }
}