<?php


namespace MezzoLabs\Mezzo\Modules\Generator\Migration;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Schema\Attributes\Attribute;
use MezzoLabs\Mezzo\Core\Schema\Attributes\RelationAttribute;

class MigrationLines
{
    /**
     * @var Collection
     */
    protected $lines;
    /**
     * @var Attribute
     */
    private $attribute;

    public function __construct(Attribute $attribute)
    {

        $this->attribute = $attribute;
    }

    /**
     *  Gets a Collection of MigrationLine's for an Attribute
     *
     * @param Attribute $attribute
     * @return mixed
     */
    public static function forAttribute(Attribute $attribute)
    {
        return (new static($attribute))->make();
    }

    /**
     * Gets a Collection of MigrationLine's for an Attribute
     *
     * @return Collection
     */
    public function make()
    {
        $columnType = $this->columnType();

        if ($this->attribute->name() === "id")
            return $this->setLines(MigrationLine::increments());

        if ($this->attribute->isForeignKey())
            return $this->foreignKey($this->attribute);

        return $this->setLines($columnType);
    }

    /**
     * @return string
     */
    protected function columnType()
    {
        return $this->attribute->type()->doctrineTypeInstance()->getName();
    }

    /**
     * @param $line
     * @return Collection
     */
    private function setLines($line)
    {
        $this->lines = $this->makeLines($line);

        return $this->lines;
    }

    /**
     * @param $var
     * @return Collection
     */
    private function makeLines($var)
    {
        if ($var instanceof Collection)
            return $var;

        if (is_array($var)) {
            return new Collection($var);
        }

        $lines = new Collection();
        $lines->push($var);
        return $lines;
    }

    /**
     * @param RelationAttribute $attribute
     * @return Collection
     */
    private function foreignKey(RelationAttribute $attribute)
    {
        $otherSide = $attribute->relationSide()->otherSide();

        $type = $attribute->type()->doctrineTypeName();
        $name = $attribute->name();
        $referencesColumn = $otherSide->primaryKey();
        $referencesTable = $otherSide->table();

        $foreignKey = MigrationLine::start()->addForeignKey($name, $referencesTable, $referencesColumn)->addNullable();

        return $this->setLines($foreignKey);
    }

    /**
     * @return Collection
     */
    public function getLines()
    {
        return $this->lines;
    }


} 