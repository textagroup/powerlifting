<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;

class Record extends DataObject 
{
    private static $table_name = 'Record';

    private static $extensions = [
        Versioned::class . '.versioned',
    ];

    private static $db = [
        'RecordType' => 'Enum("Squat, Bench, Deadlift, Total")',
        'Active' => 'Boolean',
    ];

    private static $has_one = [
        'Result' => Lifter::class
    ];
}
