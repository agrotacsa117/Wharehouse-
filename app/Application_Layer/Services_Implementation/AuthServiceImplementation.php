<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\Repository_Implementation\UserFinderRepositoryImplementation;
use App\Application_Layer\Repository_Implementation\UserManagerRepositoryImplementation;
use App\Contracts\AuthServiceInterface;
use App\Contracts\UserFinderRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use App\Application_Layer\ResultPattern;



class AuthServiceImplementation implements AuthServiceInterface
{

    private UserFinderRepositoryInterface $userFinderRepository;

    public function __construct(
        UserFinderRepositoryInterface $userFinderRepository
    ) {
        $this->userFinderRepository = $userFinderRepository;
    }

    public function login(
        string $email,
        string $password
    ): ResultPattern {

        $user = $this->userFinderRepository->findByEmail($email);
        $isCorrectPassword = Hash::check(
            $password,
            $user->password
        );

        
        if (!$user || !$isCorrectPassword) {
        }


        if ($user->activo === 0) {
        }

        return ResultPattern::success("");
    }

    public function logOut(): void {}
}
