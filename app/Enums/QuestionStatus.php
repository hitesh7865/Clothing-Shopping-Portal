<?php
namespace App\Enums;

class QuestionStatus extends Enum
{
    const ACTIVE = 1;
    const PAUSED = 0;
    const LIST_ALL = [
      [
        'id' => 1,
        'value' => "Active"
      ],
      [
        'id' => 0,
        'value' => 'Paused'
      ]
    ];
}
