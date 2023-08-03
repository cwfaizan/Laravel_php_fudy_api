<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact_no',
        'contact_no_verified',
        'email',
        'email_verified',
        'password',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function hasRole($name)
    {
        foreach ($this->roles()->get() as $role) {
            if ($role->name == $name) {
                return true;
            }
        }
        return false;
    }

    public function hasAnyRole($names = [])
    {
        $roles = collect($names);
        foreach ($this->roles()->get() as $role) {
            if ($roles->contains($role->name)) {
                return true;
            }
        }
        return false;
    }

    public function hasAccount($roleId)
    {
        foreach ($this->roles()->get() as $role) {
            if ($role->id == $roleId) {
                return true;
            }
        }
        return false;
    }

    public function isAdmin()
    {
        foreach ($this->roles()->get() as $role) {
            if ($role->name == 'admin') {
                return true;
            }
        }
        return false;
    }

    public function isSeller()
    {
        foreach ($this->roles()->get() as $role) {
            if ($role->name == 'seller') {
                return true;
            }
        }
        return false;
    }
}
