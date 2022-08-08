<?php

namespace App\Models;

interface PasswordHashLib {

    public function hash (string $password);

    public function verify(string $password, string $hash): bool;

}
