<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $guarded = [];

    public function majors()
    {
        return $this->belongsToMany(Major::class, 'school_majors');
    }
    public function benchmarks()
    {
        return $this->hasMany(Benchmark::class);
    }
}
