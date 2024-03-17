<?php

namespace App\Config;

use App\User\UserRepository;

class Auth
{
    public function checkAuth(): void
    {
        $userRep = new UserRepository();
    
        if (!$userRep->isUserLogged()) {
            header('location: /login');
        }
    }
    
    public function checkAccess(int $id_user, int $id_logged): bool
    {
        return $id_logged === $id_user;
    }
}