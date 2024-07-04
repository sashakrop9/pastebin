<?php
namespace App\Enums;

enum AccessType: string
{
    case PUBLIC = 'public';
    case PRIVATE = 'private';
    case UNLISTED = 'unlisted';


}
