<?php

namespace App\Enum;

enum ResultStatus: string
{
    const RELEASED = 'released';
    const NOTRELEASED = 'not-released';
    const WITHELD = 'witheld';
}
