<?php

namespace MezzoLabs\Mezzo\Modules\Contents\Http\ApiControllers;


use Dingo\Api\Http\Request as DingoApiRequest;
use MezzoLabs\Mezzo\Http\Controllers\ApiController;
use MezzoLabs\Mezzo\Modules\Contents\Http\Transformers\ContentBlockTypeTransformer;
use MezzoLabs\Mezzo\Modules\Contents\Types\BlockTypes\ContentBlockTypeRegistrar;


class ContentBlockTypeApiController extends ApiController
{
    public function index()
    {
        return $this->response()->collection($this->typeRegistrar()->all(), new ContentBlockTypeTransformer());
    }

    public function show(DingoApiRequest $request, $hash)
    {
        $type = $this->typeRegistrar()->get($hash, null);

        if ($request->acceptsHtml()) {
            \Debugbar::disable();
            return $type->inputsView();
        }

        return $this->response()->item($type, new ContentBlockTypeTransformer());
    }

    /**
     * @return ContentBlockTypeRegistrar
     */
    protected function typeRegistrar()
    {
        return app()->make(ContentBlockTypeRegistrar::class);
    }
}