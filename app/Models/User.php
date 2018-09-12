<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cviebrock\EloquentSluggable\Sluggable;
use AfricasTalking\SDK\AfricasTalking;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'parent_id',
        'name',
        'email',
        'password',
        'mobile',
        'credit',
        'sms_cost',
        'suspended',
        'timezone',
        'last_login',
        'last_login_ip'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
     * Get the user's role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Determine if the user has the given role.
     *
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        if ($this->role->name === $role) {
            return true;
        }

        return false;
    }

    /**
     * Get the user's file uploads.
     */
    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class);
    }

    /**
     * Get the user's contacts.
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Get the user's groups.
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Get the user's sent messages.
     */
    public function scheduledMessages()
    {
        return $this->hasMany(ScheduledMessage::class);
    }

    /**
     * Get the user's sent messages.
     */
    public function sentMessages()
    {
        return $this->hasMany(SentMessage::class);
    }

    /**
     * Get the user's credit purchases.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get the user's sender ids.
     */
    public function senderids()
    {
        return $this->hasMany(SenderId::class);
    }

    /**
     * Get the user's templates.
     */
    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    /**
     * Get the user's name.
     *
     * @param string $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }
    
    /**
     * Set the user's name.
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    /**
     * Get the user's email.
     *
     * @param string $value
     * @return string
     */
    public function getEmailAttribute($value)
    {
        return strtolower($value);
    }
    
    /**
     * Set the user's email.
     *
     * @param string $value
     * @return void
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
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
     * Get the user last login.
     *
     * @param  string $value
     * @return string
     */
    public function getLastLoginAttribute($value)
    {
        if (!empty($value)) {
            return Carbon::parse($value)->format('d M Y, h:i A');
        }

        return 'N/A';
    }

    /**
     * Get the verification date.
     *
     * @param  string $value
     * @return string
     */
    public function getEmailVerifiedAtAttribute($value)
    {
        if (!empty($value)) {
            return Carbon::parse($value)->format('d M Y, h:i A');
        }

        return 'Not Yet';
    }

    /**
     * Get the user's parent account.
     */
    public function parentAccount()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the user's parent account.
     *
     * @return boolean
     */
    public function isParentAccount()
    {
        if ($this->parent_id === null) {
            return true;
        }

        return false;
    }

    /**
     ** Instantiate a new AfricasTalking instance.
     *
     * @return AfricasTalking\SDK\AfricasTalking;
     */
    protected function africastalking()
    {
        $username = env('AFRICASTALKING_USERNAME');
        $apiKey = env('AFRICASTALKING_API_KEY');

        return new AfricasTalking($username, $apiKey);
    }

    /**
     * Get the user's credit balance.
     */
    public function creditBalance()
    {
        if ($this->hasRole('admin')) {
            $account = $this->africastalking()->account()->fetchAccount();
            return number_format(trim(last(preg_split('/\s/', $account['data']->UserData->balance))), 2);
        } else {
            return number_format($this->credit, 2);
        }
    }
}
