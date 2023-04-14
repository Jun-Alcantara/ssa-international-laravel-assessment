<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Detail;
use App\Events\UserSaved;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prefixname',
        'firstname',
        'middlename',
        'lastname',
        'suffixname',
        'username',
        'email',
        'password',
        'photo',
        'type',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($user) {
            event(new UserSaved($user));
        });
    }

    public function getAvatarAttribute()
    {
        return photo_cdn($this->photo)
            ?? 'https://placehold.co/65?text=Default+Avatar';
    }

    public function getFullnameAttribute()
    {
        return "{$this->firstname} {$this->middleinitial} {$this->lastname}";
    }

    public function getMiddleinitialAttribute()
    {
        $middleinitial = substr($this->middlename, 1, 1);
        return strtoupper($middleinitial) . ".";
    }

    public function details()
    {
        return $this->hasMany(Detail::class);
    }
}
