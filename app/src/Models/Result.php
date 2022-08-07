<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;
//use SilverStripe\Versioned\Versioned;

class Result extends DataObject 
{
    private static $table_name = 'Result';

/*
    private static $extensions = [
        Versioned::class . '.versioned',
    ];
*/

    private static $db = [
        'Age' => 'Int',
        'DateOfLift' => 'Date',
        'Gender' => 'Enum("M, F, U")',
        'Squat' => 'Decimal',
        'Bench' => 'Decimal',
        'Deadlift' => 'Decimal',
        'Total' => 'Decimal',
        'Active' => 'Boolean',
    ];

    private static $has_one = [
        'Competition' => Competition::class,
        'Lifter' => Lifter::class,
        'LifterClass' => LifterClass::class,
        'LiftType' => LiftType::class
    ];

    private static $defaults = [
        'active' => 1
    ];

    private static $summary_fields = [
        'Lifter.Title' => 'Lifter',
        'Competition.Title' => 'Competition',
        'Active'
    ];

    public function onAfterWrite()
    {
        parent::onAfterWrite();
        if ($this->Active == 0) {
            return;
        }
        Record::recordBroken($this, $this->LifterClass());
        $children = json_decode($this->LifterClass()->Override);
        if ($children) {
            foreach ($children as $id) {
                $class = LifterClass::get_by_id($id);
                Record::recordBroken($this, $class);
            }
        }
    }
}
