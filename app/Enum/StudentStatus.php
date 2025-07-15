<?php

namespace App\Enum;

enum StudentStatus: string
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const DISABLED = 'disabled';
    const GRADUATED = 'graduated';
}
