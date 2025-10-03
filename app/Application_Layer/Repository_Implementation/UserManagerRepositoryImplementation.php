<?php

namespace App\Application_Layer\RepositoryImplementation;
use App\Contracts\UserManagerRepositoryInterface;
use App\Models\User;

class UserManagerRepositoryImplementation implements UserManagerRepositoryInterface{

    public function saveUser(User $user): User
    {
        $user->save();
        return $user;
    }

    public function deleteUser(User $user): bool
    {
        $user->delete();
        return true;
    }

    public function updateUser(User $user): ?User
    {
        if ($user->isDirty()) {
            $user->save();
        }
        return $user;
    }

    public function deleteUserById(int $id): bool
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return true;
        }
        return false;
    }

    public function deleteUserByEmail(string $email): bool
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->delete();
        }
        return true;
    }

}
