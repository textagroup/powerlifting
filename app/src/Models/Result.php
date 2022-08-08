<?php

namespace Powerlifting;

use SilverStripe\Forms\DropdownField;
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

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        // Too many objects for LifterClass so Dropdown becomed a numeric field
        // by default, so setup the field manually
        $lifterClasses = LifterClass::get()
            ->filter('Active', 1)
            ->map('ID', 'Title')
            ->toArray();

        $lifterClassField = DropdownField::create('LifterClassID', 'Lifter class');
        $lifterClassField->setSource($lifterClasses);

        $fields->replaceField('LifterClassID', $lifterClassField);

        return $fields;
    }

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
