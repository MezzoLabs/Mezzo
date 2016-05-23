<?php

namespace Mezzolabs\Mezzo\Cockpit\Http\FormObjects;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;

interface FormObject
{
    /**
     * Return all the rules of atomic attributes and nested relations for a store request in a dot notation.
     *
     * @return array
     */
    public function rulesForStoring();

    /**
     * Return all the rules of atomic attributes and nested relations for a update request in a dot notation.
     *
     * @param array $dirty
     * @return array
     */
    public function rulesForUpdating(array $dirty);

    /**
     * The reflection of the eloquent model.
     *
     * @return MezzoModelReflection
     */
    public function model();

    /**
     * Returns the data that was sent by the form request.
     *
     * @return Collection
     */
    public function data();

    /**
     * Returns a collection with all the data of nested relations.
     *
     * @return NestedRelations
     */
    public function nestedRelations();

    /**
     * Returns a collection with the data of the received attributes that are not inside a nested relation.
     *
     * @return Collection
     */
    public function atomicAttributesData();

    /**
     * Set the id of the resource that is changed by this form.
     *
     * @param $id
     */
    public function setId($id);

    public function getId();
}