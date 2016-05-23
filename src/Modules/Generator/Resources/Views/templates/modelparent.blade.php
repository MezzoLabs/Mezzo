{!! $php->openingTag() !!}

namespace App\Mezzo\Generated\ModelParents;

use MezzoLabs\Mezzo\Core\Annotations as Mezzo;
use MezzoLabs\Mezzo\Core\Traits\IsMezzoModel;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
*-------------------------------------------------------------------------------------------------------------------
*
* AUTO GENERATED - MEZZO - MODEL PARENT
*
*-------------------------------------------------------------------------------------------------------------------
*
* Please do not edit, use "{{ $parent->modelSchema()->className() }}" instead. Thank you.
*
*-------------------------------------------------------------------------------------------------------------------
* Welcome to the model parent. This file is auto generated and tells Mezzo something about
* your model. If you feel the need to overwrite something use the child class.
*
{!! $annotation->classAnnotations($parent) !!}
*/
abstract class {{ $parent->name() }} extends {{ $parent->extendsClass() }}
{
    use IsMezzoModel;

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Eloquent properties
    |-------------------------------------------------------------------------------------------------------------------
    | The properties below will influence the work of the ORM Mapper "Eloquent".
    | Do not overwrite them here. Please use the power of computer science and edit them
    | in the model which extends this model parent.
    |-------------------------------------------------------------------------------------------------------------------
    */

    /**
    * The table associated with the model.
    *
    @annotation('var', 'string')
    */
    protected $table = '{{ $parent->table() }}';

    /**
    * Set of rules that will be validated in resource requests.
    *
    @annotation('var', 'array')
    */
    protected $rules = {!! $php->rulesArray($parent->modelSchema()) !!}

    /**
    * The attributes that should be hidden for arrays.
    *
    @annotation('var', 'array')
    */
    protected $hidden = {!! $php->hiddenArray($parent->modelSchema()) !!}

    /**
    * The attributes that are mass assignable.
    *
    @annotation('var', 'array')
    */
    protected $fillable = {!! $php->fillableArray($parent->modelSchema()) !!}

    /**
    * The attributes that should be casted to native types.
    *
    * @annotation('var', 'array')
    */
    protected $casts = {!! $php->castsArray($parent->modelSchema()) !!}

    /**
    * Indicates if the model should be timestamped.
    *
    @annotation('var', 'bool')
    */
    public $timestamps = {!! $php->timestampsBoolean($parent->modelSchema()) !!}


    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Attribute annotation properties
    |-------------------------------------------------------------------------------------------------------------------    |
    | In this section you will find some annotated properties.
    | They are not really important for you, but they will tell Mezzo something about
    | the attributes of this model.
    |-------------------------------------------------------------------------------------------------------------------
    */

@foreach($parent->attributes() as $attribute)
    /**
    * Attribute annotation property for {{ $attribute->name() }}
    *
    {!! $annotation->attribute($attribute) !!}
    @annotation('var', $attribute->type()->variableType())
    */
    protected $_{{ $attribute->name() }};

@endforeach

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relation annotation properties
    |-------------------------------------------------------------------------------------------------------------------
    | In this section you will find some annotated properties.
    | They are not really important for you, but they will tell Mezzo something about
    | the relations of this model.
    |-------------------------------------------------------------------------------------------------------------------
    */

@foreach($parent->relationSides() as $relationSide)
    /**
    * Relation annotation property for {{ $relationSide->naming() }}
    {!! $annotation->relation($relationSide) !!}
    */
    protected $_{{ $relationSide->naming() }};

@endforeach

}
