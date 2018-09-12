<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'group_id', 'filename', 'extension', 'filesize', 'location'
    ];

    /**
     * Get the owner of the file.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the group the file belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
