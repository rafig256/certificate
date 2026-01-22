<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CertificatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'administrator',
            'admin',
            'organizer',
            'signer',
            'user'
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Certificate $certificate): bool
    {
        if ($user->hasRole('administrator')) {
            return true;
        }
        if ($user->hasRole('organizer')) {
            return $certificate->event->organizer_id === $user->id;
        }

        // Signer → اگر امضاکننده این رویداد است
        if ($user->hasRole('signer')) {
            return $certificate->event
                ->signatories()
                ->where('users.id', $user->id)
                ->exists();
        }
        if ($user->hasRole('user')){
            return $certificate->certificateHolder?->user_id === $user->id;
        }
        return false;
    }


    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Certificate $certificate): bool
    {
        // Administrator → همه
        if ($user->hasRole('administrator')) {
            return true;
        }

        // Organizer → فقط گواهینامه‌های رویداد خودش
        if ($user->hasRole('organizer')) {
            return $certificate->event->organizer_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Certificate $certificate): bool
    {
        // فقط Administrator
        return $user->hasRole('administrator');
    }

}
