<?php

namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany as EloquentBelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany as EloquentHasOneOrMany;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use Illuminate\Support\Str;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Core\Modularisation\NamingConvention;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Core\Schema\Attributes\AttributeValues;
use MezzoLabs\Mezzo\Core\Validation\HasValidationRules;
use MezzoLabs\Mezzo\Exceptions\ReflectionException;
use MezzoLabs\Mezzo\Exceptions\RepositoryException;

/**
 * Class HasMezzoAnnotations
 * @package MezzoLabs\Mezzo\Core\Modularisation\Domain\Models
 *
 * @property array $rules
 * @property AttributeValues $attributeValues
 * @method array getOriginal
 */
trait HasMezzoAnnotations
{
    use HasValidationRules;

    /**
     * @var AttributeValues
     */
    protected $attributeValues;


    /**
     * @return \MezzoLabs\Mezzo\Core\Schema\Attributes\Attributes
     */
    public function attributeSchemas()
    {
        return $this->schema()->attributes();
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Schema\ModelSchema
     */
    public function schema()
    {
        return $this->reflection()->schema();
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection|MezzoModelReflection
     * @throws \MezzoLabs\Mezzo\Exceptions\ReflectionException
     */
    public function reflection()
    {
        return mezzo()->makeReflectionManager()->mezzoReflection(get_class($this));
    }

    /**
     * @return AttributeValues
     */
    public function attributeValues()
    {
        if (!$this->attributeValues)
            $this->attributeValues = AttributeValues::fromModel($this);

        return $this->attributeValues;
    }

    /**
     * @param $relationName
     * @return EloquentBelongsToMany|EloquentHasOneOrMany|EloquentRelation
     * @throws ReflectionException
     */
    public function relation($relationName)
    {
        $hasRelation = method_exists($this, $relationName);

        if (!$hasRelation)
            throw new ReflectionException('The Model ' . get_class($this) . " doesn't have a relation named " . $relationName);

        $relation = $this->$relationName();

        if (!$relation instanceof EloquentRelation)
            throw new ReflectionException($relationName . ' is not a valid Eloquent reflection.');

        return $relation;
    }

    /**
     * Try to find the user it that owns this model.
     *
     * @return null|int
     */
    public function tryToFindOwnerId()
    {
        $ownerColumns = ['user_id', 'owner_id', 'creator_id', 'created_by_id', 'created_by'];

        foreach ($ownerColumns as $ownerColumn) {
            if ($this->hasAttribute($ownerColumn)) return $this->getAttribute($ownerColumn);
        }

        return null;
    }

    /**
     * Check if the a column with a certain name is known to mezzo.
     *
     * @param $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return $this->schema()->attributes()->has($name);
    }

    /**
     * Check if a user owns this model.
     *
     * @return boolean
     */
    public function isOwnedByUser(\App\User $user)
    {
        $ownerId = $this->tryToFindOwnerId();

        if (!$ownerId)
            return false;

        return intval($ownerId) === intval($user->id);
    }

    /**
     * Get the label model or a computed value that represents this model in a list or a select input.
     *
     * @return string
     */
    public function getLabelAttribute()
    {
        if (isset($this->attributes['label']))
            return $this->attributes['label'];

        $labelAlternatives = ['title', 'name', 'firstname', 'lastname', 'key', 'slug'];

        foreach ($labelAlternatives as $labelAlternative) {
            if (isset($this->attributes[$labelAlternative]))
                return $this->id . ': ' . $this->getAttribute($labelAlternative);
        }

        return $this->id;
    }

    /**
     * @param bool $orFail
     * @return ModelRepository
     * @throws RepositoryException
     */
    public static function repository()
    {
        $repositoryClass = NamingConvention::repositoryClass(static::class, [
            'App',
            mezzo()->model(static::class)->module()->getNamespaceName()
        ]);

        if (!$repositoryClass) {
            throw new RepositoryException('Cannot find a repository for ' . static::class);
        }

        return app()->make($repositoryClass);
    }

    public static function hasRepository()
    {
        try {
            static::repository();

            return true;
        } catch (RepositoryException $e) {
            return false;
        }
    }

    /**
     * Data that will be added to the request if the field is empty
     *
     * @param array $requestData
     * @return array
     */
    public function defaultData(array $requestData) : array
    {
        return [];
    }

    public function getPivotValues()
    {
        $values = [];

        foreach ($this->getOriginal() as $key => $value) {
            if (!Str::startsWith($key, 'pivot_')) {
                continue;
            }

            if (Str::endsWith($key, '_id')) {
                continue;
            }

            $values[$key] = $value;
        }

        return $values;

    }


}