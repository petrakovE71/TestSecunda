<?php

namespace App\Services;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationLocationService
{
    /**
     * Find organizations by location using either radius or rectangular area search.
     *
     * @param array $params Location search parameters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByLocation(array $params)
    {
        $query = Organization::with(['building', 'phoneNumbers', 'activities'])
            ->join('buildings', 'organizations.building_id', '=', 'buildings.id');

        if (isset($params['radius'])) {
            return $this->findByRadius($query, $params);
        } else {
            return $this->findByRectangle($query, $params);
        }
    }

    /**
     * Find organizations within a radius using Haversine formula.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function findByRadius($query, array $params)
    {
        // Haversine formula to calculate distance
        $haversine = "(
            6371 * acos(
                cos(radians(?)) *
                cos(radians(buildings.latitude)) *
                cos(radians(buildings.longitude) - radians(?)) +
                sin(radians(?)) *
                sin(radians(buildings.latitude))
            )
        )";

        return $query->selectRaw("organizations.*, {$haversine} AS distance", [
                $params['latitude'],
                $params['longitude'],
                $params['latitude']
            ])
            ->having('distance', '<=', $params['radius'])
            ->orderBy('distance')
            ->get();
    }

    /**
     * Find organizations within a rectangular area.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function findByRectangle($query, array $params)
    {
        return $query->whereBetween('buildings.latitude', [$params['min_lat'], $params['max_lat']])
            ->whereBetween('buildings.longitude', [$params['min_lng'], $params['max_lng']])
            ->get();
    }
}
