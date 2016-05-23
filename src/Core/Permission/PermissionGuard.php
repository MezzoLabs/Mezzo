<?php


namespace MezzoLabs\Mezzo\Core\Permission;

use Illuminate\Auth\Guard as AuthGuard;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Http\Exceptions\NoPermissionsException;

class PermissionGuard
{
    /**
     * @var AuthGuard
     */
    protected $authGuard;

    protected static $lastMissingPermission;

    protected static $active = true;

    /**
     * @param AuthGuard $authGuard
     */
    public function __construct(AuthGuard $authGuard)
    {
        $this->authGuard = $authGuard;
    }

    /**
     * Get the logged in user.
     *
     * @return \App\User|null
     */
    public function user(\App\User $user = null) : \App\User
    {
        if ($user)
            return $user;

        $authUser = $this->authGuard->user();

        if (!$authUser) {
            $this->fail('Only logged in users can perform this task.');
        }

        return $authUser;
    }

    /**
     * Check if there is a logged in user.
     *
     * @return bool
     */
    public function loggedIn()
    {
        return $this->user() != null;
    }

    public function allowsCreate(MezzoModel $model, \App\User $user = null, $options = []) : bool
    {
        return $this->allowsModelAccess($model, 'create', $user);
    }

    public function allowsShow(MezzoModel $model, \App\User $user = null, $options = []) : bool
    {
        return $this->allowsModelAccess($model, 'show', $user);
    }

    public function allowsEdit(MezzoModel $model, \App\User $user = null, $options = []) : bool
    {
        return $this->allowsModelAccess($model, 'edit', $user);
    }

    public function allowsDelete(MezzoModel $model, \App\User $user = null, $options = []) : bool
    {
        return $this->allowsModelAccess($model, 'delete', $user);
    }

    public function allowsCockpit(\App\User $user = null, $options = []) : bool
    {
        return $this->hasPermission('cockpit', $user) && $this->user($user)->isBackendUser();
    }

    public function allowsCreateOrEdit(MezzoModel $model, $options = [])
    {
        if ($model->exists)
            return $this->allowsEdit($model);

        return $this->allowsCreate($model);
    }

    protected function allowsModelAccess(MezzoModel $model, $level = 'show', \App\User $user = null, $options = [])
    {
        $options['log'] = $options['log'] ?? false;

        if (!$this->enabled()) {
            return true;
        }

        if (!$user) $user = $this->user();

        $accessAll = $this->permissionKey($level, $model);
        $accessOwn = $this->permissionKey($level . '_own', $model);

        if ($this->hasPermission($accessAll))
            return true;

        if ($this->hasPermission($accessOwn) && $model->isOwnedByUser($user))
            return true;

        static::$lastMissingPermission = $accessOwn;

        if ($options['log']) {
            mezzo()->logger()->logMissingPermission($accessAll);
        }

        return false;
    }

    /**
     * @param $name
     * @param MezzoModel|null $model
     * @return string
     */
    protected function permissionKey($name, MezzoModel $model = null)
    {
        if ($model)
            return strtolower(class_basename($model)) . '.' . strtolower($name);

        return strtolower($name);
    }

    public function hasPermission($permission, \App\User $user = null)
    {
        $user = $this->user($user);

        if (!$user) return false;

        return $user->hasPermission($permission);
    }

    public function enabled()
    {
        $noPermissionCheck = (env('MEZZO_NO_PERMISSION_CHECK', false) && \Request::header('no-permission-check'));

        return !$noPermissionCheck && $this->isActive();
    }

    public static function fail($hint)
    {
        mezzo()->logger()->logMissingPermission('Missing permission: ' . static::$lastMissingPermission . ' ' . $hint);


        throw new NoPermissionsException("Unauthorized. You need the \"" . static::$lastMissingPermission . "\"" .
            " permission to perform this action. " . $hint);
    }

    /**
     * @return static
     */
    public static function make()
    {
        return app()->make(static::class);
    }

    public static function setActive(bool $active)
    {
        static::$active = $active;
    }

    public static function isActive() : bool
    {
        return static::$active;
    }
}