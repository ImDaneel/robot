<?php namespace Robot\Listeners;

interface AuthenticatorListener
{
    public function userFound($user);
    public function userNotFound();
}
