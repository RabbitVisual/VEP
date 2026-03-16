<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'cpf',
        'birth_date',
        'phone',
        'avatar',
        'church',
        'ministry',
        'email',
        'password',
        'is_active',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Ministry memberships (MinistryMember) for CRM / pastoral panel.
     */
    public function ministryMemberships(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\VertexSolutions\Ministry\Models\MinistryMember::class);
    }

    /**
     * Bible verses favorited by the user (bible_favorites).
     */
    public function bibleFavorites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\VertexSolutions\Core\Models\BibleFavorite::class);
    }

    /**
     * Users that this user follows (Community Hub).
     */
    public function following(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\VertexSolutions\Community\Models\UserFollow::class, 'follower_id');
    }

    /**
     * Users that follow this user (Community Hub).
     */
    public function followers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\VertexSolutions\Community\Models\UserFollow::class, 'following_id');
    }

    /**
     * Posts by this user (Community Hub).
     */
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\VertexSolutions\Community\Models\Post::class);
    }

    /**
     * Prayer requests by this user (Community Hub).
     */
    public function prayerRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\VertexSolutions\Community\Models\PrayerRequest::class);
    }

    /**
     * Get the user's full name.
     */
    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * URL da foto de perfil (storage público). Retorna null se não houver avatar.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (empty($this->avatar)) {
            return null;
        }
        return Storage::disk('public')->exists($this->avatar)
            ? Storage::disk('public')->url($this->avatar)
            : null;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
}
