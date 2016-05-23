<?php


namespace MezzoLabs\Mezzo\Core\Helpers;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Files\File;
use MezzoLabs\Mezzo\Exceptions\UnexpectedException;

class Slug
{

    /**
     * @param string $name
     * @param string[] $neighbors
     * @param array $options
     * @return string
     * @throws UnexpectedException
     */
    public static function findNext($name, $neighbors, $options = ['separator' => '_', 'hasExtension' => false])
    {
        $options = new Collection($options);
        $neighbors = new Collection($neighbors);

        $separator = $options->get('separator', '_');
        $hasExtension = $options->get('hasExtension', false);

        if ($hasExtension) {
            $extension = '.' . File::getExtension($name);
            $name = File::removeExtension($name);
        } else {
            $extension = "";
        }


        $i = 1;
        while ($i < 9999) {

            if ($i == 1)
                $possibleName = $name . $extension;
            else
                $possibleName = $name . $separator . ($i) . $extension;

            if (!$neighbors->contains($possibleName))
                return $possibleName;

            $i++;
        }

        throw new UnexpectedException('Thats a hell of a lot similar strings.');

    }
}