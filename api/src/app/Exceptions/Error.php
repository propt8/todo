<?php

namespace App\Exceptions;

interface Error
{
    const INTERNAL_SERVER_ERROR = 'INTERNAL_SERVER_ERROR';

    const UNAUTHORIZED = 'UNAUTHORIZED';
    const FORBIDDEN = 'FORBIDDEN';
}
