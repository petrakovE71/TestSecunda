<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationPhone extends Model
{
    protected $table = 'organization_phones';
    protected $fillable = ['number', 'organization_id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
