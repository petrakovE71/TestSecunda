<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="OrganizationPhone",
 *     required={"number"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="organization_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="number", type="string", example="+7 (999) 123-45-67"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class OrganizationPhone extends Model
{
    protected $table = 'organization_phones';
    protected $fillable = ['number', 'organization_id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
