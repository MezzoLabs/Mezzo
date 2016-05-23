<?php

namespace MezzoLabs\Mezzo\Modules\Addresses\Http\Requests;


trait HasNestedAddress
{
    public function validateAddress($addressName)
    {
        $address = new \App\Address();

        $validator = $address->validateCreate($this->get($addressName), false);


        mezzo_dd($validator->messages());

        if ($validator->fails())
            $this->failedValidation($validator);
    }

    public function addressRules($addressName)
    {
        if (!$this->has($addressName))
            return [];

        $address = new \App\Address();
        $addressRules = [];
        foreach ($address->getRules() as $column => $rules) {
            $addressRules[$addressName . '.' . $column] = $rules;
        }

        return $addressRules;
    }

}