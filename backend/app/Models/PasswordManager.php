<?php

namespace App\Models;

class PasswordManager implements PasswordHashLib {

    const SALT = 'CruzDeMayo';

    public function hash(string $password) {
        return hash('sha512', self::SALT . $password);
    }

    public function verify(string $password, string $hash): bool {
        return ($hash == self::hash($password));
    }
}
