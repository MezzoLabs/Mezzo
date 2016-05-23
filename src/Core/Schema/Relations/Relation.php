<?php

namespace MezzoLabs\Mezzo\Core\Schema\Relations;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Schema\Columns\Columns;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;

/**
 * Example: Tutorial<->Categories
 *
 * fromTable: tutorials
 * fromNaming: categories
 * fromPrimaryKey: id
 * toTable: categories
 * toNaming: tutorials
 * toPrimaryKey: id
 *
 * Class Relation
 * @package MezzoLabs\Mezzo\Core\Schema\Relations
 */
abstract class Relation
{
    /**
     * @var string
     */
    protected $fromTable;

    /**
     * @var string
     */
    protected $toTable;

    /**
     * @var string
     */
    protected $fromPrimaryKey = 'id';

    /**
     * @var string
     */
    protected $toPrimaryKey = 'id';

    /**
     * @var string
     */
    protected $fromNaming;

    /**
     * @var string
     */
    protected $toNaming;

    /**
     * @var Collection
     */
    protected $joinColumns;

    /**
     * @var Collection
     */
    protected $columns;

    /**
     * @var array
     */
    protected $tables;

    /**
     * @var Scopes
     */
    protected $scopes;

    /**
     * Prevent the creation via the constructor. Use the factory method instead.
     */
    final public function __construct()
    {

    }

    public function from($fromTable, $relationNaming)
    {
        $this->setTable('from', $fromTable, $relationNaming);
        return $this;
    }

    public function to($toTable, $relationNaming)
    {
        $this->setTable('to', $toTable, $relationNaming);
        return $this;
    }


    public function isInitialized()
    {
        return $this->fromTable && $this->fromNaming && $this->toTable && $this->toNaming;
    }

    /**
     * Internal function to set a table attribute and the according name for one part of this relationship.
     *
     * @param $type
     * @param $tableName
     * @param string $relationNaming
     */
    protected function setTable($type, $tableName, $relationNaming)
    {
        $tableAttribute = $type . 'Table';
        $namingAttribute = $type . 'Naming';

        $this->$tableAttribute = $tableName;
        $this->$namingAttribute = $relationNaming;
    }

    /**
     * @return string
     */
    abstract public function qualifiedName();

    /**
     * @return Columns
     */
    abstract protected function makeColumnsCollection();

    /**
     * @return array
     */
    abstract protected function makeTablesArray();

    /**
     * @return Collection
     */
    public function joinColumns()
    {
        if (!$this->joinColumns)
            $this->joinColumns = $this->columns()->joinColumns();

        return $this->joinColumns;
    }

    /**
     * @return array
     */
    public function tables()
    {
        if (!$this->tables)
            $this->tables = $this->makeTablesArray();

        return $this->tables;
    }

    /**
     * @param $tableName
     * @return bool
     */
    public function connectsTable($tableName)
    {
        return in_array($tableName, $this->tables());
    }

    /**
     * @return Columns
     */
    public function columns()
    {
        if (!$this->columns) $this->columns = $this->makeColumnsCollection();
        return $this->columns;
    }

    /**
     * Create a new relation. Do not forget to call from and to afterwards.
     *
     * @param $type
     * @return Relation
     */
    public static function makeByType($type)
    {
        $class = static::typeToClassName($type);
        return new $class();
    }


    /**
     * Convert the type of a relationship to the according class name.
     *
     * @param $type
     * @throws InvalidArgumentException
     * @return mixed
     */
    protected static function typeToClassName($type)
    {
        if (class_exists($type)) return $type;

        switch (strtolower($type)) {
            case 'onetoone':
                return OneToOne::class;
            case 'onetomany':
                return OneToMany::class;
            case 'manytomany':
                return ManyToMany::class;
            default:
                throw new InvalidArgumentException($type);
        }
    }

    /**
     * @return string
     */
    public function toNaming()
    {
        return $this->toNaming;
    }

    /**
     * @return string
     */
    public function toTable()
    {
        return $this->toTable;
    }

    /**
     * @return string
     */
    public function fromNaming()
    {
        return $this->fromNaming;
    }

    /**
     * @return string
     */
    public function fromTable()
    {
        return $this->fromTable;
    }

    /**
     * @return string
     */
    public function fromPrimaryKey()
    {
        return $this->fromPrimaryKey;
    }

    /**
     * @return string
     */
    public function toPrimaryKey()
    {
        return $this->toPrimaryKey;
    }


    public function type()
    {
        return static::class;
    }

    public function isManyToMany()
    {
        return $this instanceof ManyToMany;
    }

    public function isOneToMany()
    {
        return $this instanceof OneToMany;
    }

    public function isOneToOne()
    {
        return $this instanceof OneToOne;
    }

    public function isOneToOneOrMany()
    {
        return $this instanceof OneToOneOrMany;
    }

    public function shortType()
    {
        $parts = explode('\\', $this->type());
        return $parts[count($parts) - 1];
    }

    /**
     * @return Scopes
     */
    public function getScopes()
    {
        if(!$this->scopes)
            $this->setScopes(new Scopes());

        return $this->scopes;
    }

    /**
     * @param Scopes $scopes
     */
    public function setScopes(Scopes $scopes)
    {
        $this->scopes = $scopes;
    }


}