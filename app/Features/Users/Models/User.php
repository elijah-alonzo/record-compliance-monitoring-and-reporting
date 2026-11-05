<?php

namespace App\Features\Users\Models;

use App\Features\ActivityLog\Concerns\LogsActivity;
use App\Features\Users\Policies\UserPolicy;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string $email
 * @property string|null $phone
 * @property-read string $full_name
 */
#[UsePolicy(UserPolicy::class)]
#[UseFactory(UserFactory::class)]
class User extends Authenticatable implements FilamentUser, HasName
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, LogsActivity, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'password',
    ];

    protected $appends = [
        'full_name',
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
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ], static fn ($part) => $part !== null && $part !== '');

        return trim(implode(' ', $parts));
    }

    public function getFilamentName(): string
    {
        $fullName = $this->full_name;

        if ($fullName !== '') {
            return $fullName;
        }

        return $this->email ?? 'User';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(['admin', 'staff']);
    }
}
