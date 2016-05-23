<?php


namespace MezzoLabs\Mezzo\Cockpit\Pages\Forms;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Schema\Attributes\Attribute;
use MezzoLabs\Mezzo\Core\Schema\Attributes\RelationAttribute;
use MezzoLabs\Mezzo\Core\Schema\Relations\OneToOneOrMany;
use MezzoLabs\Mezzo\Core\Schema\Relations\RelationSide;

class IndexTableColumn
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $type;

    /**
     * @var Collection
     */
    public $options;



    /**
     * IndexTableColumn constructor.
     * @param string $title
     * @param string $type
     * @param array $options
     */
    public function __construct(string $name, string $title = null, string $type = "string", $options = [])
    {
        $this->name = $name;
        $this->title = ($title != null) ? $title : ucfirst($name);
        $this->type = $type;

        $this->options = (new Collection([
            'hasMultipleChildren' => false,
            'column' => $this->name,
            'isRelation' => false,
            'attribute' => null
        ]))->merge($options);
    }

    public function setRelation(RelationSide $relationSide)
    {
        $this->options->put('hasMultipleChildren', $relationSide->hasMultipleChildren());

        if($relationSide->relation() instanceof OneToOneOrMany && $relationSide->containsTheJoinColumn()){
            $this->options->put('column', $relationSide->attributeName());
        } else {
            $this->options->put('column', '');
        }

        $this->options->put('isRelation', true);
    }

    /**
     * @return bool
     */
    public function hasAttribute()
    {
        return $this->options->get('attribute') != null;
    }

    /**
     * @return Attribute
     */
    public function getAttribute()
    {
        return $this->options->get('attribute');
    }

    public function hasRelationAttribute()
    {
        if(!$this->hasAttribute()){
            return false;
        }

        return $this->getAttribute()->isRelationAttribute();
    }

    /**
     * @param Attribute $attribute
     */
    public function setAttribute(Attribute $attribute)
    {
        if ($attribute instanceof RelationAttribute) {
            $this->setRelation($attribute->relationSide());
        }

        $this->options->put('attribute', $attribute);
    }

    public static function makeFromAttribute(Attribute $attribute) : IndexTableColumn
    {
        $column = new static($attribute->naming(), $attribute->title(), $attribute->type()->doctrineTypeName());

        $column->setAttribute($attribute);

        return $column;
    }

    public static function makeFromCalculatedValue(string $name, string $title = null, string $type = "string") : IndexTableColumn
    {
        $column = new static($name, $title, $type, [
            'column' => ''
        ]);

        return $column;
    }

    public function output()
    {

    }
}