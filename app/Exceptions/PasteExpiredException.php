<?php
namespace App\Exceptions;

use Exception;

class PasteExpiredException extends Exception
{
    protected $message = 'Paste expired';
}

