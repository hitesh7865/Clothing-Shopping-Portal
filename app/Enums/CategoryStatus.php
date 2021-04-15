<?php
namespace App\Enums;

class CategoryStatus extends Enum
{
    const ACTIVE = 1;
    const PAUSED = 0;
    const SENDING_TO_REVIEW = 3;
    const REJECTED = 4;
    const DELETED =5;
}
