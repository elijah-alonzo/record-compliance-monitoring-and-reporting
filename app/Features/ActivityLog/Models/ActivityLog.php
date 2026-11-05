<?php

namespace App\Features\ActivityLog\Models;

use App\Features\ActivityLog\Policies\ActivityLogPolicy;
use App\Features\Users\Models\User;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $description
 */
#[UsePolicy(ActivityLogPolicy::class)]
class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'description',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
