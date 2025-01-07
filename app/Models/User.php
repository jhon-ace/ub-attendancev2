<?php

namespace App\Models;

// // use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;
// use Spatie\Permission\Traits\HasRoles;
// use \App\Models\Admin\School; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use \App\Models\Admin\School; 


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'school_id',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function createUserBiometric(string $publicKey)
    {
        // Create a biometric for the user
        $this->createBiometric($publicKey);
    }

    /**
     * Verify a biometric signature for the user.
     *
     * @param string $uuid
     * @param string $signature
     * @return bool
     */
    public function verifyUserBiometric(string $uuid, string $signature)
    {
        // Verify the biometric for the user
        return $this->verifyBiometric($uuid, $signature);
    }

    /**
     * Revoke a biometric for the user.
     *
     * @param string $uuid
     * @return void
     */
    public function revokeUserBiometric(string $uuid)
    {
        // Revoke the biometric for the user
        $this->revokeBiometric($uuid);
    }

}
