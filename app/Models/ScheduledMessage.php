<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ScheduledMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'group_id',
        'from',
        'to',
        'recipients',
        'message',
        'msg_count',
        'characters',
        'cost',
        'send_time'
    ];

    /**
     * Get the message owner.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the message characters.
     *
     * @param  string $message
     * @return string
     */
    public function getCharactersAttribute($message)
    {
        return utf8_encode(strlen($this->message));
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

    /**
     * Get the send time.
     *
     * @param  string $value
     * @return string
     */
    public function getSendTimeAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y, h:i A');
    }

    /**
     * Get the send time.
     *
     * @param  string $value
     * @return string
     */
    public function getRecipientsAttribute($value)
    {
        return number_format($value);
    }
}
