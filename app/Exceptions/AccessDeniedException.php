<?php

namespace App\Exceptions;

use Exception;

class AccessDeniedException extends Exception
{
    protected $message = 'Access denied';
}
