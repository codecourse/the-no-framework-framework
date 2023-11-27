<?php

namespace App\Auth;

use App\Auth\Hashing\Hasher;
use App\Auth\Providers\UserProvider;
use App\Cookie\CookieJar;
use App\Models\User;
use App\Session\SessionStore;
use Exception;

class Auth
{
    protected $hash;

    protected $session;

    protected $user;

    protected $recaller;

    protected $cookie;

    public function __construct(
        Hasher $hash,
        SessionStore $session,
        Recaller $recaller,
        CookieJar $cookie,
        UserProvider $user
    ) {
        $this->hash = $hash;
        $this->session = $session;
        $this->recaller = $recaller;
        $this->cookie = $cookie;
        $this->user = $user;
    }

    public function logout()
    {
        $this->session->clear($this->key());
    }

    public function attempt($username, $password, $remember = false)
    {
        $user = $this->user->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        if ($this->needsRehash($user)) {
            $this->user->updateUserPasswordHash($user->id, $this->hash->create($password));
        }

        $this->setUserSession($user);

        if ($remember) {
            $this->setRememberToken($user);
        }

        return true;
    }

    public function setUserFromCookie()
    {
        list($identifier, $token) = $this->recaller->splitCookieValue(
            $this->cookie->get('remember')
        );

        if (!$user = $this->user->getUserByRememberIdentifier($identifier)) {
            $this->cookie->clear('remember');
            return;
        }

        if (!$this->recaller->validateToken($token, $user->remember_token)) {
            $this->user->clearUserRememberToken($user->id);
            $this->cookie->clear('remember');

            throw new Exception();
        }

        $this->setUserSession($user);
    }

    public function hasRecaller()
    {
        return $this->cookie->exists('remember');
    }

    protected function setRememberToken($user)
    {
        list($identifier, $token) = $this->recaller->generate();

        $this->cookie->set('remember', $this->recaller->generateValueForCookie($identifier, $token));

        $this->user->setUserRememberToken(
            $user->id, $identifier, $this->recaller->getTokenHashForDatabase($token)
        );
    }

    protected function needsRehash($user)
    {
        return $this->hash->needsRehash($user->password);
    }

    public function user()
    {
        return $this->user;
    }

    public function check()
    {
        return $this->hasUserInSession();
    }

    public function hasUserInSession()
    {
        return $this->session->exists($this->key());
    }

    public function setUserFromSession()
    {
        $user = $this->user->getById($this->session->get($this->key()));

        if (!$user) {
            throw new Exception();
        }

        $this->user = $user;
    }

    protected function setUserSession($user)
    {
        $this->session->set($this->key(), $user->id);
    }

    protected function key()
    {
        return 'id';
    }

    protected function hasValidCredentials($user, $password)
    {
        return $this->hash->check($password, $user->password);
    }
}