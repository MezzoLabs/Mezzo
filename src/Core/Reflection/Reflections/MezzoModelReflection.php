<?php


namespace MezzoLabs\Mezzo\Core\Reflection\Reflections;

use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Core\Permission\PermissionGuard;
use MezzoLabs\Mezzo\Core\Schema\Attributes\RelationAttribute;
use MezzoLabs\Mezzo\Exceptions\ModelIsAlreadyAssociated;

class MezzoModelReflection extends ModelReflection
{
    /**
     * @var ModuleProvider
     */
    private $module;

    /**
     * @param ModuleProvider $module
     * @throws ModelIsAlreadyAssociated
     */
    public function setModule(ModuleProvider $module)
    {
        if ($this->hasModule()) {
            throw new ModelIsAlreadyAssociated($this, $module);
        }

        $this->module = $module;

        $this->module->associateModel($this->modelReflectionSet());
    }

    /**
     * Check if there is a module that wants to use this model.
     *
     * @return bool
     */
    public function hasModule()
    {
        return $this->module != null;
    }

    /**
     * @return ModuleProvider
     */
    public function module()
    {
        return $this->module;
    }

    /**
     * @return string
     */
    public function tableName()
    {
        return $this->instance()->getTable();
    }

    /**
     * Class name of the reflected eloquent model.
     *
     * @param bool $forceNew Should we create a new instance or use the singleton approach
     * @return MezzoModel
     */
    public function instance($forceNew = false)
    {
        return parent::instance($forceNew);
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Annotations\Reader\ModelAnnotations
     */
    public function annotations()
    {
        return mezzo()->makeAnnotationReader()->model($this);
    }

    /**
     * @return static
     */
    public function defaultIncludes($form = "index", $merge = [])
    {

        $attributes = $this->attributes()->relationAttributes()->visibleInForm($form);

        $attributes = $attributes->keyBy(function (RelationAttribute $relationAttribute) {
            return $relationAttribute->relationSide()->naming();
        });

        return $attributes->keys()->merge($merge);
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository
     */
    public function repository()
    {
        return $this->instance()->repository();
    }

    public function canBeEditedBy(\App\User $user = null) : bool
    {
        return PermissionGuard::make()->allowsEdit($this->instance(), $user);
    }

    public function canBeCreatedBy(\App\User $user = null) : bool
    {
        return PermissionGuard::make()->allowsCreate($this->instance(), $user);
    }

    public function canBeDeletedBy(\App\User $user = null) : bool
    {
        return PermissionGuard::make()->allowsDelete($this->instance(), $user);
    }


}