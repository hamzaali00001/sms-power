<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;

class Group extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
    * Get the route key for the model.
    *
    * @return string
    */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the group file uploads.
     */
    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class);
    }

    /**
     * Get the group contacts.
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Get the group owner.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the group name.
     *
     * @param string $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }
    
    /**
     * Set the group name.
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
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
