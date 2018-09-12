<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contact extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'group_id', 'name', 'mobile', 'active'
    ];

    /**
     * Get the group the contact belongs to
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the contact owner
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the contact's name
     *
     * @param string $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    /**
     * Get the masked mobile number
     *
     * @param string $value
     * @return string
     */
    public function mask($number)
    {
        return str_repeat("*", strlen($number)-4) . substr($number, -4);
    }
    
    /**
     * Set the contact's name
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    /**
     * Get the creation date
     *
     * @param  string $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y, h:i A');
    }

    /**
     * Get the last update date
     *
     * @param  string $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y, h:i A');
    }

    /**
     * Get the status
     *
     * @param  string $value
     * @return string
     */
    public function getActiveAttribute($value)
    {
        return $value ? 'Yes' : 'No';
    }

    /**
     * Scope a query to only include active contacts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
