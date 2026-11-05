<?php

declare(strict_types=1);

namespace App\Features\Loads\Policies;

use App\Features\Loads\Models\Load;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class LoadPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Load');
    }

    public function view(AuthUser $authUser, Load $load): bool
    {
        return $authUser->can('View:Load');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Load');
    }

    public function update(AuthUser $authUser, Load $load): bool
    {
        return $authUser->can('Update:Load');
    }

    public function delete(AuthUser $authUser, Load $load): bool
    {
        return $authUser->can('Delete:Load');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Load');
    }

    public function restore(AuthUser $authUser, Load $load): bool
    {
        return $authUser->can('Restore:Load');
    }

    public function forceDelete(AuthUser $authUser, Load $load): bool
    {
        return $authUser->can('ForceDelete:Load');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Load');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Load');
    }

    public function replicate(AuthUser $authUser, Load $load): bool
    {
        return $authUser->can('Replicate:Load');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Load');
    }
}
