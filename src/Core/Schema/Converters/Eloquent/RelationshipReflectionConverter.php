<?php


namespace MezzoLabs\Mezzo\Core\Schema\Converters\Eloquent;


use MezzoLabs\Mezzo\Core\Reflection\Reflections\EloquentRelationshipReflection;
use MezzoLabs\Mezzo\Core\Schema\Converters\Converter;
use MezzoLabs\Mezzo\Core\Schema\Relations\ManyToMany;
use MezzoLabs\Mezzo\Core\Schema\Relations\OneToMany;
use MezzoLabs\Mezzo\Core\Schema\Relations\OneToOne;
use MezzoLabs\Mezzo\Core\Schema\Relations\Relation;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;

class
RelationshipReflectionConverter extends Converter
{

    /**
     * Convert RelationshipReflection to Relation
     *
     * Examples:
     * One To One
     * $women->belongsTo('Man')  --> womens.man_id + man.id
     * $man->hasOne('Woman')     --> man.id + womens.man_id
     *
     * One To Many
     * $event->belongsTo('Course')  --> events.course_id + courses.id
     * $course->hasMany('Event')    --> courses.id + events.course_id
     *
     * Many To Many
     * $user->belongsToMany('Role') --> user_roles.role_id + user_roles.user_id
     * $role->belongsToMany('User') --> user_roles.role_id + user_roles.user_id
     *
     *
     * @param EloquentRelationshipReflection $reflection
     * @throws \ReflectionException
     * @return Relation
     */
    protected function fromReflectionToRelation(EloquentRelationshipReflection $reflection)
    {
        switch ($reflection->type()) {
            case 'BelongsTo':
                return $this->fromBelongsTo($reflection);
            case 'BelongsToMany':
                return $this->makeManyToMany($reflection);
            case 'HasOne':
                return $this->makeOneToOneOrMany(OneToOne::class, $reflection);
            case 'HasMany':
                return $this->makeOneToOneOrMany(OneToMany::class, $reflection);
            default:
                throw new \ReflectionException('Relation is not supported ' . $reflection->qualifiedName());
        }
    }

    /**
     * Create a OneToOne or a OneToMany relation from a relation reflection.
     *
     * belongsTo + hasOne = OneToOne
     * belongsTo + hasMany = OneToMany
     *
     * @param EloquentRelationshipReflection $reflection
     * @return OneToMany|OneToOne
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    protected function fromBelongsTo(EloquentRelationshipReflection $reflection)
    {
        if (!$reflection->is('BelongsTo'))
            throw new InvalidArgumentException($reflection);

        $counterpart = $this->findOrFailCounterpart($reflection);

        if ($counterpart->is('HasOne'))
            return $this->makeOneToOneOrMany(OneToOne::class, $reflection);
        else
            return $this->makeOneToOneOrMany(OneToMany::class, $reflection);
    }

    /**
     * Find the counterpart of a relationship or throw an exception
     *
     * @param EloquentRelationshipReflection $reflection
     * @return EloquentRelationshipReflection
     * @throws \ReflectionException
     */
    protected function findOrFailCounterpart(EloquentRelationshipReflection $reflection)
    {
        $counterpart = $reflection->counterpart();

        if (!$counterpart)
            throw new \ReflectionException('Cannot find a counterpart to ' . $reflection->qualifiedName() . '. ' .
                'Please set up the inverse relation in ' . get_class($reflection->instance()->getRelated()));

        return $counterpart;
    }

    /**
     * Create a OneToOne or a OneToMany relationship
     *
     * @param $class
     * @param EloquentRelationshipReflection $reflection
     * @internal param $joinColumn
     * @return mixed
     */
    protected function makeOneToOneOrMany($class, EloquentRelationshipReflection $reflection)
    {
        return Relation::makeByType($class)
            ->from($reflection->tableName(), $reflection->name())
            ->to($reflection->relatedTableName(), $reflection->counterpartName())
            ->connectVia($reflection->joinColumn(), $reflection->connectingTable());
    }

    /**
     * @param EloquentRelationshipReflection $reflection
     * @return $this
     */
    protected function makeManyToMany(EloquentRelationshipReflection $reflection)
    {
        return ManyToMany::make()
            ->from($reflection->tableName(), $reflection->name())
            ->to($reflection->relatedTableName(), $reflection->counterpartName())
            ->setPivot(
                $reflection->pivotTable(),
                $reflection->instance()->getForeignKey(),
                $reflection->instance()->getOtherKey()
            );
    }


    public function run($toConvert)
    {
        return $this->fromReflectionToRelation($toConvert);
    }
}