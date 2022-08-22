<?php

namespace Powerlifting;

use Page;
use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\Forms\ListboxField;
//use SilverStripe\Versioned\Versioned;

class LifterClass extends DataObject 
{
    private static $table_name = 'LifterClass';

/*
    private static $extensions = [
        Versioned::class . '.versioned',
    ];
*/

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

	private static $belongs_many_many = [
		'RecordTables' => Page::class,
	];

    /**
     * returns the best (or standard) results for a lifter class
     *
     * @return array
     */
    public function getCurrentClassResults()
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
                    'Active' => 1,
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

    /**
     * returns the current (or standard) records for a lifter class
     *
     * @param LifterClass $lifterClass
     *
     * @return array
     */
    public function getCurrentRecords()
    {
        $lastRecord = DB::query('SELECT MAX(LastEdited) FROM Record')
            ->value();
        $cache = Injector::inst()->get(CacheInterface::class . '.lifterClassCache');
        $key = md5($this->ID . $lastRecord);
        if (!$cache->has('myCacheKey')) {
            $standards = [
                'Squat' => $this->StandardSquat,
                'Bench' => $this->StandardBench,
                'Deadlift' => $this->StandardDeadlift,
                'Total' => $this->StandardTotal,
            ];
            foreach (Record::$checkRecords as $record) {
                $obj = Record::get()
                    ->filter([
                        'Active' => 1,
                        'RecordType' => $record,
                        'LifterClassID' => $this->ID,
                    ])->first();
                    if ($obj && $obj->exists()) {
                        $result = $obj->Result();
                        $results[$record] = [
                            'Name' => $result->Lifter()->Title,
                            'Meet' => $result->Competition()->Title,
                            'Weight' => $result->$record
                        ];
                    } else {
                        $results[$record] = [
                            'Name' => 'Standard',
                            'Meet' => '',
                            'Weight' => $standards[$record]
                        ];
                    }
            }
            $cache->set($key, $results);
            return $results;
        } else {
            return $cache->get($key);
        }
    }

    /**
     * Record getter
     *
     * @return String
     */
    public function getSquatRecordHolder()
    {
        $records = $this->getCurrentRecords();
        return $records['Squat']['Name'];
    }

    /**
     * Record getter
     *
     * @return String
     */
    public function getSquatRecordMeet()
    {
        $records = $this->getCurrentRecords();
        return $records['Squat']['Meet'];
    }

    /**
     * Record getter
     *
     * @return String
     */
    public function getSquatRecordWeight()
    {
        $records = $this->getCurrentRecords();
        return $records['Squat']['Weight'];
    }

    /**
     * Record getter
     *
     * @return String
     */
    public function getBenchRecordHolder()
    {
        $records = $this->getCurrentRecords();
        return $records['Bench']['Name'];
    }

    /**
     * Record getter
     *
     * @return String
     */
    public function getBenchRecordMeet()
    {
        $records = $this->getCurrentRecords();
        return $records['Bench']['Meet'];
    }

    /**
     * Record getter
     *
     * @return String
     */
    public function getBenchRecordWeight()
    {
        $records = $this->getCurrentRecords();
        return $records['Bench']['Weight'];
    }

    /**
     * Record getter
     *
     * @return String
     */
    public function getDeadliftRecordHolder()
    {
        $records = $this->getCurrentRecords();
        return $records['Deadlift']['Name'];
    }

    /**
     * Record getter
     *
     * @return String
     */
    public function getDeadliftRecordMeet()
    {
        $records = $this->getCurrentRecords();
        return $records['Deadlift']['Meet'];
    }

    /**
     * Record getter
     *
     * @return String
     */
    public function getDeadliftRecordWeight()
    {
        $records = $this->getCurrentRecords();
        return $records['Deadlift']['Weight'];
    }

    /**
     * Record getter
     *
     * @return String
     */
    public function getTotalRecordHolder()
    {
        $records = $this->getCurrentRecords();
        return $records['Total']['Name'];
    }

    /**
     * Record getter
     *
     * @return String
     */
    public function getTotalRecordMeet()
    {
        $records = $this->getCurrentRecords();
        return $records['Total']['Meet'];
    }

    /**
     * Record getter
     *
     * @return String
     */
    public function getTotalRecordWeight()
    {
        $records = $this->getCurrentRecords();
        return $records['Total']['Weight'];
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

    /**
     * Returns classes that this class can set records for
     *
     * @return Array
     */
    public function getLifterClassChildren()
    {
        $overrides = json_decode($this->Override, true);
        $lifterClasses = LifterClass::get()
            ->filter(
                [
                    'MinWeight' => $this->MinWeight,
                    'MaxWeight' => $this->MaxWeight,
                    'MinAge:LessThanOrEqual' => $this->MinAge,
                    'Gender' => $this->Gender,
                    'AffiliationID' => $this->AffiliationID,
                    'Active' => 1,
                    'Override' => null,
                ]
            )->map('ID', 'ID')
            ->toArray();
        $children = (isset($overrides))
            ? array_merge($lifterClasses, $overrides)
            : $lifterClasses;
        return $children;
    }

    /**
     * Returns Lifter weight class
     *
     * @return String
     */
    public function getLifterWeightClass()
    {
        if ($this->MaxWeight > 200) {
            return $this->MinWeight . '+';
        }
        return $this->MaxWeight;
    }
}


