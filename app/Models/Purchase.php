<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Purchase extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'quantity', 'amount', 'trans_code', 'status'
    ];

    /**
     * Get the credit owner.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
}
