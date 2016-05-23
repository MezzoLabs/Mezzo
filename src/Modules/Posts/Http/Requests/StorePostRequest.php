<?php


namespace packages\mezzolabs\mezzo\src\Modules\Posts\Http\Requests;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use MezzoLabs\Mezzo\Http\Requests\Resource\StoreResourceRequest;
use MezzoLabs\Mezzo\Modules\Contents\Http\Requests\IsRequestWithContentBlocks;

class StorePostRequest extends StoreResourceRequest
{
    public $model = \App\Post::class;

    use IsRequestWithContentBlocks;

    protected function makeFormObject()
    {
        if (!$this->has('user_id'))
            $this->offsetSet('user_id', Auth::id());

        if (!$this->has('published_at'))
            $this->offsetSet('published_at', Carbon::now()->toDateTimeString());

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