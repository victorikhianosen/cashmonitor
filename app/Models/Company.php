<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    public $guarded = [];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
