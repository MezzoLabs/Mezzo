<?php


namespace MezzoLabs\Mezzo\Core\Reflection;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Filesystem\ClassFinder;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Core\Traits\IsMezzoModel;

class ModelFinder
{
    /**
     * @var string class name of the eloquent mode base class
     */
    protected static $eloquentClass = EloquentModel::class;

    /**
     * @var string class name of the eloquent mode base class
     */
    protected static $mezzoModelTrait = IsMezzoModel::class;

    /**
     * A collection of strings that represent the class names of all models in the app folder.
     *
     * @var Collection
     */
    private $eloquentModelClasses;

    /**
     * A collection of strings that represent the class names of all mezzo models in the app folder.
     *
     * @var Collection
     */
    private $mezzoModelClasses;



    /**
     * Create a new Model Finder instance
     */
    public function __construct(){
    }

    /**
     * @return Collection
     */
    public function eloquentModelClasses()
    {
        if(!$this->eloquentModelClasses){
            $this->eloquentModelClasses = $this->findEloquentModels();
        }

        return $this->eloquentModelClasses;
    }

    /**
     * @return Collection
     */
    public function mezzoModelClasses()
    {
        if(!$this->mezzoModelClasses){
            $this->mezzoModelClasses = $this->findMezzoModels();
        }

        return $this->mezzoModelClasses;
    }

    /**
     * Find all the classes in the app folder.
     *
     * @return array
     */
    protected function classesInAppFolder()
    {
        $finder = app()->make(ClassFinder::class);
        return $finder->findClasses(app_path());
    }


    /**
     *  Finds all classes that use the mezzo model trait.
     *
     * @return Collection
     */
    protected function findMezzoModels()
    {
        $classes = $this->getClassesUsingTrait(static::$mezzoModelTrait, $this->eloquentModelClasses);
        return new Collection($classes);
    }

    /**
     *  Finds all classes that extend the eloquent model
     *
     * @return Collection
     */
    protected function findEloquentModels()
    {
        return new Collection($this->getChildrenOfClass(static::$eloquentClass));
    }

    /**
     * Returns a collection of class names that extend a given class.
     *
     * @param string $parentClass
     * @param $childClasses
     * @return array
     */
    protected function getChildrenOfClass($parentClass, $childClasses = null)
    {
        $children = [];

        //If there is no collection of classes, we will search through all classes
        if ($childClasses == null) $childClasses = $this->classesInAppFolder();

        foreach ($childClasses as $class) {
            if ($this->checkIfConcreteSubclass($class, $parentClass)){
                $children[] = $class;
            }
        }

        return $children;
    }

    protected function checkIfConcreteSubclass($class, $parentClass){

        if (!is_subclass_of($class, $parentClass))
            return false;

        $reflection = Singleton::reflection($class);

        if($reflection->isAbstract())
            return false;

        return true;

    }

    /**
     * @param $traitName
     * @param null $childClasses
     * @return array
     */
    protected function getClassesUsingTrait($traitName, $childClasses = null)
    {
        if ($childClasses == null) $childClasses = $this->classesInAppFolder();

        $usages = [];

        foreach ($childClasses as $class) {
            if (static::classUsesTrait($class, $traitName)) $usages[] = $class;
        }

        return $usages;
    }

    /**
     * Helper function that checks if a class uses a trait
     *
     * @param $class
     * @param string $trait
     * @param bool $recursively
     * @return bool
     */
    public static function classUsesTrait($class, $trait = "", $recursively = true)
    {
        //@TODO-SCHS: Check if this needs to be cached.

        $parent = get_parent_class($class);

        if($parent && $parent != Model::class){
            $parentUsesTrait = static::classUsesTrait($parent, $trait);

            if($parentUsesTrait) return true;
        }

        if (empty($trait)) $trait = static::$mezzoModelTrait;

        if ($recursively)
            $usedTraits = trait_uses_recursive($class);
        else
            $usedTraits = class_uses($class);


        return in_array($trait, $usedTraits);
    }

    /**
     * @param $class
     * @param bool $recursively
     * @return bool
     */
    public static function classUsesMezzoTrait($class, $recursively = true)
    {
        return static::classUsesTrait($class, static::$mezzoModelTrait, $recursively);
    }
}