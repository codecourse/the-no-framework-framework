<?php

namespace App\Auth\Hashing;

interface Hasher
{
    public function create($plain);

    public function check($plain, $hash);

    public function needsRehash($hash);
}
