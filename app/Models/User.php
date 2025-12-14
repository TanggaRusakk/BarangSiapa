<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
        'image_path',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function getPhotoUrlAttribute()
    {
        // Support both `profile_photo` and `image_path` fields depending on older code
        if ($this->profile_photo) {
            return asset(ltrim($this->profile_photo, '/'));
        }

        if ($this->image_path) {
            return asset('images/profile/' . ltrim($this->image_path, '/'));
        }

        // Prefer JPEG placeholder if present, fallback to PNG
        if (file_exists(public_path('images/profiles/profile_placeholder.jpg'))) {
            return asset('images/profiles/profile_placeholder.jpg');
        }

        return asset('images/profiles/profile_placeholder.png');
    }
}
