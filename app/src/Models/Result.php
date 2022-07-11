<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;

class Result extends DataObject 
{
    private static $table_name = 'Result';

    private static $db = [
        'Age' => 'Int',
        'DateOfList' => 'Date',
        'Gender' => 'Enum("M, F, U")',
        'Squat' => 'Decimal',
        'Bench' => 'Decimal',
        'Deadlift' => 'Decimal',
        'Total' => 'Decimal',
        'Active' => 'Boolean',
    ];

    private static $has_one = [
        'Competition' => Competition::class,
        'LiftType' => LiftType::class,
        'Lifter' => Lifter::class
    ];
}
