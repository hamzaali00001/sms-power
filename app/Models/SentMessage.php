<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SentMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'from',
        'to',
        'message',
        'msg_count',
        'characters',
        'cost',
        'status'
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
     * Get the status.
     *
     * @param  string $value
     * @return string
     */
    public function getStatusAttribute($value)
    { 
        switch ($value)
        {
            case 'Failed':
                return 'Failed';

            case 'Success':
                return 'Delivered';

            case 'Rejected':
                return 'Failed';

            case 'Buffered':
                return 'Submitted';

            case 'Submitted':
                return 'Submitted';

            case 'Sent':
                return 'Submitted';

            default:
                return 'Unknown';
        }
    }

    /**
     * Get the status label.
     *
     * @param  string $value
     * @return string
     */
    public function getStatusLabelAttribute($value)
    { 
        switch ($this->status)
        {
            case 'Failed':
                return 'label label-danger';

            case 'Success':
                return 'label label-success';

            case 'Rejected':
                return 'label label-danger';

            case 'Buffered':
                return 'label label-info';

            case 'Submitted':
                return 'label label-info';

            case 'Sent':
                return 'label label-info';

            default:
                return 'label label-default';
        }
    }
}
