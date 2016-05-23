<?php


namespace packages\mezzolabs\mezzo\src\Modules\Posts\Http\Requests;


use MezzoLabs\Mezzo\Http\Requests\Resource\StoreResourceRequest;

class StoreCategoryRequest extends StoreResourceRequest
{
    public function rules()
    {
        $rules = parent::rules();

        if (!$this->has('category_group_id')) {
            $rules['parent_id'] = "required|" . $rules['parent_id'];
        }

        if (!$this->has('parent_id')) {
            $rules['category_group_id'] = "required|" . $rules['category_group_id'];
        }

        return $rules;
    }
}