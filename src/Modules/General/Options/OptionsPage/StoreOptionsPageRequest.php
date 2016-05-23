<?php


namespace MezzoLabs\Mezzo\Modules\General\Options\OptionsPage;


use App\Option;
use MezzoLabs\Mezzo\Core\Helpers\StringHelper;
use MezzoLabs\Mezzo\Http\Requests\Resource\ResourceRequest;
use MezzoLabs\Mezzo\Modules\General\GeneralModule;

class StoreOptionsPageRequest extends ResourceRequest
{
    public $model = Option::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $optionsRegistry = app()->make(GeneralModule::class)->optionRegistry();

        foreach ($this->get('options', []) as $name => $value){
            $rulesString = $optionsRegistry->getOrDefault($name)->rules();

            $rules['options.' . StringHelper::fromArrayToDotNotation($name)] = $rulesString;
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->permissionGuard()->allowsEdit(new Option());
    }


}