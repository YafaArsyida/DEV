<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PenempatanSiswa;
use App\Models\User;

class PenempatanSiswaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PenempatanSiswa $penempatanSiswa): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PenempatanSiswa $penempatanSiswa): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PenempatanSiswa $penempatanSiswa): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PenempatanSiswa $penempatanSiswa): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PenempatanSiswa $penempatanSiswa): bool
    {
        //
    }
}
