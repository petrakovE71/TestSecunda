<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Activity",
 *     required={"name", "level"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Еда"),
 *     @OA\Property(property="parent_id", type="integer", format="int64", nullable=true, example=null),
 *     @OA\Property(property="level", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Activity extends Model
{
    protected $fillable = ['name', 'parent_id', 'level'];

    public function parent()
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_activity');
    }
}
