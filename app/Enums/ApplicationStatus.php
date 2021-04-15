<?php
namespace App\Enums;

class ApplicationStatus extends Enum
{
    const PENDING_REVIEW = 1;
    const QUESTIONED = 2;
    const RESPONDED = 3;
    const SCREENED = 4;
    const REJECTED = 5;
    const HIRED = 6;
    const HOLD = 7;
    const LIST_ALL = [
      [
        'id' => 1,
        'value' => "Pending Review"
      ],
      [
        'id' => 2,
        'value' => 'Questioned'
      ],
      [
        'id' => 3,
        'value' => 'Responded'
      ],
      [
        'id' => 7,
        'value' => 'On Hold'
      ],
      [
        'id' => 4,
        'value' => 'Screened'
      ],
      [
        'id' => 5,
        'value' => 'Rejected'
      ],
      [
        'id' => 6,
        'value' => 'Hired'
      ]
      
      
    ];
    public static function getKeyById($id)
    {
        foreach (ApplicationStatus::LIST_ALL as $status) {
            if ($status['id'] == $id) {
                return $status['value'];
            }
        }
    }
}
