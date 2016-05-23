<?php


namespace MezzoLabs\Mezzo\Modules\Generator\Schema;


use Illuminate\Support\Str;
use MezzoLabs\Mezzo\Core\Schema\ModelSchema;

class ModelParentSchema extends FileSchema
{
    /**
     * @var ModelSchema
     */
    private $modelSchema;

    /**
     * Create a new model parent schema based on a model schema
     *
     * @param ModelSchema $modelSchema
     */
    public function __construct(ModelSchema $modelSchema)
    {
        $this->modelSchema = $modelSchema;
    }

    /**
     * The content of the generated file.
     *
     * @return string
     */
    public function content()
    {
        return $this->fillTemplate(['parent' => $this]);
    }

    /**
     * @return ModelSchema
     */
    public function modelSchema()
    {
        return $this->modelSchema;
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Schema\Attributes\Attributes
     */
    public function attributes()
    {
        return $this->modelSchema->attributes();
    }

    /**
     * Returns the relations of the model.
     *
     * @return \MezzoLabs\Mezzo\Core\Schema\RelationSchemas
     */
    public function relations()
    {
        return $this->modelSchema()->relations();
    }

    /**
     * Returns the relation sides of the model.
     *
     * @return \Illuminate\Support\Collection
     */
    public function relationSides()
    {
        return $this->modelSchema()->relationSides();
    }

    /**
     * The name of the template inside view folder.
     *
     * @return string
     */
    protected function templateName()
    {
        return 'modelparent';
    }

    /**
     * The file name of the according file.
     *
     * @return string
     */
    protected function shortFileName()
    {
        return $this->name() . '.php';
    }

    public function name()
    {
        return 'Mezzo' . $this->modelSchema()->shortName();
    }

    public function table()
    {
        return $this->modelSchema()->tableName();
    }

    public function extendsClass()
    {
        if (!class_exists($this->modelSchema()->className()))
            return '\App\Mezzo\BaseModel';


        // Go down the parents and search for a class that is named "Mezzo..."
        // If we find one we will return the parent of this class.
        $hitNext = false;
        foreach (class_parents($this->modelSchema()->className()) as $classParent){
            if($hitNext)
                return '\\' . $classParent;

            if(Str::startsWith(class_basename($classParent), 'Mezzo')) $hitNext = true;
        }

        return '\App\Mezzo\BaseModel';

    }
}