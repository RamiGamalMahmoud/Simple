<?php

namespace Simple\Core;

interface IErrorHandler
{
    public function internalError();
    public function pageNotFound();
    public function resourcesRemoved();
    public function authorizationError();
}
