<?php


namespace MezzoLabs\Mezzo\Modules\Addresses\Domain\Models;


use App\Mezzo\Generated\ModelParents\MezzoAddress;

abstract class Address extends MezzoAddress
{
    public function firstName()
    {
        $parts = explode(' ', $this->addressee);

        return implode(' ', array_splice($parts, 0, count($parts) - 1));
    }

    public function lastName()
    {
        $parts = explode(' ', $this->addressee);

        return implode(' ', array_splice($parts, count($parts) - 1, 1));
    }

    public function streetWithNumber()
    {
        if (empty($this->street_extra)) {
            return $this->street;
        }

        return $this->street . ' ' . $this->street_extra;
    }
}