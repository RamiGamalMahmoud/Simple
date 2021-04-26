<?php

namespace Simple\Contracts;

interface ErrorHandlerInterface
{
    public function internalError();
    public function pageNotFound();
    public function resourcesRemoved();
    public function authorizationError();
}
