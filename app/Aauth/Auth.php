<?php

namespace App\Aauth;


use App\Models\User;

class Auth
{
    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function user()
    {
        return User::find($_SESSION['user'] ?? false);
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }

    public function attempt(string $email, string $password)
    {
        if ($user = User::where('email', $email)->first()) {
            if (password_verify($password, $user->password)) {
                $_SESSION['user'] = $user->id;
                return true;
            }
        }
        $this->logout();
        return false;
    }
}