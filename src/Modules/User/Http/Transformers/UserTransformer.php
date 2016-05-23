<?php


namespace MezzoLabs\Mezzo\Modules\User\Http\Transformers;


use App\Tutorial;
use App\User;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Http\Transformers\EloquentModelTransformer;

class UserTransformer extends EloquentModelTransformer
{
    /**
     * @var string
     */
    protected $modelName = User::class;

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'roles',
        'tutorials'
    ];

    protected $defaultIncludes = [
        'roles'
    ];

    public function transform($model)
    {
        if(! $model instanceof User)
            throw new InvalidArgumentException($model);

        return parent::transform($model);
    }

    /**
     * Include Roles
     *
     * @param User $user
     * @return \League\Fractal\Resource\Item
     */
    public function includeRoles(User $user)
    {
        return $this->automaticCollection($user->roles);
    }

    /**
     * Include Tutorials
     *
     * @param User $user
     * @return \League\Fractal\Resource\Item
     */
    public function includeTutorials(User $user)
    {
        return $this->automaticCollection($user->tutorials);
    }

}