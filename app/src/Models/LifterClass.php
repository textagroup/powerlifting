<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\ListboxField;
use SilverStripe\Versioned\Versioned;

class LifterClass extends DataObject 
{
    private static $table_name = 'LifterClass';

    private static $extensions = [
        Versioned::class . '.versioned',
    ];

    private static $db = [
        'Title' => 'Varchar',
        'MinWeight' => 'Int',
        'MaxWeight' => 'Int',
        'MinAge' => 'Int',
        'MaxAge' => 'Int',
        'Gender' => 'Enum("M, F, U")',
        'StandardSquat' => 'Decimal',
        'StandardBench' => 'Decimal',
        'StandardDeadlift' => 'Decimal',
        'StandardTotal' => 'Decimal',
        'Override' => 'Text',
        'Active' => 'Boolean',
    ];

    private static $has_one = [
        'Affiliation' => Affiliation::class,
        'LiftType' => LiftType::class
    ];

    /**
     * returns the current (or standard) record for a lifter class
     *
     * @param LifterClass $lifterClass
     *
     * @return array
     */
    public function getCurrentRecords()
    {
        $results = [];
        $standards = [
            'Squat' => $this->StandardSquat,
            'Bench' => $this->StandardBench,
            'Deadlift' => $this->StandardDeadlift,
            'Total' => $this->StandardTotal,
        ];
        foreach (Record::$checkRecords as $record) {
            $weight = Result::get()
                ->filter([
                    'active' => 1,
                    "$record:GreaterThan" => 0,
                    'LifterClassID' => $this->ID,
                ])
                ->sort($record, 'DESC')
                ->first();
            if ($weight && $weight->exists() && $weight->$record > $standards[$record]) {
                $results[$record] = [
                    'ID' => $weight->ID,
                    'Time' => strtotime($weight->DateOfLift),
                    'Weight' => $weight->$record
                ];
            } else {
                $results[$record] = [
                    'ID' => 0,
                    'Time' => 0,
                    'Weight' => $standards[$record]
                ];
            }
        }
        return $results;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $tagField = ListboxField::create(
            'Override',
            'Override',
            LifterClass::get()->filter('Active', 1)->exclude('ID', $this->ID)
                ->map('ID', 'Title'),
            explode(',', $this->Override)
        );
        $fields->replaceField('Override', $tagField);
        return $fields;
    }
}


