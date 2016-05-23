<?php

namespace MezzoLabs\Mezzo\Http\Controllers;

use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Core\Modularisation\NamingConvention;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Exceptions\ModuleControllerException;
use MezzoLabs\Mezzo\Exceptions\RepositoryException;

trait HasModelResource
{
    /**
     * @var MezzoModelReflection
     */
    protected $modelReflection;
    /**
     * @var ModelRepository
     */
    protected $repository;

    /**
     * @param $model
     * @internal param $modelReflection
     */
    public function setModelReflection($model)
    {
        $modelReflection = mezzo()->model($model);
        $this->modelReflection = $modelReflection;
    }

    /**
     * @param string $repositoryClassName
     * @return static
     * @throws RepositoryException
     */
    protected function makeRepository($repositoryClassName = "")
    {
        if (empty($repositoryClassName)) {
            $repositoryClassName = $this->guessRepositoryClass();
        }
        if ($repositoryClassName && class_exists($repositoryClassName)) {
            return new $repositoryClassName;
        }
        if (!$this->allowStaticRepositories())
            throw new RepositoryException('Cannot find a repository implementation for ' .
                $this->model()->className() . '. You should create one in the App or the module folder.');
        return ModelRepository::makeRepository($this->model());
    }

    /**
     * Try to find a repository implementation for the resource.
     *
     * @return bool|string
     */
    private function guessRepositoryClass()
    {
        return NamingConvention::repositoryClass($this->model()->name(), [
            'App',
            $this->module()->getNamespaceName()
        ]);
    }

    /**
     * Assert that this resource controller has a valid model reflection.
     *
     * @return bool
     * @throws ModuleControllerException
     */
    protected function assertResourceIsReflectedModel()
    {
        if (!$this->model())
            throw new ModuleControllerException($this->qualifiedName() . " isn't a valid resource controller. " .
                "Tried to find a Mezzo model with the name " . $this->guessModelName() . '. ' .
                'You should try <ModelName>Controller.');
        return true;
    }

    /**
     * @return bool|MezzoModelReflection|null
     */
    public function model()
    {
        if ($this->modelReflection === null) {
            $modelName = (!empty($this->model)) ? $this->model : $this->guessModelName();
            if (!mezzo()->knowsModel($modelName))
                $this->modelReflection = false;
            else
                $this->setModelReflection($modelName);
        }
        return $this->modelReflection;
    }

    /**
     * @return ModelRepository|void
     */
    public function repository()
    {
        if (!$this->repository)
            $this->repository = $this->makeRepository();
        return $this->repository;
    }

    /**
     * @return bool
     */
    private function allowStaticRepositories()
    {
        if (property_exists($this, 'allowStaticRepositores'))
            return $this->allowStaticRepositories;
        return false;
    }

    /**
     * @return string
     */
    private function guessModelName()
    {
        return NamingConvention::modelName($this);
    }

    /**
     * @return ModuleProvider
     */
    abstract public function module();

    /**
     * @return string
     */
    abstract public function qualifiedName();
}