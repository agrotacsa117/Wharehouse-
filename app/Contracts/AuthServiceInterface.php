<?php

namespace App\Contracts;

use App\Models\User;
use App\Application_Layer\ResultPattern;

interface AuthServiceInterface 
{

    public function login(string $email, string $password): ResultPattern;

    public function logOut(): void;
}
