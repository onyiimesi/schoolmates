<?php

namespace App\Enum;

enum ResultStatus: string
{
    case RELEASED = 'released';
    case NOTRELEASED = 'not-released';
    case WITHELD = 'witheld';
}
