<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;

class Record extends DataObject 
{
    private static $table_name = 'Record';

    private static $db = [
        'RecordType' => 'Enum("Squat, Bench, Deadlift, Total")',
        'Active' => 'Boolean',
    ];

    private static $has_one = [
        'Result' => Lifter::class
    ];
}
