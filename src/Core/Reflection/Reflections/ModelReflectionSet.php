<?php


namespace MezzoLabs\Mezzo\Core\Reflection\Reflections;


use Illuminate\Database\Eloquent\Model as EloquentModel;
use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Core\Reflection\ModelFinder;

class ModelReflectionSet
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var MezzoModelReflection
     */
    protected $mezzoModelReflection;

    /**
     * @var EloquentModelReflection
     */
    protected $eloquentModelReflection;

    /**
     * @var boolean
     */
    protected $isMezzoModel;

    /**
     * @var \ReflectionClass
     */
    protected $reflectionClass;

    /**
     * One example instance of the wrapped model.
     *
     * @var EloquentModel
     */
    protected $instance;

    /**
     * @param $className
     * @throws \ReflectionException
     */
    public function __construct($className)
    {
        $this->className = $className;

        $this->assertValidEloquentModel();

        $this->isMezzoModel = $this->checkIfMezzoModel();
    }

    /**
     * @return bool
     * @throws \ReflectionException
     */
    protected function assertValidEloquentModel()
    {
        if (!class_exists($this->className))
            throw new \ReflectionException('Class ' . $this->className . ' does not exist.');

        if (!$this->checkIfEloquentModel())
            throw new \ReflectionException('Class ' . $this->className . ' is not a valid eloquent model.');

        return true;
    }

    /**
     * @return bool
     */
    protected function checkIfEloquentModel()
    {
        return is_subclass_of($this->className, EloquentModel::class);
    }

    /**
     * @return bool
     */
    protected function checkIfMezzoModel()
    {
        return ModelFinder::classUsesMezzoTrait($this->className());
    }

    /**
     * @return string
     */
    public function className()
    {
        return $this->className;
    }

    /**
     * The table name for this model class.
     *
     * @return string
     */
    public function tableName()
    {
        return $this->instance()->getTable();
    }

    /**
     * Returns an instance of the reflected Eloquent model.
     *
     * @param bool $forceNew
     * @return EloquentModel
     */
    public function instance($forceNew = false)
    {
        if ($this->instance && $forceNew) {
            return mezzo()->make($this->className());
        }

        if (!$this->instance) {

            $this->instance = mezzo()->make($this->className());
        }

        return $this->instance;
    }

    /**
     * @return string
     */
    public function fileName()
    {
        return $this->reflectionClass()->getFileName();
    }

    /**
     * Get the ReflectionClass object of the underlying model
     *
     * @return \ReflectionClass
     */
    public function reflectionClass()
    {
        if (!$this->reflectionClass) {
            $this->reflectionClass = Singleton::reflection($this->className());
        }

        return $this->reflectionClass;
    }

    /**
     * @return string
     */
    public function shortName()
    {
        return $this->reflectionClass()->getShortName();
    }

    /**
     * Returns a MezzoModelReflection if this model uses the model
     *
     * @param bool $forceEloquentReflection
     * @return EloquentModelReflection|MezzoModelReflection
     */
    public function bestReflection($forceEloquentReflection = false)
    {
        if ($this->isMezzoModel() && !$forceEloquentReflection)
            return $this->mezzoReflection();

        return $this->eloquentReflection();

    }

    /**
     * @return boolean
     */
    public function isMezzoModel()
    {
        return $this->isMezzoModel;
    }

    /**
     * @return MezzoModelReflection
     */
    public function mezzoReflection()
    {
        if (!$this->isMezzoModel())
            return null;

        if ($this->mezzoModelReflection === null)
            $this->mezzoModelReflection = new MezzoModelReflection($this);

        return $this->mezzoModelReflection;
    }

    /**
     * @return EloquentModelReflection
     */
    public function eloquentReflection()
    {
        if ($this->eloquentModelReflection === null) {
            $this->eloquentModelReflection = new EloquentModelReflection($this);
        }
        return $this->eloquentModelReflection;
    }

}