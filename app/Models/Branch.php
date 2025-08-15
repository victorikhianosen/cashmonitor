<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{


    protected $guarded = [];

    public function company()
{
    return $this->belongsTo(Company::class);
}

}
