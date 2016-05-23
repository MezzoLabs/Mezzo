<?php


namespace App\Mezzo\Generated\ModelParents;


use App\Mezzo\BaseModel;
use MezzoLabs\Mezzo\Core\Annotations as Mezzo;
use MezzoLabs\Mezzo\Core\Traits\IsMezzoModel;

abstract class MezzoRole extends BaseModel
{
    use IsMezzoModel;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "roles";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'label'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $rules = [
        'name' => 'required|max:255|alpha_dash',
        'label' => 'required|max:255|alpha_num'
    ];

    /**
     * @Mezzo\Attribute(type="NumberInput")
     * @var int
     */
    protected $id;

    /**
     * @Mezzo\Attribute(type="TextInput")
     * @var string
     */
    protected $name;

    /**
     * @Mezzo\Attribute(type="TextInput")
     * @var string
     */
    protected $label;

    /**
     *
     * @Mezzo\Attribute(type="DateInput")
     * @var string
     */
    protected $created_at;

    /**
     *
     * @Mezzo\Attribute(type="DateInput")
     * @var string
     */
    protected $updated_at;
}