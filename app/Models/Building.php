<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Building",
 *     required={"address", "latitude", "longitude"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="address", type="string", example="ул. Ленина, 1"),
 *     @OA\Property(property="latitude", type="number", format="float", example=55.7558),
 *     @OA\Property(property="longitude", type="number", format="float", example=37.6173),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Building extends Model
{
    protected $fillable = ['address', 'latitude', 'longitude'];

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }
}
