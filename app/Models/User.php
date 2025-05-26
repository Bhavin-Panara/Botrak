<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'encrypted_password',
        'reset_password_token',
        'reset_password_sent_at',
        'remember_created_at',
        'confirmation_token',
        'confirmed_at',
        'confirmation_sent_at',
        'unconfirmed_email',
        'created_at',
        'updated_at',
        'invitation_token',
        'invitation_created_at',
        'invitation_sent_at',
        'invitation_accepted_at',
        'invitation_limit',
        'invited_by_type',
        'invited_by_id',
        'invitations_count',
        'authentication_token',
        'recent_organization_id'
    ];

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'users_roles', 'user_id', 'role_id');
    }

    public function organizations()
    {
        return $this->belongsTo(Organizations::class, 'recent_organization_id');
    }
}