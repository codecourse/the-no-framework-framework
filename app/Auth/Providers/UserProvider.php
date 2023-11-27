<?php

namespace App\Auth\Providers;

interface UserProvider
{
    public function getByUsername($username);
    public function getById($id);
    public function updateUserPasswordHash($id, $hash);
    public function getUserByRememberIdentifier($identifier);
    public function clearUserRememberToken($id);
    public function setUserRememberToken($id, $identifier, $hash);
}