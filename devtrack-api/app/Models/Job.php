<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_listings';

    protected $fillable = [
        'external_job_id',
        'title',
        'company',
        'location',
        'description',
        'source',
        'apply_url',
    ];

    public function savedByUsers()
    {
        return $this->hasMany(SavedJob::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
