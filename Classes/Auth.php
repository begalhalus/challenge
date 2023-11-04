<?php

class Auth
{
    public function authenticate(User $user)
    {
        $users = [
            ['username' => 'feon', 'password' => 'feon'],
            ['username' => 'vira', 'password' => 'vira']
        ];

        foreach ($users as $userData) {
            if ($userData['username'] === $user->getUsername() && $userData['password'] === $user->getPassword()) {
                return true;
            }
        }

        return false;
    }
}
