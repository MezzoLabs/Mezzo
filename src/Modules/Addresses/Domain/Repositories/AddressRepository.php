<?php


namespace MezzoLabs\Mezzo\Modules\Addresses\Domain\Repositories;


use Illuminate\Support\Facades\Auth;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\Cloneable\CanClone;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\Cloneable\CloneRepositoryContract;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Exceptions\RepositoryException;

class AddressRepository extends ModelRepository implements CloneRepositoryContract
{
    use CanClone;

    /**
     * @param $data
     * @param string $type
     * @param \App\User|null $user
     * @return \MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel|\App\Address
     * @throws RepositoryException
     */
    public function setUserAddress($data, $type = \App\Address::TYPE_DEFAULT, $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            throw new RepositoryException('Tried to update an address for a user, but there was no valid user given.');
        }

        return $this->setAddressForModel($data, $type, $user);
    }

    public function unsetAddressForModel($type = \App\Address::TYPE_DEFAULT, MezzoModel $model)
    {
        $existing = $this->getAddressForModel($model, $type);

        if (!$existing) {
            return false;
        }

        $existing->delete();

        return true;
    }

    /**
     * @param $data
     * @param string $type
     * @param MezzoModel $model
     * @return \App\Address|\Illuminate\Database\Eloquent\Model
     * @throws RepositoryException
     */
    public function setAddressForModel($data, $type = \App\Address::TYPE_DEFAULT, MezzoModel $model)
    {
        $existing = $this->getAddressForModel($model, $type);

        if (!$existing) {
            $new = $this->create($data);

            $foreignKey = snake_case($type) . '_id';

            $model->$foreignKey = $new->id;
            $model->save();

            return $new;
        }

        $this->update($data, $existing->id);

        return $existing;
    }

    public function duplicateBestFromModel(MezzoModel $model, string $type = \App\Address::TYPE_DEFAULT)
    {
        $defaultAddressKey = snake_case(\App\Address::TYPE_DEFAULT) . '_id';
        $typeAddressKey = snake_case($type) . '_id';

        if ($type == \App\Address::TYPE_DEFAULT && $model->$defaultAddressKey) {
            return $this->duplicate($model->$defaultAddressKey);
        }

        if ($type != \App\Address::TYPE_DEFAULT && $model->$typeAddressKey) {
            return $this->duplicate($model->$typeAddressKey);
        }

        if ($model->$defaultAddressKey) {
            return $this->duplicate($model->$defaultAddressKey);
        }

        return null;
    }


    /**
     * @param MezzoModel $model
     * @param string $type
     * @return \App\Address
     */
    public function getAddressForModel(MezzoModel $model, string $type = \App\Address::TYPE_DEFAULT)
    {
        return $model->$type;
    }
}