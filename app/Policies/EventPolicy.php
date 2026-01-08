<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'administrator',
            'organizer',
            'signer',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        // administrator → همه
        if ($user->hasRole('administrator')) {
            return true;
        }

        // organizer → رویدادهای خودش
        if ($user->hasRole('organizer')) {
            return $event->organizer_id === $user->id;
        }

        // signer → اجازه دیدن (بدون مالکیت)
        if ($user->hasRole('signer')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'administrator',
            'organizer',
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        // administrator → همه
        if ($user->hasRole('administrator')) {
            return true;
        }

        // organizer → فقط رویداد خودش
        if ($user->hasRole('organizer')) {
            return $event->organizer_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        return $this->update($user, $event);
    }

}
