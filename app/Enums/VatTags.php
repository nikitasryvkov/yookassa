<?php

namespace App\Enums;

enum VatTags: int
{
    case Zero = 1104;
    case Ten = 1103;
    case Twelve = 1102;
    case NotUsed = 1105;
    case TenWithRate = 1107;
    case TwelveWithRate = 1106;
}
