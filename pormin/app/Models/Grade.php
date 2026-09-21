<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['code', 'name', 'description', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function ageRule()
    {
        return $this->hasOne(GradeAgeRule::class)->where('is_active', true);
    }
}
