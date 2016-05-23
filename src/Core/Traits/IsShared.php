<?php
/**
 * Project: MezzoDemo | IsShared.php
 * Author: Simon - www.triggerdesign.de
 * Date: 12.09.2015
 * Time: 23:42
 */

namespace MezzoLabs\Mezzo\Core\Traits;


trait IsShared
{
    /**
     * @return static
     */
    public static function make()
    {
        return app()->make(static::class);
    }
} 