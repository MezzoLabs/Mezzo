<?php

namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany as EloquentBelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany as EloquentHasOneOrMany;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Core\Schema\Attributes\AttributeValues;

/**
 * Interface MezzoModel
 * @package MezzoLabs\Mezzo\Core\Modularisation\Domain\Models
 *
 * @property integer $id
 * @property boolean $timestamps
 */
interface MezzoModel extends EloquentInterface
{

    public function getRules();
    /**
     * @return \MezzoLabs\Mezzo\Core\Schema\Attributes\Attributes
     */
    public function attributeSchemas();

    /**
     * @return \MezzoLabs\Mezzo\Core\Schema\ModelSchema
     */
    public function schema();

    /**
     * @return \MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection
     * @throws \MezzoLabs\Mezzo\Exceptions\ReflectionException
     */
    public function reflection();

    /**
     * @return AttributeValues
     */
    public function attributeValues();

    /**
     * @param string $relationName
     * @return EloquentBelongsToMany|EloquentHasOneOrMany|EloquentRelation
     */
    public function relation($relationName);

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable();

    /**
     * Get all of the current attributes on the model.
     *
     * @return array
     */
    public function getAttributes();

    public function validateOrFail($data = [], $mode = "create");


    /**
     * Check if the user owns this model.
     *
     * @return boolean
     */
    public function isOwnedByUser(\App\User $user);

    /**
     * Get the label model or a computed value that represents this model in a list or a select input.
     *
     * @return string
     */
    public function getLabelAttribute();


    /**
     * Get the additional pivot values without the ids.
     *
     * @return array
     */
    public function getPivotValues();

    /**
     * @return ModelRepository
     */
    public static function repository();
}