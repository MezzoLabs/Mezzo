<?php

namespace MezzoLabs\Mezzo\Modules\Addresses\Domain\Repositories;


use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;
use MezzoLabs\Mezzo\Modules\Addresses\Exceptions\ZipCodeNotFoundException;

trait RepositoryWithAddress
{
    public function addNearLocationScope(EloquentBuilder $query, float $latitude, float $longitude, int $km)
    {
        $latitude = number_format($latitude, 10, '.', '');
        $longitude = number_format($longitude, 10, '.', '');

        $selectDistance =
            '( 3959 * acos( cos( radians(' . $latitude . ') ) ' .
            '* cos( radians( addresses.latitude ) ) ' .
            '* cos( radians( addresses.longitude ) - radians(' . $longitude . ') ) ' .
            '+ sin( radians(' . $latitude . ') ) ' .
            '* sin( radians( addresses.latitude ) ) ) ) AS distance';

        $query->select(DB::raw('events.*, ' . $selectDistance));
        $query->join('addresses', 'events.address_id', '=', 'addresses.id');
        $query->having('distance', '<=', $km);
    }

    /**
     * @param EloquentBuilder $query
     * @param int $zip
     * @param $km
     * @throws ZipCodeNotFoundException
     */
    public function addNearZipScope(EloquentBuilder $query, int $zip, $km)
    {

        $zip = DB::table('zipcodes')
            ->select('latitude', 'longitude')
            ->where('zip', '=', $zip)
            ->first();

        if (empty($zip)) {
            throw new ZipCodeNotFoundException('Zipcode ' . $zip . ' not found.');
        }

        $this->addNearLocationScope($query, $zip->latitude, $zip->longitude, $km);
    }
}