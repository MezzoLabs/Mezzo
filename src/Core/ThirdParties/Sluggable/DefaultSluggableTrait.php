<?php


namespace MezzoLabs\Mezzo\Core\ThirdParties\Sluggable;


trait DefaultSluggableTrait
{
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'build_from' => 'label',
            'save_to' => 'slug',
        ];
    }

}