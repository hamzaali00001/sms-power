<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'label'
    ];

    /**
     * Get the users with this role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the creation date.
     *
     * @param  string $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y, h:i A');
    }

    /**
     * Get the last update date.
     *
     * @param  string $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y, h:i A');
    }
}
