<?php


namespace MezzoLabs\Mezzo\Core\Reflection\Reflections;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Core\Database\DatabaseColumn;
use MezzoLabs\Mezzo\Core\Schema\Converters\Eloquent\RelationshipReflectionConverter;
use MezzoLabs\Mezzo\Core\Schema\Relations\ManyToOne;
use MezzoLabs\Mezzo\Core\Schema\Relations\Relation as MezzoRelation;
use MezzoLabs\Mezzo\Exceptions\MezzoException;

class EloquentRelationshipReflection
{
    /**
     * Array of function names that are allowed relationships.
     *
     * @var array
     */
    public static $allowed = [
        'belongsTo', 'belongsToMany', 'hasMany', 'hasOne', 'hasOneOrMany'
    ];
    /**
     * @var EloquentModelReflection
     */
    protected $modelReflection;

    /**
     * @var string
     */
    protected $functionName;

    /**
     * @var BelongsTo|BelongsToMany|HasOneOrMany
     */
    protected $instance;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var EloquentRelationshipReflection
     */
    protected $counterpart;

    /**
     * @var Relation
     */
    protected $relationSchema;

    /**
     * @var RelationshipReflectionConverter
     */
    protected $schemaConverter;

    /**
     * @param EloquentModelReflection $modelReflection
     * @param $functionName
     */
    public function __construct(EloquentModelReflection $modelReflection, $functionName)
    {
        $this->modelReflection = $modelReflection;
        $this->functionName = $functionName;

        $this->instance = $this->makeInstance();
        $this->type = $this->instanceReflection()->getShortName();

        $this->schemaConverter = RelationshipReflectionConverter::make();

    }

    /**
     * @return Relation
     */
    private function makeInstance()
    {
        $modelInstance = $this->modelReflection->instance();
        $function = $this->functionName;
        return $modelInstance->$function();
    }

    /**
     * @return \ReflectionClass
     */
    public function instanceReflection()
    {
        return Singleton::reflection($this->instance);
    }

    /**
     * Check if a relation is allowed.
     *
     * @param $string
     * @return bool
     */
    public static function isAllowed($string)
    {
        return in_array(camel_case($string), static::$allowed);
    }

    /**
     * Get the qualified column of the related table.
     *
     * @return string
     * @throws MezzoException
     */
    public function qualifiedRelatedColumn()
    {
        return $this->relatedTableName() . '.' . $this->relatedColumn();
    }

    /**
     * Get the name of the foreign table
     *
     * @return string
     */
    public function relatedTableName()
    {
        return $this->instance()->getRelated()->getTable();
    }

    /**
     * @return BelongsTo|BelongsToMany|HasOneOrMany|Relation
     */
    public function instance()
    {
        return $this->instance;
    }

