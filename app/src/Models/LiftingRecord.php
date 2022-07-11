<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;

class LiftingRecord extends DataObject 
{
    private static $table_name = 'LiftingRecord';

    private static $db = [
        'RecordType' => 'Enum("Squat, Bench, Deadlift, Total")',
        'Active' => 'Boolean',
    ];

    private static $has_one = [
        'Result' => Result::class
    ];
}
