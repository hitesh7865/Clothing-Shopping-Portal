<?php
namespace App\Enums;

class QuestionType extends Enum
{
    const RANGE = 1;
    const MULTI_OPTION = 2;
    const TEXT=3;
    const LONG_TEXT=4;
    const DROPDOWN=5;
}