    /**
     * Get the foreign column name without the name of the table.
     *
     * @throws MezzoException
     * @return string
     */
    public function relatedColumn()
    {
        switch ($this->type()) {
            case 'BelongsTo':
                $column = $this->instance()->getOtherKey();
                break;
            case 'BelongsToMany':
                $column = $this->instance()->getRelated()->getKeyName();
                break;
            case 'HasOne':
                $column = $this->instance()->getForeignKey();
                break;
            case 'HasMany':
                $column = $this->instance()->getForeignKey();
                break;
            default:
                throw new MezzoException('Relationship ' . $this->qualifiedName() . ' is not supported. ');
        }

        return $this->disqualifyColumn($column);
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * Get a qualified name for this relationship.
     *
     * @return string
     */
    public function qualifiedName()
    {
        return $this->modelReflection->className() . '.' . $this->functionName;
    }

    /**
     * Remove the table name from a column.
     *
     * @param string $columnName
     * @return string
     */
    private function disqualifyColumn($columnName)
    {
        return DatabaseColumn::disqualifyName($columnName);
    }

    /**
     * Returns the column that is needed for connecting two tables (Not for many to many relationships).
     *
     * @return string
     * @throws MezzoException
     */
    public function joinColumn()
    {
        if ($this->isBelongsTo())
            return $this->localColumn();

        return $this->relatedColumn();
    }

    /**
     * Checks if this relation has the foreign key as a column in the connected database table.
     */
    public function isBelongsTo()
    {
        return $this->type == 'BelongsTo' || $this->type == 'BelongsToMany';
    }

    /**
     * Get the unqualified name of the local column.
     *
     * @throws \Exception
     * @return string
     */
    public function localColumn()
    {
        switch ($this->type()) {
            case 'BelongsTo':
                $column = $this->instance()->getForeignKey();
                break;
            case 'BelongsToMany':
                $column = $this->instance()->getParent()->getKeyName();
                break;
            case 'HasOne':
                $column = $this->instance()->getQualifiedParentKeyName();
                break;
            case 'HasMany':
                $column = $this->instance()->getQualifiedParentKeyName();
                break;
            default:
                throw new MezzoException('Relationship ' . $this->qualifiedName() . ' is not supported. ');
        }

        return $this->disqualifyColumn($column);
    }

    /**
     * Returns the table which contains the extra column.
     *
     * @return string
     */
    public function connectingTable()
    {
        if ($this->isBelongsTo())
            return $this->tableName();

        return $this->relatedTableName();
    }

    /**
     * Get the name of the model table
     *
     * @return string
     */
    public function tableName()
    {
        return $this->modelReflection->databaseTable()->name();
    }

    /**
     * Get the qualified local column.
     *
     * @return string
     * @throws MezzoException
     */
    public function qualifiedLocalColumn()
    {
        return $this->tableName() . '.' . $this->localColumn();
    }

    /**
     * Check if a relationship reflection is the inverse part of this.
     *
     * @param EloquentRelationshipReflection $check
     * @return bool
     * @throws MezzoException
     */
    public function isCounterpart(EloquentRelationshipReflection $check)
    {
        $correctTables = $check->tableName() == $this->relatedTableName();

        $correctColumns = $check->localColumn() == $this->relatedColumn() &&
            $check->relatedColumn() == $this->localColumn();

        if ($this->is('BelongsToMany'))
            return $correctTables && $correctColumns && $check->pivotTable() == $this->pivotTable();

        if (!$correctTables) return false;

        return $correctTables && $correctColumns;
    }

    /**
     * Checks if the relation is a 'hasMany', 'hasOne'...
     *
     * @param string $type
     * @return bool
     */
    public function is($type)
    {
        return strtolower($type) == strtolower($this->type());
    }

    /**
     * Returns the pivot table if the relation is many to many (belongsToMany)
     *
     * @return string
     */
    public function pivotTable()
    {
        if (!$this->is('BelongsToMany'))
            return "";

        return $this->instance()->getTable();
    }

    /**
     * Returns the name of the counterpart relationship reflection.
     * If no counterpart is set up in the related model class it will return an empty string.
     *
     * @return string
     */
    public function counterpartName()
    {
        if (!$this->counterpart()) return "";

        return $this->counterpart()->name();
    }

    /**
     * Get the counterpart of this relationship reflection
     *
     * @return EloquentRelationshipReflection
     */
    public function counterpart()
    {
        if (!$this->counterpart) {
            $this->counterpart = $this->findCounterpart();
        }

        return $this->counterpart;
    }

    public function findCounterpart()
    {
        $counterpartModel = $this->relatedModelReflection();

        if ($this->isSelfReferencing())
            return $this;

        return $counterpartModel->relationshipReflections()->findCounterpartTo($this);
    }

    /**
     * Get the reflection of the related model.
     *
     * @return EloquentModelReflection
     */
    public function relatedModelReflection()
    {
        return mezzo()->model($this->instance()->getRelated(), 'eloquent');

    }

    /**
     * Get the unqualified name of this relationship (the name of the function)
     *
     * @return string
     */
    public function name()
    {
        return $this->functionName;
    }

    /**
     * Get the abstract schema of this relation reflection.
     *
     * @return MezzoRelation
     */
    public function relationSchema()
    {
        if (!$this->relationSchema) {
            $this->relationSchema = $this->makeRelationSchema();
        }

        return $this->relationSchema;
    }

    /**
     * @return MezzoRelation
     */
    protected function makeRelationSchema()
    {
        $schema = $this->schemaConverter->run($this);

        return $schema;
    }

    public function isSelfReferencing()
    {
        $counterpartModel = $this->relatedModelReflection();

        return $counterpartModel->className() == $this->modelReflection->className();
    }

}